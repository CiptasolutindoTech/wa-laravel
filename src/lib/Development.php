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
    /**
     * Summary of __construct
     * @param \Cst\WALaravel\lib\Connection&\Mockery\LegacyMockInterface&\Mockery\MockInterface $connection
     * @throws \Exception
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
        if (empty(config("wa.dev_numbers"))) {
            throw new \Exception("Dev Number required");
        }
    }
    public function report( $message=null)
    {
        $this->header = $this->formSourceHeader();
        $this->message = $this->formMsg($message);
        $return = collect();
        foreach (explode(',', config("wa.dev_numbers")) as $value) {
            $return->push($this->connection->to($value)->msg(Str::limit($this->message,config("wa.string_limit",995)))->body());
        }
        return $return;
    }
    public function send($message=null)
    {
        $return = '';
        $this->message = $this->formMsg($message);
        foreach (explode(',', config("wa.dev_numbers")) as $value) {
            $return .= $this->connection->to($value)->msg(Str::limit($message??$this->message,config("wa.string_limit",995)));
        }
        return $return;
    }
    public function sendPlain($message=null)
    {
        $return = '';
        foreach (explode(',', config("wa.dev_numbers")) as $value) {
            $return .= $this->connection->to($value)->msg(Str::limit($message??$this->message,config("wa.string_limit",995)));
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
    public function formMsg($message,$title = '🚨❌☠️   Error    ☠️❌🚨', $stacktrace = null)
    {
        $title = FormatMsg::bold($this->title ?? $title);
        $stacktrace = $stacktrace ?? $this->stacktrace;
        if($message instanceof Throwable){
            $stacktrace = FormatMsg::bold($message->getTraceAsString());
            $message = $message->getMessage();
        }
        return "$title :\n$this->header\n$message\n$stacktrace";
    }
    public function test()
    {
        return $this->report("Test Exception Report");
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
        return tap($this, function ($instance) {
            $instance->title = config("dev_info_message_title", "ℹ️ Info ℹ️");
        });
    }
    /**
     * Set the title to info
     *
     * @return  self
     */
    public function error()
    {
        return tap($this, function ($instance) {
            $instance->title = config("dev_error_message_title", "🚨❌☠️   Error    ☠️❌🚨");
        });
    }
    /**
     * Set the title to warning
     *
     * @return  self
     */
    public function warning()
    {
        return tap($this, function ($instance) {
            $instance->title = config("dev_warning_message_title", "⚠️ Warning ⚠️");
        });
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
