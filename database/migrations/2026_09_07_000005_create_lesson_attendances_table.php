<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lesson_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_record_id')->constrained('lesson_records')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('restrict');
            $table->boolean('is_present')->default(false);
            $table->timestamps();

            $table->unique(['lesson_record_id', 'student_id'], 'unique_record_student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_attendances');
    }
};
