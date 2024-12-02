<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор запроса
            $table->foreignId('user_id')->constrained(); // Внешний ключ на пользователя, создавшего запрос
            
            // Основная информация о запросе
            $table->string('title'); // Заголовок запроса
            $table->string('category')->nullable(); // Категория запроса (например: "помощь по дому", "ремонт")
            $table->text('description'); // Подробное описание запроса
            
            // Геолокационные данные
            $table->text('address'); // Физический адрес выполнения
            $table->decimal('latitude', 10, 8)->nullable(); // Широта для геолокации (от -90 до 90)
            $table->decimal('longitude', 11, 8)->nullable(); // Долгота для геолокации (от -180 до 180)
            
            // Статус и временные параметры
            $table->enum('status', [
                'pending',      // Ожидает исполнителя
                'in_progress',  // Взят в работу
                'completed',    // Завершен
                'cancelled'     // Отменен
            ])->default('pending');
            $table->dateTime('deadline')->nullable(); // Крайний срок выполнения
            $table->integer('estimated_time')->nullable(); // Предполагаемое время выполнения в минутах
            
            // Контактная информация и дополнительные параметры
            $table->string('contact_phone')->nullable(); // Контактный телефон для связи
            $table->string('urgency')->nullable(); // Срочность запроса (например: "высокая", "средняя", "низкая")
            $table->json('files')->nullable(); // Прикрепленные файлы (фотографии, документы)
            
            // Системные временные метки
            $table->timestamps(); // created_at и updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests'); // Удаление таблицы при откате миграции
    }
};
