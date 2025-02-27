<?php

namespace Cst\WALaravel\lib;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Throwable;

class Development
{
    private $connection;
    public $message;
    public $title;
    public $header;
    public $stacktrace;
    public $formatHeader;
    /**
     * Summary of __construct
     * @param \Cst\WALaravel\lib\Connection&\Mockery\LegacyMockInterface&\Mockery\MockInterface $connection
     * @throws \Exception
     */
    public function __construct($connection)
    {
        $connection = $connection->setDriver(config("wa.dev_driver"));
        if (!empty(config("wa.auth_dev_token"))) {
            $connection = $connection->setAuthToken(config("wa.auth_dev_token"));
        }
        if (!empty(config("wa.app_dev_token"))) {
            $connection = $connection->setAppToken(config("wa.app_dev_token"));
        }
        if (!empty(config("wa.dev_url"))) {
            $connection = $connection->setServerUrl(config("wa.dev_url"));
        }
        $this->connection = $connection;

        if (empty(config("wa.dev_numbers"))) {
            throw new Exception("Dev Number required");
        }
    }
    public function report( $message=null)
    {
        if(empty($this->header)){
            $this->header = $this->formSourceHeader();
        }
        $this->message = $this->formMsg($message);
        $return = collect();
        foreach (explode(',', config("wa.dev_numbers")) as $value) {
            $return->push($this->connection->to($value)->msg(Str::limit($this->message,config("wa.string_limit",995))));
        }
        return $return;
    }
    public function send($message=null)
    {
        $return = collect();
        $this->message = $this->formMsg($message);
        foreach (explode(',', config("wa.dev_numbers")) as $value) {
            $return->push($this->connection->to($value)->msg(Str::limit($message??$this->message,config("wa.string_limit",995))));
        }
        return $return;
    }
    public function sendPlain($message=null)
    {
        $return = collect();
        foreach (explode(',', config("wa.dev_numbers")) as $value) {
            $return->push($this->connection->to($value)->msg(Str::limit($message??$this->message,config("wa.string_limit",995))));
        }
        return $return;
    }
    public function sendTo(string $to, $message = "Test")
    {
        return  $this->connection->to($to)->msg(Str::limit($this->message ?? $message,config("wa.string_limit",995)));
    }
    public function sendToGroup()
    {
        // content
    }
    /**
     * add header
     * @return $this
     */
    public function addHeader($header=null)
    {
        $this->header = ($header??$this->formSourceHeader());
        return $this;
    }
    public function addCompactHeader()
    {
        $this->header = $this->formCompactSourceHeader();
        return $this;
    }
    /**
     * generaye source at header
     * @return string
     */
    public function formSourceHeader()
    {
            $appName = config('app.name');
            $url = config('app.url');
            $appEnv = config('app.env');
            $appDebug = config('app.debug');
            return "----------------------\nApp Name  : $appName\nUrl        : $url\nEnv        : $appEnv\nDebug    : $appDebug\n----------------------";
    }
    /**
     * generaye source at header
     * @return string
     */
    public function formCompactSourceHeader()
    {
            $appName = config('app.name');
            $url = config('app.url');
            $appEnv = config('app.env');
            $appDebug = config('app.debug');
            return "|{$url}|\n-|{$appName}|Env:{$appEnv}|Debug:{$appDebug}|-";
    }
    public function formMsg($message,$title = '🚨❌☠️   Error    ☠️❌🚨', $stacktrace = null)
    {
        $title = FormatMsg::bold($this->title ?? $title);
        $stacktrace = $stacktrace ?? $this->stacktrace;
        if($message instanceof Throwable){
            $stacktrace = FormatMsg::bold($message->getTraceAsString());
            $message = $message->getMessage();
        }
        return "{$title} :\n{$this->header}\n{$message}\n{$stacktrace}";
    }
    /**
     * Send a test report to dev
     * @return \Illuminate\Http\Client\Response
     */
    public function testReport()
    {
        return $this->report("Test Exception Report");
    }
    public function test() {
        $return = collect();
        foreach (explode( ',', config("wa.dev_numbers")) as $value) {
            $value = $this->connection->formatPhone($value);
            $return->push($this->connection->to($value)->msg(Str::limit(config("wa.test_message")." To Dev", config("wa.string_limit", 995))));
        }
        return $return;
    }
    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    /**
     * Set the title to info
     *
     * @return  self
     */
    public function info()
    {
        $this->title = config("dev_info_message_title", "ℹ️ Info ℹ️");
        return $this;

    }
    /**
     * Set the title to info
     *
     * @return  self
     */
    public function error()
    {
        $this->title = config("dev_error_message_title", "🚨❌☠️   Error    ☠️❌🚨");
        return $this;

    }
    /**
     * Set the title to warning
     *
     * @return  self
     */
    public function warning()
    {
        $this->title = config("dev_warning_message_title", "⚠️ Warning ⚠️");
        return $this;

    }

    /**
     * Set the value of stacktrace
     *
     * @return  self
     */
    public function stacktrace($stacktrace)
    {
        $this->stacktrace = $stacktrace;
        return $this;
    }
}
