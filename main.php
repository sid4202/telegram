#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;


$bot_username = '@sumismadeby_bot';


try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    $mysql_credentials = [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'Cra5hLoy@ale',
        'database' => 'telegram',
    ];

    $telegram->enableMySql($mysql_credentials);

    $sql = mysqli_connect('localhost', 'root', 'Cra5hLoy@ale', 'telegram');
    $response = $telegram->handleGetUpdates();
    $result = $response->getResult();
    /** @var \Longman\TelegramBot\Entities\Update $update */
    foreach ($result as $update) {
        $message = $update->getMessage();
        $text = $message->getText();
        $chat_id = $update->getMessage()->getChat()->getId();
        $message_id = $update->getMessage()->getMessageId();
        $previous_message_id = $message_id - 2;
        $previous_message = mysqli_fetch_assoc($sql->query("select * from message where id=$previous_message_id and chat_id=$chat_id"));
        var_dump($previous_message);

        if ($text == "/start") {
            \Longman\TelegramBot\Request::sendMessage([
                "chat_id" => $chat_id,
                "text" => "Введите первое число"
            ]);
            continue;
        }
        if ($previous_message['text'] == "/start") {
            \Longman\TelegramBot\Request::sendMessage([
                "chat_id" => $chat_id,
                "text" => "Введите второе число"
            ]);
            continue;
        }
        if ($previous_message['text'] != "/start") {
            $x = intval($previous_message['text']);
            $y = intval($text);
            $sum = $x + $y;
            \Longman\TelegramBot\Request::sendMessage([
                "chat_id" => $chat_id,
                "text" => "Сумма:$sum"
            ]);
            continue;
        }
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}