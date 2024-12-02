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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор задачи
            $table->string('queue')->index(); // Название очереди, индексировано для быстрого поиска
            $table->longText('payload'); // Данные задачи в сериализованном виде
            $table->unsignedTinyInteger('attempts'); // Количество попыток выполнения задачи
            $table->unsignedInteger('reserved_at')->nullable(); // Время, когда задача была зарезервирована для выполнения
            $table->unsignedInteger('available_at'); // Время, когда задача станет доступна для выполнения
            $table->unsignedInteger('created_at'); // Время создания задачи
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // Уникальный идентификатор пакета задач
            $table->string('name'); // Название пакета задач
            $table->integer('total_jobs'); // Общее количество задач в пакете
            $table->integer('pending_jobs'); // Количество ожидающих выполнения задач
            $table->integer('failed_jobs'); // Количество неудачных задач
            $table->longText('failed_job_ids'); // Список ID неудачных задач
            $table->mediumText('options')->nullable(); // Дополнительные опции пакета
            $table->integer('cancelled_at')->nullable(); // Время отмены пакета
            $table->integer('created_at'); // Время создания пакета
            $table->integer('finished_at')->nullable(); // Время завершения пакета
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // Уникальный идентификатор неудачной задачи
            $table->string('uuid')->unique(); // Уникальный UUID для идентификации задачи
            $table->text('connection'); // Информация о соединении с очередью
            $table->text('queue'); // Название очереди
            $table->longText('payload'); // Данные задачи в сериализованном виде
            $table->longText('exception'); // Информация об ошибке, вызвавшей сбой
            $table->timestamp('failed_at')->useCurrent(); // Время сбоя задачи
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
