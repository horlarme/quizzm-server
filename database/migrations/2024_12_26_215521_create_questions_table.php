<?php

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
        Schema::create('questions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('quiz_id')
                ->constrained('quizzes', 'id')
                ->cascadeOnDelete();

            $table->string('title', 250);
            $table->enum('option_type', \App\Models\Question::OptionTypes)
                ->default(\App\Models\Question::OptionTypeText);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
