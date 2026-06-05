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
        Schema::create('pos_terminal_logs', function (Blueprint $table) {
            $table->id();
            $table->string('agent_name')->nullable();
            $table->string('level', 20); // INFO, DEBUG, ERROR, CRITICAL
            $table->text('message');
            $table->dateTime('client_timestamp'); // Windows PC එකේ වෙලාව
            $table->timestamps(); // Laravel එකට Log එක ආපු වෙලාව
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_terminal_logs');
    }
};
