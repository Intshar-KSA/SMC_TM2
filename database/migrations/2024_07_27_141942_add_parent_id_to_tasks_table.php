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
        Schema::table('tasks', function (Blueprint $table) {
            // إضافة حقل parent_id إلى الجدول
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // إزالة حقل parent_id من الجدول
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
