<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Garantir existência do Templo Sede para migração limpa sem quebra de integridade
        $headquartersId = DB::table('congregations')->where('is_headquarters', true)->value('id');
        if (! $headquartersId) {
            $headquartersId = DB::table('congregations')->insertGetId([
                'name' => 'Templo Sede',
                'slug' => 'templo-sede',
                'pastor_dirigente' => 'Pastor Presidente',
                'city' => 'Maceió',
                'is_headquarters' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Tabela users: congregation_id
        if (! Schema::hasColumn('users', 'congregation_id')) {
            Schema::table('users', function (Blueprint $table) use ($headquartersId) {
                $table->foreignId('congregation_id')
                    ->nullable()
                    ->default($headquartersId)
                    ->after('id')
                    ->constrained('congregations')
                    ->nullOnDelete();
            });
        }

        // 3. Tabela classes: congregation_id
        if (! Schema::hasColumn('classes', 'congregation_id')) {
            Schema::table('classes', function (Blueprint $table) use ($headquartersId) {
                $table->foreignId('congregation_id')
                    ->default($headquartersId)
                    ->after('id')
                    ->constrained('congregations')
                    ->cascadeOnDelete();
            });
        }

        // 4. Tabela students: congregation_id
        if (! Schema::hasColumn('students', 'congregation_id')) {
            Schema::table('students', function (Blueprint $table) use ($headquartersId) {
                $table->foreignId('congregation_id')
                    ->default($headquartersId)
                    ->after('id')
                    ->constrained('congregations')
                    ->cascadeOnDelete();
            });
        }

        // 5. Tabela lesson_records: congregation_id
        if (! Schema::hasColumn('lesson_records', 'congregation_id')) {
            Schema::table('lesson_records', function (Blueprint $table) use ($headquartersId) {
                $table->foreignId('congregation_id')
                    ->default($headquartersId)
                    ->after('id')
                    ->constrained('congregations')
                    ->cascadeOnDelete();
            });
        }

        // 6. Tabela audit_logs: congregation_id
        if (! Schema::hasColumn('audit_logs', 'congregation_id')) {
            Schema::table('audit_logs', function (Blueprint $table) use ($headquartersId) {
                $table->foreignId('congregation_id')
                    ->nullable()
                    ->default($headquartersId)
                    ->after('user_id')
                    ->constrained('congregations')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('audit_logs', 'congregation_id')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropConstrainedForeignId('congregation_id');
            });
        }

        if (Schema::hasColumn('lesson_records', 'congregation_id')) {
            Schema::table('lesson_records', function (Blueprint $table) {
                $table->dropConstrainedForeignId('congregation_id');
            });
        }

        if (Schema::hasColumn('students', 'congregation_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropConstrainedForeignId('congregation_id');
            });
        }

        if (Schema::hasColumn('classes', 'congregation_id')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->dropConstrainedForeignId('congregation_id');
            });
        }

        if (Schema::hasColumn('users', 'congregation_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('congregation_id');
            });
        }
    }
};
