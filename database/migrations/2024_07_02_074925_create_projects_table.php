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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('whatsapp_group_id')->nullable();
            $table->string('facebook_user')->nullable();
            $table->string('insta_user')->nullable();
            $table->string('tiktok_user')->nullable();
            $table->string('instagram_user')->nullable();
            $table->string('snap_user')->nullable();
            $table->string('x_user')->nullable();
            $table->string('facebook_pass')->nullable();
            $table->string('insta_pass')->nullable();
            $table->string('tiktok_pass')->nullable();
            $table->string('instagram_pass')->nullable();
            $table->string('snap_pass')->nullable();
            $table->string('x_pass')->nullable();
            $table->string('store_url')->nullable();
            $table->string('store_user')->nullable();
            $table->string('store_password')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
