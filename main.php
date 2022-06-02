#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';
require  __DIR__ . '/UserNumbers.php';

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

$bot_api_key  = '5492344744:AAG6s-i2dHaRfpA900m_Upv509h_qjqyci4';
$bot_username = '@sumismadeby_bot';


try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    $mysql_credentials = [
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => 'Cra5hLoy@ale',
        'database' => 'telegram',
    ];

    $telegram->enableMySql($mysql_credentials);

    // Handle telegram getUpdates request
    $response = $telegram->handleGetUpdates();
    $result = $response->getResult();
    /** @var \Longman\TelegramBot\Entities\Update  $update */
    foreach($result as $update) {
        $message = $update->getMessage();
        $text = $message->getText();

        $database = new UserNumbers();
        $userNumbers = $database->find($update->getMessage()->getChat()->getId());

        $userNumbersData = $database->find($update->getMessage()->getChat()->getId(), true);
        var_dump($userNumbersData);
        echo $update->getMessage()->getMessageId();
        if ($text == "/start") {
            if ($userNumbers === null){
                $database->create($update->getMessage()->getChat()->getId());
            }

            \Longman\TelegramBot\Request::sendMessage([
                "chat_id" => $update->getMessage()->getChat()->getId(),
                "text" => "Введите первое число"
            ]);
            continue;
        }
        if ($userNumbersData["first_number"] === null) {
            $userNumbers->update("first_number", intval($text));
            \Longman\TelegramBot\Request::sendMessage([
                "chat_id" => $update->getMessage()->getChat()->getId(),
                "text" => "Введите второе число"
            ]);
            continue;
        } else {
            if ($userNumbersData["sum"] === null) {
                $sum = $userNumbersData["first_number"] + intval($text);
                $userNumbers->update("sum", $sum);
                \Longman\TelegramBot\Request::sendMessage([
                    "chat_id" => $update->getMessage()->getChat()->getId(),
                    "text" => "Сумма равна:$sum"

                ]);
            }
            if (!$userNumbersData["sum"] === null and !$userNumbersData["first_number"] === null) {
                $userNumbers->delete();
            }
        }
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
        echo  $e->getMessage();
}