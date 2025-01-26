<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Основная информация о запросе
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('description');
            
            // Геолокационные данные
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Статус и временные параметры
            $table->string('status')->default('pending');
            $table->dateTime('deadline')->nullable();
            $table->integer('estimated_time')->nullable();
            
            // Контактная информация и дополнительные параметры
            $table->string('contact_phone')->nullable();
            $table->string('urgency')->nullable();
            $table->jsonb('files')->nullable();
            
            // Индексы для оптимизации
            $table->index('status');
            $table->index(['latitude', 'longitude']);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
