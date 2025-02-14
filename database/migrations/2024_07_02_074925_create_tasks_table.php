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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('sender_id')->constrained('emps')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('emps')->onDelete('cascade');
            $table->integer('time_in_minutes')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('recurrence_interval_days')->nullable();
            $table->datetime('next_occurrence')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
