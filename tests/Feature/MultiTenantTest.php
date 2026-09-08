<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Congregation;
use App\Models\EbdClass;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_data_isolation_between_congregations(): void
    {
        $sede = Congregation::where('is_headquarters', true)->first() ?? Congregation::create([
            'name' => 'Templo Sede',
            'slug' => 'templo-sede-teste',
            'is_headquarters' => true,
            'is_active' => true,
        ]);

        $canaa = Congregation::create([
            'name' => 'Congregação Canaã',
            'slug' => 'congregacao-canaa-teste',
            'is_headquarters' => false,
            'is_active' => true,
        ]);

        $secSede = User::factory()->create([
            'congregation_id' => $sede->id,
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
        ]);

        $secCanaa = User::factory()->create([
            'congregation_id' => $canaa->id,
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
        ]);

        $classSede = EbdClass::create([
            'congregation_id' => $sede->id,
            'name' => 'Turma Adultos Sede',
            'is_active' => true,
        ]);

        $classCanaa = EbdClass::create([
            'congregation_id' => $canaa->id,
            'name' => 'Turma Adultos Canaã',
            'is_active' => true,
        ]);

        // Secretário da Sede vê apenas a turma da Sede
        $this->actingAs($secSede);
        $this->assertEquals(1, EbdClass::count());
        $this->assertEquals('Turma Adultos Sede', EbdClass::first()->name);

        $responseSede = $this->get(route('classes.index'));
        $responseSede->assertSee('Turma Adultos Sede');
        $responseSede->assertDontSee('Turma Adultos Canaã');

        // Tentativa do Secretário da Sede de acessar a turma de Canaã resulta em 404 (oculto pelo escopo global)
        $responseForbidden = $this->get(route('classes.edit', $classCanaa));
        $responseForbidden->assertNotFound();

        // Secretário de Canaã vê apenas a turma de Canaã
        $this->actingAs($secCanaa);
        $this->assertEquals(1, EbdClass::count());
        $this->assertEquals('Turma Adultos Canaã', EbdClass::first()->name);

        $responseCanaa = $this->get(route('classes.index'));
        $responseCanaa->assertSee('Turma Adultos Canaã');
        $responseCanaa->assertDontSee('Turma Adultos Sede');
    }

    public function test_admin_can_view_all_or_switch_congregation_context(): void
    {
        $sede = Congregation::where('is_headquarters', true)->first() ?? Congregation::create([
            'name' => 'Sede',
            'slug' => 'sede-admin-teste',
            'is_headquarters' => true
        ]);

        $canaa = Congregation::create([
            'name' => 'Canaã',
            'slug' => 'canaa-admin-teste',
            'is_headquarters' => false
        ]);

        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'congregation_id' => null,
            'is_active' => true,
        ]);

        EbdClass::create(['congregation_id' => $sede->id, 'name' => 'Classe Sede', 'is_active' => true]);
        EbdClass::create(['congregation_id' => $canaa->id, 'name' => 'Classe Canaã', 'is_active' => true]);

        // Sem seleção na sessão, Admin vê todas as classes
        $this->actingAs($admin);
        $this->assertEquals(2, EbdClass::count());

        // Admin seleciona congregação Canaã
        $response = $this->post(route('admin.congregacoes.switch'), [
            'congregation_id' => $canaa->id,
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('selected_congregation_id', $canaa->id);

        $this->assertEquals(1, EbdClass::count());
        $this->assertEquals('Classe Canaã', EbdClass::first()->name);

        // Admin volta para "todas as congregações"
        $this->post(route('admin.congregacoes.switch'), [
            'congregation_id' => 'all',
        ]);
        $this->assertEquals(2, EbdClass::count());

        // Ao tentar criar classe sem congregação selecionada, deve redirecionar com aviso amigável
        $createResponse = $this->get(route('classes.create'));
        $createResponse->assertRedirect(route('classes.index'));
        $createResponse->assertSessionHas('warning', 'Selecione uma congregação para criar uma turma.');

        // E ao tentar salvar via POST também é barrado com aviso
        $storeResponse = $this->post(route('classes.store'), [
            'name' => 'Classe Sem Congregacao',
        ]);
        $storeResponse->assertRedirect(route('classes.index'));
        $storeResponse->assertSessionHas('warning', 'Selecione uma congregação para criar uma turma.');

        // CRUD completo desativado em modo "todas as congregações"
        $classToTest = EbdClass::first();
        $this->get(route('classes.edit', $classToTest))
            ->assertRedirect(route('classes.index'))
            ->assertSessionHas('warning');

        $this->put(route('classes.update', $classToTest), ['name' => 'Tentativa'])
            ->assertRedirect(route('classes.index'))
            ->assertSessionHas('warning');

        $this->patch(route('classes.toggle', $classToTest))
            ->assertRedirect(route('classes.index'))
            ->assertSessionHas('warning');

        $this->delete(route('classes.destroy', $classToTest))
            ->assertRedirect(route('classes.index'))
            ->assertSessionHas('warning');

        // Na listagem geral, exibe o alerta visual de modo geral (somente leitura)
        $indexResponse = $this->get(route('classes.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Modo Geral (Somente Leitura)');
        $indexResponse->assertSee('Somente leitura');
    }

    public function test_secretario_can_manage_teachers_only_for_own_congregation(): void
    {
        $sede = Congregation::where('is_headquarters', true)->first() ?? Congregation::create([
            'name' => 'Templo Sede',
            'slug' => 'sede-prof-teste',
            'is_headquarters' => true
        ]);

        $secSede = User::factory()->create([
            'congregation_id' => $sede->id,
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'congregation_id' => $sede->id,
            'name' => 'Classe Jovens Sede',
            'is_active' => true,
        ]);

        $this->actingAs($secSede);

        // Criar novo professor
        $response = $this->post(route('professores.store'), [
            'name' => 'Novo Professor da Sede',
            'email' => 'prof.novo@ebd.local',
            'password' => 'senha123',
            'phone' => '82999990000',
            'class_ids' => [$class->id],
            'is_active' => true,
        ]);

        $response->assertRedirect(route('professores.index'));

        $teacher = User::where('email', 'prof.novo@ebd.local')->first();
        $this->assertNotNull($teacher);
        $this->assertEquals(UserRole::PROFESSOR, $teacher->role);
        $this->assertEquals($sede->id, $teacher->congregation_id);
        $this->assertTrue($teacher->teachingClasses->contains($class->id));
    }

    public function test_secretario_cannot_edit_teacher_from_another_congregation(): void
    {
        $sede = Congregation::where('is_headquarters', true)->first() ?? Congregation::create([
            'name' => 'Templo Sede',
            'slug' => 'sede-edit-teste',
            'is_headquarters' => true
        ]);

        $canaa = Congregation::create([
            'name' => 'Canaã',
            'slug' => 'canaa-edit-teste',
            'is_headquarters' => false
        ]);

        $secSede = User::factory()->create([
            'congregation_id' => $sede->id,
            'role' => UserRole::SECRETARIO,
        ]);

        $profCanaa = User::factory()->create([
            'congregation_id' => $canaa->id,
            'role' => UserRole::PROFESSOR,
        ]);

        $this->actingAs($secSede);

        $response = $this->get(route('professores.edit', $profCanaa));
        $response->assertForbidden();

        $responseUpdate = $this->put(route('professores.update', $profCanaa), [
            'name' => 'Hacked Name',
            'email' => $profCanaa->email,
        ]);
        $responseUpdate->assertForbidden();
    }

    public function test_admin_can_manage_congregations_crud(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $sec = User::factory()->create(['role' => UserRole::SECRETARIO]);

        // Não admin recebe 403
        $this->actingAs($sec)->get(route('admin.congregacoes.index'))->assertForbidden();

        // Admin acessa e cadastra congregação
        $this->actingAs($admin);
        $response = $this->post(route('admin.congregacoes.store'), [
            'name' => 'Congregação Betel',
            'pastor_dirigente' => 'Ev. Lucas Ribeiro',
            'city' => 'Maceió',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.congregacoes.index'));

        $betel = Congregation::where('name', 'Congregação Betel')->first();
        $this->assertNotNull($betel);
        $this->assertEquals('congregacao-betel', $betel->slug);

        // Acessar tela de edição
        $editResponse = $this->get(route('admin.congregacoes.edit', $betel));
        $editResponse->assertOk();
        $editResponse->assertSee('Editar: Congregação Betel');

        // Alternar status
        $this->patch(route('admin.congregacoes.toggle', $betel));
        $this->assertFalse($betel->fresh()->is_active);
    }

    public function test_lesson_record_saves_with_correct_congregation(): void
    {
        $canaa = Congregation::create([
            'name' => 'Canaã',
            'slug' => 'canaa-record-teste',
            'is_headquarters' => false
        ]);

        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $class = EbdClass::create([
            'congregation_id' => $canaa->id,
            'name' => 'Classe Canaã',
            'is_active' => true,
        ]);

        $student = Student::create([
            'congregation_id' => $canaa->id,
            'class_id' => $class->id,
            'name' => 'Aluno de Canaã',
            'is_active' => true,
        ]);

        $record = LessonRecord::create([
            'congregation_id' => $canaa->id,
            'class_id' => $class->id,
            'registered_by' => $admin->id,
            'lesson_date' => now()->format('Y-m-d'),
            'visitors_count' => 2,
        ]);

        $this->assertEquals($canaa->id, $record->fresh()->congregation_id);
    }

    public function test_students_crud_is_disabled_in_all_congregations_mode(): void
    {
        $sede = Congregation::where('is_headquarters', true)->first() ?? Congregation::create([
            'name' => 'Templo Sede',
            'slug' => 'sede-student-lock',
            'is_headquarters' => true,
        ]);

        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'congregation_id' => null,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'congregation_id' => $sede->id,
            'name' => 'Classe Teste Alunos',
            'is_active' => true,
        ]);

        $student = Student::create([
            'congregation_id' => $sede->id,
            'class_id' => $class->id,
            'name' => 'Aluno Bloqueio Geral',
            'is_active' => true,
        ]);

        // Sem congregação na sessão (Modo Todas as Congregações)
        $this->actingAs($admin);

        // Tela de listagem exibe avisos de modo somente leitura
        $indexRes = $this->get(route('alunos.index'));
        $indexRes->assertOk();
        $indexRes->assertSee('Modo Geral (Somente Leitura)');
        $indexRes->assertSee('Somente leitura');
        $indexRes->assertSee('Selecione uma Congregação');

        // Create barrado
        $this->get(route('alunos.create'))
            ->assertRedirect(route('alunos.index'))
            ->assertSessionHas('warning');

        // Store barrado
        $this->post(route('alunos.store'), [
            'name' => 'Aluno Invalido',
            'class_id' => $class->id,
        ])->assertRedirect(route('alunos.index'))
          ->assertSessionHas('warning');

        // Edit barrado
        $this->get(route('alunos.edit', $student))
            ->assertRedirect(route('alunos.index'))
            ->assertSessionHas('warning');

        // Update barrado
        $this->put(route('alunos.update', $student), [
            'name' => 'Aluno Nome Alterado',
            'class_id' => $class->id,
        ])->assertRedirect(route('alunos.index'))
          ->assertSessionHas('warning');

        // Toggle barrado
        $this->patch(route('alunos.toggle', $student))
            ->assertRedirect(route('alunos.index'))
            ->assertSessionHas('warning');

        // Destroy barrado
        $this->delete(route('alunos.destroy', $student))
            ->assertRedirect(route('alunos.index'))
            ->assertSessionHas('warning');
    }

    public function test_teachers_crud_is_disabled_in_all_congregations_mode(): void
    {
        $sede = Congregation::where('is_headquarters', true)->first() ?? Congregation::create([
            'name' => 'Templo Sede',
            'slug' => 'sede-teacher-lock',
            'is_headquarters' => true,
        ]);

        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'congregation_id' => null,
            'is_active' => true,
        ]);

        $teacher = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'congregation_id' => $sede->id,
            'name' => 'Professor Bloqueio Geral',
            'email' => 'prof.lock@ebd.local',
            'is_active' => true,
        ]);

        // Sem congregação na sessão (Modo Todas as Congregações)
        $this->actingAs($admin);

        // Tela de listagem exibe avisos de modo somente leitura
        $indexRes = $this->get(route('professores.index'));
        $indexRes->assertOk();
        $indexRes->assertSee('Modo Geral (Somente Leitura)');
        $indexRes->assertSee('Somente leitura');
        $indexRes->assertSee('Selecione uma Congregação');

        // Create barrado
        $this->get(route('professores.create'))
            ->assertRedirect(route('professores.index'))
            ->assertSessionHas('warning');

        // Store barrado
        $this->post(route('professores.store'), [
            'name' => 'Professor Invalido',
            'email' => 'invalido@ebd.local',
            'password' => 'senha123',
        ])->assertRedirect(route('professores.index'))
          ->assertSessionHas('warning');

        // Edit barrado
        $this->get(route('professores.edit', $teacher))
            ->assertRedirect(route('professores.index'))
            ->assertSessionHas('warning');

        // Update barrado
        $this->put(route('professores.update', $teacher), [
            'name' => 'Professor Nome Alterado',
            'email' => $teacher->email,
        ])->assertRedirect(route('professores.index'))
          ->assertSessionHas('warning');

        // Toggle barrado
        $this->patch(route('professores.toggle', $teacher))
            ->assertRedirect(route('professores.index'))
            ->assertSessionHas('warning');

        // Reset password barrado
        $this->patch(route('professores.reset-password', $teacher), [
            'password' => 'novasenha123',
        ])->assertRedirect(route('professores.index'))
          ->assertSessionHas('warning');
    }
}
