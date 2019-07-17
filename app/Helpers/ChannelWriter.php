<?php

namespace App\Helpers;

use Monolog\Logger;

use App\Helpers\ChannelStreamHandler;

class ChannelWriter
{
    /**
     * The Log channels.
     *
     * @var array
     */
    protected $channels = [
        'user' => [
            'path' => 'logs/user.log',
            'level' => Logger::INFO
        ],
        'admin' => [
            'path' => 'logs/admin.log',
            'level' => Logger::INFO
        ],
        'wallet' => [
            'path' => 'logs/wallet.log',
            'level' => Logger::INFO
        ],
        'trade' => [
            'path' => 'logs/trade.log',
            'level' => Logger::INFO
        ]
    ];

    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug'     => Logger::DEBUG,
        'info'      => Logger::INFO,
        'notice'    => Logger::NOTICE,
        'warning'   => Logger::WARNING,
        'error'     => Logger::ERROR,
        'critical'  => Logger::CRITICAL,
        'alert'     => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];

    public function __construct() {}

    /**
     * Write to log based on the given channel and log level set
     *
     * @param type $channel
     * @param type $message
     * @param array $context
     * @throws InvalidArgumentException
     */
    public function writeLog(string $channel, string $level, array $message, array $context = [])
    {
        if (!in_array($channel, array_keys($this->channels))) {
            throw new InvalidArgumentException('Invalid channel used.');
        }

        $logger = \App::make("{$channel}log");
        $channelHandler = new ChannelStreamHandler(
            $channel,
            storage_path() . '/' . $this->channels[$channel]['path'],
            $this->channels[$channel]['level']
        );
        $logger->pushHandler($channelHandler);
        $logger->{$level}($message);
    }

    public function write($channel, array $message, array $context = []){
        //get method name for the associated level
        $level = array_flip( $this->levels )[$this->channels[$channel]['level']];
        //write to log
        $this->writeLog($channel, $level, $message, $context);
    }

    //alert('event','Message');
    function __call($func, $params){
        if(in_array($func, array_keys($this->levels))){
            return $this->writeLog($params[0], $func, $params[1]);
        }
    }

}