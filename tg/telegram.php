<?php
class Telegram
{
    private $token = '';

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function request($method, $params = [])
    {
        $url = 'https://api.telegram.org/bot' . $this->token .  '/' . $method;
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $out = json_decode(curl_exec($curl), true)["result"]["message_id"];

        curl_close($curl);

        return $out;
    }

    public function reply($chat, $text)
    {
        return $this->request('sendMessage', [
            "parse_mode" => "markdown",
            "chat_id" => $chat,
            "text" => $text
        ]);
    }

    public function kbd($array, $inline = false, $resize = true)
    {
        $keyArray = [];

        foreach ($array as $row) {
            $keyRow = [];

            foreach ($row as $button) {
                if ($button[2] === true) {
                    $keyRow[] = [
                        'text' => $button[0],
                        'switch_inline_query_current_chat' => ''
                    ];
                } else {
                    $keyRow[] = [
                        'text' => $button[0],
                        'callback_data' => $button[1],
                    ];
                }
            }

            $keyArray[] = $keyRow;
        }

        $keyboard = [
            $inline ? 'inline_keyboard' : 'keyboard' => $keyArray,
            'resize_keyboard' => $resize,
            'one_time_keyboard' => !$inline,
        ];

        return $keyboard;
    }

    public function sendMsgEdit($chat, $msg_id, $text)
    {
        return $this->request('editMessageText', [
            'parse_mode' => 'markdown',
            'chat_id' => $chat,
            'message_id' => $msg_id,
            'text' => $text
        ]);
    }

    public function sendKbdEdit($chat, $msg_id, $kbd, $inline = true)
    {
        $kbd = is_null($kbd) ? $kbd : json_encode($this->kbd($kbd, $inline), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $this->request('editMessageReplyMarkup', [
            'parse_mode' => 'markdown',
            'chat_id' => $chat,
            'message_id' => $msg_id,
            'reply_markup' => $kbd
        ]);
    }

    public function sendEdit($chat, $msg_id, $text, $kbd = [], $inline = true)
    {
        $this->sendMsgEdit($chat, $msg_id, $text);
        if (isset($kbd)) $this->sendKbdEdit($chat, $msg_id, $kbd, $inline);
    }

    public function sendMessage($chat, $text, $kbd = null, $inline = true)
    {
        $kbd = is_null($kbd) ? $kbd : json_encode($this->kbd($kbd, $inline), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $this->request('sendMessage', [
            'parse_mode' => 'markdown',
            'chat_id' => $chat,
            'text' => $text,
            'reply_markup' => $kbd
        ]);
    }

    public function html($chat, $text)
    {
        return $this->request('sendMessage', [
            "parse_mode" => "HTML",
            "chat_id" => $chat,
            "text" => $text
        ]);
    }

    public function deleteMessage($chat, $msg_id)
    {
        return $this->request('deleteMessage', [
            "chat_id" => $chat,
            "message_id" => $msg_id
        ]);
    }
  
    function showAlert($id, $message, $show_alert = true) {
        return $this->request('answerCallbackQuery', [
            "callback_query_id" => $id,
            "text" => $message,
            "show_alert" => $show_alert
        ]);
    }

    public function answerInline($inline_query_id, $result)
    {
        return $this->request('answerInlineQuery', [
            "inline_query_id" => $inline_query_id,
            "results" => json_encode($result)
        ]);
    }

    function getPhoto($user_id)
    {
        $out = $this->request('getUserProfilePhotos', [
            "user_id" => $user_id
        ]);

        $file_id = $out['result']['photos']['0']['0']['file_id'];

        if (!$file_id) {
            return;
        }

        $file = 'https://api.telegram.org/file/bot' . $this->token . '/' . $this->getFilePath($file_id);
        return $file;
    }

    function getFilePath($file_id)
    {
        return stripslashes($this->request('getFile', [
            "file_id" => $file_id
        ])['result']['file_path']);
    }
}
