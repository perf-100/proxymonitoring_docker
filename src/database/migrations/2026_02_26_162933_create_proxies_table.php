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
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('host');
            $table->unsignedBigInteger('port');
            $table->string('login')->nullable();
            $table->string('password')->nullable();
            $table->enum('type', ['http', 'https', 'socks4', 'socks5'])->default('http');
            $table->enum('status', ['unknown', 'working', 'failed'])->default('unknown');
            $table->timestamp('checked_at')->nullable();
            $table->unsignedBigInteger('check_interval')->default(300);
            $table->longtext('comment')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proxies');
    }
};
