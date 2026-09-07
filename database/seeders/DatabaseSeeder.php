<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar Usuários
        $admin = User::create([
            'name' => 'Pastor / Administrador',
            'email' => 'admin@ebd.local',
            'password' => Hash::make('senha123'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $secretario = User::create([
            'name' => 'Superintendente / Secretaria',
            'email' => 'secretario@ebd.local',
            'password' => Hash::make('senha123'),
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
        ]);

        $prof1 = User::create([
            'name' => 'Prof. Barnabé Silva',
            'email' => 'professor1@ebd.local',
            'password' => Hash::make('senha123'),
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $prof2 = User::create([
            'name' => 'Profª. Débora Oliveira',
            'email' => 'professor2@ebd.local',
            'password' => Hash::make('senha123'),
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        // 2. Criar Classes
        $classAdultos = EbdClass::create([
            'name' => 'Classe Adultos (Bereanos)',
            'description' => 'Estudo aprofundado das epístolas paulinas para membros adultos.',
            'is_active' => true,
        ]);

        $classJovens = EbdClass::create([
            'name' => 'Classe Jovens (Geração Eleita)',
            'description' => 'Discipulado prático, cosmovisão bíblica e vida cristã na universidade/trabalho.',
            'is_active' => true,
        ]);

        $classAdolescentes = EbdClass::create([
            'name' => 'Classe Adolescentes (Conectados)',
            'description' => 'Estudos dinâmicos para adolescentes de 12 a 17 anos.',
            'is_active' => true,
        ]);

        $classCriancas = EbdClass::create([
            'name' => 'Classe Infantil (Cordeirinhos)',
            'description' => 'Histórias bíblicas ilustradas e atividades para crianças de 6 a 11 anos.',
            'is_active' => true,
        ]);

        // 3. Vincular Professores às Classes
        $classAdultos->teachers()->attach([$prof1->id]);
        $classJovens->teachers()->attach([$prof1->id, $prof2->id]);
        $classAdolescentes->teachers()->attach([$prof2->id]);
        $classCriancas->teachers()->attach([$prof2->id]);

        // 4. Criar Alunos
        $alunosData = [
            $classAdultos->id => [
                ['name' => 'Antônio Carlos Silva', 'phone' => '(82) 98801-1001', 'birth_date' => '1975-04-12'],
                ['name' => 'Benedita Maria Santos', 'phone' => '(82) 98801-1002', 'birth_date' => '1980-08-25'],
                ['name' => 'Carlos Eduardo Ferreira', 'phone' => '(82) 98801-1003', 'birth_date' => '1968-11-03'],
                ['name' => 'Dorotéia Gusmão', 'phone' => '(82) 98801-1004', 'birth_date' => '1972-02-17'],
                ['name' => 'Ezequiel Mendes Rocha', 'phone' => '(82) 98801-1005', 'birth_date' => '1985-09-30'],
            ],
            $classJovens->id => [
                ['name' => 'Felipe Gabriel Souza', 'phone' => '(82) 99902-2001', 'birth_date' => '2001-05-14'],
                ['name' => 'Gabriela Costa Lima', 'phone' => '(82) 99902-2002', 'birth_date' => '2002-10-21'],
                ['name' => 'Heitor Vasconcelos', 'phone' => '(82) 99902-2003', 'birth_date' => '2000-01-09'],
                ['name' => 'Isabela Nogueira', 'phone' => '(82) 99902-2004', 'birth_date' => '2003-07-18'],
            ],
            $classAdolescentes->id => [
                ['name' => 'João Pedro Ribeiro', 'phone' => '(82) 98703-3001', 'birth_date' => '2009-03-15'],
                ['name' => 'Karina Albuquerque', 'phone' => '(82) 98703-3002', 'birth_date' => '2010-06-22'],
                ['name' => 'Lucas Vinícius Prado', 'phone' => '(82) 98703-3003', 'birth_date' => '2008-12-05'],
            ],
            $classCriancas->id => [
                ['name' => 'Mateus Henrique Dias', 'phone' => null, 'birth_date' => '2016-04-10'],
                ['name' => 'Noemi Cavalcante', 'phone' => null, 'birth_date' => '2017-09-18'],
                ['name' => 'Otávio Augusto Teles', 'phone' => null, 'birth_date' => '2015-11-28'],
            ],
        ];

        foreach ($alunosData as $classId => $students) {
            foreach ($students as $data) {
                Student::create([
                    'class_id' => $classId,
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'birth_date' => $data['birth_date'],
                    'is_active' => true,
                ]);
            }
        }

        // 5. Criar Registro de Aula de Exemplo na Classe de Adultos para Hoje
        $lessonToday = LessonRecord::create([
            'class_id' => $classAdultos->id,
            'registered_by' => $prof1->id,
            'lesson_date' => now()->format('Y-m-d'),
            'lesson_number' => 'Lição 01',
            'lesson_title' => 'A Justificação pela Fé em Cristo',
            'visitors_count' => 2,
            'bibles_count' => 4,
            'magazines_count' => 4,
            'offerings_amount' => 45.50,
            'observations' => 'Excelente participação da classe no debate inicial.',
        ]);

        $adultosStudents = Student::where('class_id', $classAdultos->id)->get();
        foreach ($adultosStudents as $index => $std) {
            LessonAttendance::create([
                'lesson_record_id' => $lessonToday->id,
                'student_id' => $std->id,
                'is_present' => $index !== 2, // 1 ausente para demonstrar cálculo
            ]);
        }

        // 6. Log de Auditoria Inicial
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'DATABASE_SEEDED',
            'auditable_type' => User::class,
            'auditable_id' => $admin->id,
            'payload_before' => null,
            'payload_after' => ['message' => 'Carga inicial de dados do sistema EBD realizada com sucesso.'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'CLI DatabaseSeeder',
            'created_at' => now(),
        ]);
    }
}
