<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as HttpClient;


class BotController extends Controller
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClient(['defaults' => ['verify' => false]]);
    }

    public function index()
    {
        $BOT_TOKEN  ='1954069333:AAGNbncqFfp5qEpNYml-7UcWIrayxUHK3kQ';

        $input = file_get_contents('php://input');
        $data = json_decode($input);
        $chatId = $data->message->chat->id;
        $text = $data->message->text;

        if($text == '/start'){
            $msg = "Welcome @" . $data->message->chat->username ;

            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => 'forward me to groups', 'callback_data' => '/status']
                    ]
                ]
            ];
            $encodedKeyboard = json_encode($keyboard);
        }
        else{
            $msg = "Other TEXT HERE";
        }

        $response = $this->httpClient->post('https://api.telegram.org/bot' . $BOT_TOKEN . '/sendMessage' . '?chat_id=' . $chatId . '&text=' .
        $msg .'?reply_markup='. '&disable_web_page_preview='.$encodedKeyboard,['verify' => false]);

    }
}
