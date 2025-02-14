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
       
        Schema::create('project_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_id')->constrained('emps');
            $table->foreignId('project_id')->constrained('projects');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('moderator_id')->nullable()->constrained('emps');
            $table->foreignId('copy_writer_id')->nullable()->constrained('emps');
            $table->foreignId('media_buyer_id')->nullable()->constrained('emps');
            $table->foreignId('graphic_designer_id')->nullable()->constrained('emps');
            $table->foreignId('video_designer_id')->nullable()->constrained('emps');
            $table->foreignId('programmer_id')->nullable()->constrained('emps');
            $table->foreignId('seo_specialist_id')->nullable()->constrained('emps');
            $table->string('files_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_plans');
    }
};
