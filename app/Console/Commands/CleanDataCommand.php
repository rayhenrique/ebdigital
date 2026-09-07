<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebd:clean-data {--force : Executar sem confirmação interativa}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa todas as classes, alunos, presenças e chamadas para início em produção, preservando todos os usuários';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $usersCount = User::count();

        $this->warn("Atenção: Esta ação limpará todos os dados operacionais (Classes, Alunos, Chamadas e Presenças).");
        $this->info("Total de usuários que serão mantidos intactos: {$usersCount}");

        if (! $this->option('force') && ! $this->confirm('Deseja realmente continuar com a limpeza para produção?')) {
            $this->comment('Operação cancelada.');
            return self::SUCCESS;
        }

        $this->info('Iniciando limpeza das tabelas operacionais...');

        Schema::disableForeignKeyConstraints();

        $deletedAttendances = DB::table('lesson_attendances')->count();
        DB::table('lesson_attendances')->truncate();

        $deletedRecords = DB::table('lesson_records')->count();
        DB::table('lesson_records')->truncate();

        $deletedStudents = DB::table('students')->count();
        DB::table('students')->truncate();

        $deletedClassTeachers = DB::table('class_teacher')->count();
        DB::table('class_teacher')->truncate();

        $deletedClasses = DB::table('classes')->count();
        DB::table('classes')->truncate();

        // Limpar logs de auditoria dessas entidades antigas para deixar o banco completamente limpo
        $deletedAuditLogs = DB::table('audit_logs')->whereIn('auditable_type', [
            EbdClass::class,
            Student::class,
            LessonRecord::class,
            LessonAttendance::class,
            'App\Models\EbdClass',
            'App\Models\Student',
            'App\Models\LessonRecord',
            'App\Models\LessonAttendance',
        ])->delete();

        Schema::enableForeignKeyConstraints();

        $this->newLine();
        $this->table(
            ['Item', 'Status / Quantidade Removida'],
            [
                ['Alunos (students)', "{$deletedStudents} registros limpos"],
                ['Classes (classes)', "{$deletedClasses} registros limpos"],
                ['Vínculos de Professores (class_teacher)', "{$deletedClassTeachers} vínculos limpos"],
                ['Registros de Aula (lesson_records)', "{$deletedRecords} aulas limpas"],
                ['Presenças (lesson_attendances)', "{$deletedAttendances} presenças limpas"],
                ['Logs de Auditoria Antigos', "{$deletedAuditLogs} logs limpos"],
                ['Usuários Preservados (users)', "{$usersCount} usuários intactos"],
            ]
        );

        $this->newLine();
        $this->info('Banco de dados limpo com sucesso para entrada em produção!');

        return self::SUCCESS;
    }
}
