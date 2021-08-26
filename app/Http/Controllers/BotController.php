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
        $BOT_TOKEN  = '1954069333:AAGNbncqFfp5qEpNYml-7UcWIrayxUHK3kQ';

        $input = file_get_contents('php://input');

        $this->writeLog('input-log', $input);

        $data = json_decode($input);

        if (isset($data->message->chat->id) && isset($data->message->text)) {
            $chatId = $data->message->chat->id;
            $text = $data->message->text;
            if ($text == '/openticketsincategory') {
                $msg = 'Choose a category from bellow to see its information';

                $keyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => 'Technical', 'callback_data' => '/technical28'],
                            ['text' => 'Accounts', 'callback_data' => '/accounts29'],
                            ['text' => 'Password Reset', 'callback_data' => '/accounts29'],

                        ],

                        [

                            ['text' => 'Customer Profile', 'callback_data' => '/accounts29'],
                            ['text' => 'Negetive reveiew on service', 'callback_data' => '/accounts29'],

                        ],

                    ]
                ];
                $encodedKeyboard = json_encode($keyboard);

                $response = $this->httpClient->post('https://api.telegram.org/bot' . $BOT_TOKEN . '/sendMessage' . '?chat_id=' . $chatId . '&text=' .
                    $msg . '&reply_markup=' . $encodedKeyboard . '&disable_web_page_preview=', ['verify' => false]);
            }
        }


        if (isset($data->callback_query->data)) {

            $inKbText = $data->callback_query->data;
            $inKbChatId = $data->callback_query->message->chat->id;

            if ($inKbText == '/technical28' || $inKbText == '/accounts29' ) {

                $id = preg_replace('/[^0-9]/', '', $inKbText);
                $msg = $id;

                $response = $this->httpClient->post('https://api.telegram.org/bot' . $BOT_TOKEN . '/sendMessage' . '?chat_id=' . $inKbChatId . '&text=' .
                    $msg . '&reply_markup=' . '&disable_web_page_preview=', ['verify' => false]);
            }

        }
    }









    private  function writeLog($logName, $logData)
    {
        if (is_array($logData)) {
            // dd('An Array');
            $logData = json_encode($logData);
            // $stringToSign .= "{$key}\n{$message[$key]}\n";
        } else if (is_object($logData)) {
            $logData = json_encode($logData);
            // dd('An Object. To String:'.$string);
            // $stringToSign .= "{$key}\n{$string}\n";
        }
        file_put_contents('./log-' . $logName . date("j.n.Y") . '.log', $logData, FILE_APPEND);
    }
}
