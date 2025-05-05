<?php

namespace Cst\WALaravel\lib;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Http;

class Connection
{
    protected $driver;
    protected $authToken;
    protected $appToken;
    protected $serverUrl;
    public $to;
    protected $connection;
    function __construct($to = null)
    {
        $this->to = $to;
    }
    public function url()
    {
        if (empty(($this->serverUrl ?? config("wa.{$this->connection}.api_url",config("wa.api_url"))))) {
            throw new \Exception('Whatsapp api url cant be empty');
        }
        return $this->serverUrl ?? config("wa.{$this->connection}.api_url",config("wa.api_url"));
    }
    protected function sendUrl()
    {
        if ($this->driver === 'cipta') {
            return $this->url() . '/create-message';
        } else if ($this->driver === 'ruangWa') {
            return $this->url() . '/send_message';
        } else {
            throw new \Exception('Invalid Whatsapp Driver');
        }
    }
    protected function qrUrl()
    {
        if ($this->driver === 'cipta') {
            return $this->url() . '/qr';
        } else if ($this->driver === 'ruangWa') {
            return $this->url() . '/send_message';
        } else {
            throw new \Exception('Invalid Whatsapp Driver');
        }
    }
    public function appToken()
    {
        switch ($this->driver) {
            case 'cipta':
            $configName = 'app_token';
            break;
            case 'ruangWa':
            $configName = 'ruang_wa_token';
            break;
            default:
            $configName = 'app_token';
            break;
        }
        $token = config("wa.{$this->connection}.{$configName}",config("wa.{$configName}"));
        if ($this->driver === 'ruangWa' && empty($token)) {
            $token = config("wa.{$this->connection}.app_token",config("wa.{$configName}"));
        }
        if (empty(($this->appToken ?? $token))) {
            throw new \Exception("Whatsapp app token can't be empty");
        }
        return $this->appToken ?? $token;
    }
    public function authToken()
    {
        if (empty(($this->authToken ?? config("wa.connections.{$this->connection}.auth_token",config("wa.auth_token"))))) {
            throw new \Exception("Whatsapp auth token can't be empty");
        }
        return $this->authToken ?? config("wa.connections.{$this->connection}.auth_token",config("wa.auth_token"));
    }
    protected function body($message, $to = null)
    {
        if ($this->driver === 'cipta') {
            return $this->auth()->merge(['message' => $message, 'to' => $to ?? $this->formatPhone($this->to)])->toArray();
        } else if ($this->driver === 'ruangWa') {
            return $this->auth()->merge(['message' => $message, 'number' => $to ?? $this->formatPhone($this->to)])->toArray();
        } else {
            throw new \Exception('Invalid Whatsapp Driver');
        }
    }
    private function auth()
    {
        if ($this->driver === 'cipta') {
            return collect(['appkey' => $this->appToken(), 'authkey' => $this->authToken()]);
        } else if ($this->driver === 'ruangWa') {
            return collect(['token' => $this->appToken()]);
        } else {
            throw new \Exception('Invalid Whatsapp Driver');
        }
    }
    protected function post($message)
    {
        /**
         * Sends a message to the specified recipients using the configured WhatsApp driver.
         *
         * @throws \Exception if the WhatsApp driver is invalid.
         *
         * @return \Illuminate\Support\Collection A collection of responses from the HTTP requests.
         *
         * The method performs the following steps:
         * 1. Checks if the driver is valid (either 'cipta' or 'ruangWa'). If not, throws an exception.
         * 2. Initializes the send URL and an empty collection for formatted messages.
         * 3. Formats the messages based on the type of the recipient(s) and message(s):
         *    - If `$this->to` is an array, formats each recipient's message.
         *    - If `$message` is an array, formats each message for the corresponding recipient.
         *    - Otherwise, formats the single message.
         * 4. Sends the formatted messages using the appropriate HTTP method based on the driver:
         *    - For 'cipta', sends a POST request.
         *    - For 'ruangWa', sends a POST request with form data.
         * 5. Collects and returns the responses from the HTTP requests.
         */
        if (!in_array($this->driver, ['cipta', 'ruangWa'])) {
            throw new \Exception('Invalid Whatsapp Driver');
        }


        $sendUrl = $this->sendUrl();
        $formattedMessages = collect();
        if (empty($this->to)) {
            $this->to = $this->testNumbers();
        }
        // Check if the recipient is an array of phone numbers
        if (is_array($this->to)) {
            foreach ($this->to as $value) {
            // Format each phone number and prepare the message body
            $formattedMessages->push($this->body($message, $this->formatPhone($value)));
            }
        }
        // Check if the message is an array of messages
        elseif (is_array($message)) {
            foreach ($message as $to => $msg) {
            if (is_array($msg)) {
                // If format is [['to' => 'phone', 'msg' => 'message'], ...]
                foreach ($msg as $data) {
                    $formattedMessages->push($this->body($data['msg'], $this->formatPhone($data['to'])));
                }
            } else {
                //  if format is ['phone' => 'message', ...]
                $formattedMessages->push($this->body($msg, $this->formatPhone($to)));
            }
            }
        }
        // If the recipient and message are not arrays, prepare a single message body
        else {
            $formattedMessages->push($this->body($message));
        }

        $responses = collect();
        foreach ($formattedMessages as $msg) {
            if($this->driver === 'cipta'){
                $responses->push(HTTP::withHeaders(['Accept' => 'application/json'])->post($sendUrl, $msg)->body());
            }elseif($this->driver === 'ruangWa'){
                $responses->push(HTTP::withHeaders(['Accept' => 'application/json'])->asForm()->post($sendUrl, $msg)->body());
            }
        }
        return $responses->count() == 1 ? $responses->first() : $responses;
    }
    public function qr()
    {
        if ($this->driver === 'cipta') {
            return HTTP::withToken($this->authToken())->post($this->qrUrl(), ['id' => $this->appToken()]);
        } else if ($this->driver === 'ruangWa') {
            return false;
        } else {
            throw new \Exception('Invalid Whatsapp Driver');
        }
    }
    /**
     * Receiver Number
     * @param mixed $phone
     */
    public function to($phone)
    {
        $this->to = $phone;
        return $this;
    }
    public function formatPhone($phones = null)
    {
        if (empty($phones)) {
            throw new \Exception("Phone Number can't be empty");
        }
        $phones = str_replace(['-', ' ', '/'], '', $phones);
        if (Str::is('+*', $phones)) {
            $phones = str_replace('+', '', $phones);
        }
        if (Str::is('08*', $phones)) {
            $phones = Str::replaceFirst('0', '62', $phones);
        }
        if (!Str::is('62*', $phones) || !is_numeric($phones) || strlen($phones) < 10) {
            throw new \Exception("Phone Number Invalid: {$phones}");
        }
        return $phones;
    }

