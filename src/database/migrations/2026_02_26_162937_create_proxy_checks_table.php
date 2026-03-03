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
        Schema::create('proxy_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proxy_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['working', 'failed'])->default('working');
            $table->unsignedInteger('time')->nullable();
            $table->string('ip_addr')->nullable();
            $table->longtext('message')->nullable();
            $table->timestamps();

            $table->index(['proxy_id', 'status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proxy_checks');
    }
};
