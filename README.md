# GoodDealMap API
  
Добро пожаловать в проект GoodDealMap API. Это RESTful API, разработанный на Laravel, который использует Laravel Sanctum для аутентификации пользователей и управления токенами.
## Описание проекта


![Screenshot](https://i.postimg.cc/VrHvVtjM/temp-Image-GCFHn-A.avif)
![Screenshot](https://i.postimg.cc/n9jrbLkW/temp-Imageg-WFi-Tk.avif)
![Screenshot](https://i.postimg.cc/DW681H0x/temp-Image-TBxl-RT.avif)
![Screenshot](https://i.postimg.cc/wyB322sg/temp-Imageujn-O67.avif)

GoodDealMap — это платформа, которая позволяет пользователям добавлять задания на карту, чтобы другие могли их видеть и помогать с выполнением этих просьб. Основная идея проекта — создание сообщества, где люди могут делиться своими нуждами и получать помощь от других участников.

## Установка

1. **Клонируйте репозиторий:**

   ```bash
   git clone https://github.com/your-username/gooddealmap.git
   cd gooddealmap
   ```

2. **Установите зависимости:**

   Убедитесь, что у вас установлен Composer, затем выполните:

   ```bash
   composer install
   ```

3. **Настройте файл окружения:**

   Скопируйте `.env.example` в `.env` и настройте параметры базы данных и другие переменные окружения:

   ```bash
   cp .env.example .env
   ```

4. **Сгенерируйте ключ приложения:**

   ```bash
   php artisan key:generate
   ```

5. **Запустите миграции:**

   Убедитесь, что база данных настроена правильно, затем выполните:

   ```bash
   php artisan migrate
   ```

6. **Запустите сервер:**

   ```bash
   php artisan serve
   ```

## Использование

### Аутентификация

API использует Laravel Sanctum для аутентификации. Убедитесь, что вы добавили заголовок `Authorization` с токеном в формате `Bearer <token>` для защищенных маршрутов.

### Маршруты

- **POST /api/register**: Зарегистрировать пользователя
- **POST /api/login**: Авторизировать пользователя
- **POST /api/logout**: Деавторизация пользователя
- **GET /api/user/userinfo**: Получение информации о пользователе
- **GET /api/user/userinfo/{id}**: Получение информации о конкретном пользователе
- **POST /api/user/avatar**: Добавление аватара ползователю
- **PUT /api/user/profile**: Редактирование профиля пользователя
- **GET /api/requests/nearby**: Получиь информацию о ближайших по георасположению заданиях
- **POST /api/requests/**: Создать задание 
- **GET /api/requests/{id}**: Получить конкретное задание.
- **PUT /api/requests/{id}**: Обновить существующее задание.
- **DELETE /api/requests/{id}**: Удалить задание.


## Вклад

Если вы хотите внести свой вклад в проект, пожалуйста, создайте форк репозитория и отправьте pull request с вашими изменениями.



