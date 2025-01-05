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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор пользователя
            $table->string('name'); // Имя пользователя
            $table->string('email')->unique(); // Уникальный email пользователя
            $table->string('phone')->unique(); // @фывфывУникальный номер телефона пользователя
            $table->string('password'); // Хэшированный пароль пользователя
            $table->string('avatar')->nullable(); // Путь к файлу аватара пользователя
            $table->float('rating')->default(5.0); // Рейтинг пользователя, по умолчанию 5.0
            $table->enum('status', ['active', 'blocked', 'pending'])->default('pending'); // Статус аккаунта
            $table->rememberToken(); // Токен для функции "Запомнить меня"
            $table->timestamps(); // Создает поля created_at и updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email пользователя как первичный ключ
            $table->string('token'); // Токен для сброса пароля
            $table->timestamp('created_at')->nullable(); // Время создания токена
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Уникальный идентификатор сессии
            $table->foreignId('user_id')->nullable()->index(); // Внешний ключ на пользователя
            $table->string('ip_address', 45)->nullable(); // IP адрес пользователя
            $table->text('user_agent')->nullable(); // Информация о браузере пользователя
            $table->longText('payload'); // Данные сессии в сериализованном виде
            $table->integer('last_activity')->index(); // Время последней активности, индексировано
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
