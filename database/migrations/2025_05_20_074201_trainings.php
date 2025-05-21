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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            // $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('start_time');
            // Указать не дату, а день недели, и заменить datetime на time
            $table->dateTime('end_time');
            $table->string('location');
            $table->text('notes')->nullable();
            // статус тренировки (активно, завершено)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
