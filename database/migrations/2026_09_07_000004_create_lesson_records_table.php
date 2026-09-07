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
        Schema::create('lesson_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('restrict');
            $table->foreignId('registered_by')->constrained('users')->onDelete('restrict');
            $table->date('lesson_date');
            $table->string('lesson_number', 50)->nullable();
            $table->string('lesson_title', 255)->nullable();
            $table->unsignedInteger('visitors_count')->default(0);
            $table->unsignedInteger('bibles_count')->default(0);
            $table->unsignedInteger('magazines_count')->default(0);
            $table->decimal('offerings_amount', 10, 2)->default(0.00);
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->unique(['class_id', 'lesson_date'], 'unique_class_lesson_date');
            $table->index('lesson_date', 'idx_lesson_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_records');
    }
};
