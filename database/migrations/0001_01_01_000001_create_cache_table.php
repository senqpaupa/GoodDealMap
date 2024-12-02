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
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Уникальный ключ кэша, используется как первичный ключ
            $table->mediumText('value'); // Закэшированные данные в сериализованном виде
            $table->integer('expiration'); // Время истечения срока действия кэша в Unix timestamp
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Уникальный ключ блокировки
            $table->string('owner'); // Идентификатор владельца блокировки
            $table->integer('expiration'); // Время истечения блокировки в Unix timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
