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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор токена
            $table->morphs('tokenable'); // Полиморфная связь с моделью владельца токена
            $table->string('name'); // Название токена (например, "API Token")
            $table->string('token', 64)->unique(); // Хэшированное значение токена
            $table->text('abilities')->nullable(); // Список разрешений токена
            $table->timestamp('last_used_at')->nullable(); // Время последнего использования токена
            $table->timestamp('expires_at')->nullable(); // Время истечения срока действия токена
            $table->timestamps(); // Создает поля created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
