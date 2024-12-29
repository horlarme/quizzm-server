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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title', 250);
            $table->longText('description');
            $table->string('thumbnail');

            $table->enum('status', \App\Models\Quiz::Statuses)
                ->index()
                ->default(\App\Models\Quiz::StatusDraft);

            $table->boolean('require_registration')->default(false);
            $table->boolean('require_approval')->default(false);
            $table->enum('start_type', \App\Models\Quiz::StartTypes)
                ->default(\App\Models\Quiz::StartTypeUser);
            $table->timestamp('start_time')->nullable();
            $table->enum('visibility', \App\Models\Quiz::Visibilities)
                ->default(\App\Models\Quiz::VisibilityPublic);

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
