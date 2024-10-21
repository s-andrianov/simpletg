# SimpleTG

SimpleTG — PHP библиотека для работы с Telegram Bot API. Она предоставляет простые и удобные методы для отправки, редактирования и удаления сообщений, работы с клавиатурами, получения данных о пользователе и многое другое.

## Установка

Установите библиотеку через Composer (если используете) или просто загрузите исходный код.

```bash
composer require s-andrianov/simpletg
```

## Использование

Создайте объект Telegram, передав токен вашего бота:

```php
require 'telegram.php';

$bot = new Telegram('YOUR_BOT_TOKEN');
```

## Методы

### [reply](#reply)

Отправляет текстовое сообщение с режимом Markdown.

**Пример:**

```php
$bot->reply($chat_id, 'Привет!');
```

### [sendEdit](#sendEdit)

Редактирует текст и клавиатуру сообщения одновременно.

**Пример:**

```php
$bot->sendEdit($chat_id, $callback_message_id, $new_text, $new_keyboard);
```

### [sendMessage](#sendMessage)

Отправляет сообщение с возможностью добавить клавиатуру. Поддерживаются обычные и inline-клавиатуры.

**Пример:**

```php
$keyboard = [
    [['Кнопка 1', 'callback_data_1'], ['Кнопка 2', 'callback_data_2']]
];
$bot->sendMessage($chat_id, $text, $keyboard, $is_inline);
```

### [html](#html)

Отправляет сообщение в HTML-формате.

**Пример:**

```php
$bot->html($chat_id, '<b>bold-текст</b>');
```

### [deleteMessage](#deleteMessage)

Удаляет сообщение по его ID.

**Пример:**

```php
$bot->deleteMessage($chat_id, $message_id);
```

### [showAlert](#showAlert)

Показывает алерт пользователю после нажатия кнопки.

**Пример:**

```php
$bot->showAlert($callback_query_id, $text);
```

### [answerInline](#answerInline)

Отправляет ответ на inline-запрос.

**Пример:**

```php
$result = [
    "type" => "article",
    "id" => 1,
    "title" => "Вода (0.5л)",
    "description" => "Цена: 50 руб.",
    "thumbnail_url" => "image.png",
    "input_message_content" => [
        "message_text" => "/cart 1",
        "parse_mode" => "Markdown",
    ],
];

$bot->answerInline($inline_query_id, [$result]);
```

### [getPhoto](#getPhoto)

Получает фотографию профиля пользователя по его user_id.

**Пример:**

```php
$photo = $bot->getPhoto($user_id);
```

### [getFilePath](#getFilePath)

Получает путь к файлу по его file_id.

**Пример:**

```php
$file_path = $bot->getFilePath($file_id);
```

## Лицензия

SimpleTG распространяется по лицензии MIT. См. LICENSE файл для подробностей.