    /**
     * Get the value of driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set the value of driver
     *
     * @return  self
     */
    public function setDriver($driver)
    {
        return tap($this, function () use ($driver) {
            $this->driver = trim($driver);
        });
    }
    /**
     * Send mesage
     *
     * @param string $message
     * @return \Illuminate\Http\Client\Response
     */
    public function msg($message)
    {
        return $this->send($message);
    }
    /**
     * Send mesage
     *
     * @param string $message
     * @return \Illuminate\Http\Client\Response
     */
    public function send($message)
    {
        return $this->post($message);
    }
    /**
     * Send test mesage
     *
     * @param string $message
     * @return \Illuminate\Http\Client\Response|\Illuminate\Support\Collection
     */
    public function test()
    {
        if (empty($this->to)) {
            $return = collect();
            foreach ($this->testNumbers() as $value) {
                $value = $this->formatPhone($value);
                $return->push($this->to($value)->msg(Str::limit(config("wa.{$this->connection}.test_message",config("wa.test_message")), config("wa.{$this->connection}.string_limit",config("wa.string_limit", 995)))));
            }
            return $return;
        }
        return $this->msg(config("wa.{$this->connection}.test_message",config("wa.test_message")));
    }
    public function inspire()
    {
        if (empty($this->to)) {
            $this->to = $this->testNumbers();
        }
        return $this->msg(Inspiring::quote());
    }
    public function dev()
    {
        return new Development($this);
    }
    public function toDev()
    {
        return $this->dev();
    }

    /**
     * Set the value of authToken
     *
     * @return  self
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * Set the value of appToken
     *
     * @return  self
     */
    public function setAppToken($appToken)
    {
        $this->appToken = $appToken;

        return $this;
    }

    /**
     * Set the value of serverUrl
     *
     * @return  self
     */
    public function setServerUrl($serverUrl)
    {
        $this->serverUrl = $serverUrl;

        return $this;
    }

    /**
     * Get the value of connection data
     */
    public function getConnectionConfig()
    {
        return config("wa.{$this->connection}");
    }
    /**
     * Get the value of connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set the value of connection
     *
     * @return  self
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }
    /**
     * Set the value of connection
     *
     * @return  self
     */
    public function connect($connection)
    {
        $this->connection = $connection;

        return $this;
    }
    public function toArray($string,$separator = ',') {
        return explode($separator, string: $string);
    }
    /**
     * Get Test Number
     * @return array
     */
    public function testNumbers() {
        return $this->toArray(config("wa.{$this->connection}.test_numbers",config("wa.test_numbers")));
    }
    /**
     * Set conection config
     * @param array|string $config appKey|['appKey','authKey','url']
     * @param mixed $authKey
     * @param mixed $url
     * @return $this;
     */
    public function config($config=null,$authKey=null,$url=null){
        $appKey = $config;
        if(is_array($config)){
            $appKey = $config[0];
            $authKey = $config[1];
            $url = $config[2];
        }
        $this->appToken = $appKey;
        $this->authToken = $authKey;
        $this->serverUrl = $url;

        return $this;
    }
}
