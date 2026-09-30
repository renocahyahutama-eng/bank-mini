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
        Schema::create('nasabahs', function (Blueprint $table) {
            $table->id();
            $table->string('account_number', 20)->unique();
            $table->string('student_number', 20)->unique();
            $table->string('student_name', 150);
            $table->string('class', 20);
            $table->string('jurusan', 20)->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('security_pin');
            $table->decimal('balance', 15, 2)->default(0);
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabahs');
    }
};
