<?php

App::uses('CakeLogInterface', 'Log');
require dirname(__FILE__) . '/log4php/Logger.php';

class Log4PhpCustomLoggerLog implements CakeLogInterface {

    private $logger;

    public function __construct($options = array()) {

        //log4php設定ファイル読み込み
        if(isset($options['properties_path'])){
            Logger::configure($options['properties_path']);
        } else {
            $env = env('WEB_APP_ENV');
            switch ($env) {
                case 'production' :
                    // Production環境
                    Logger::configure(APP. 'Config' . '/log4php_production.properties');
                    break;
                case 'staging' :
                    // Staging環境
                    Logger::configure(APP. 'Config' . '/log4php_staging.properties');
                    break;
                case 'testing' :
                case 'demo' :
                    // Testing・Demo環境
                    Logger::configure(APP. 'Config' . '/log4php.properties');
                    break;
                default :
                    // ローカル開発環境
                    Logger::configure(APP. 'Config' . '/log4php.properties');
                    break;
            }
        }

        if(isset($options['name'])){
            $this->logger = Logger::getLogger($options['name']);
        } else{
            $this->logger = Logger::getLogger(get_class());
        }
    }

    public function write($type, $message) {
        LoggerNDC::clear();
        if (isset($_SERVER['REQUEST_URI']) && isset($_SERVER['SERVER_ADDR'])) {
            $reqUri = $_SERVER['REQUEST_URI'];
            $serverIp = $_SERVER['SERVER_ADDR'];
            $localeMsg = $serverIp.':'.$reqUri.'';
        } else {
            $localeMsg = ' BATCH ';
        }
        $trace = debug_backtrace();
        if (count($trace) >= 3) {
            $callClass = basename($trace[2]['file'],'.php');
            $callLine = $trace[2]['line'];
            $localeMsg = $localeMsg.'('.$callClass.':'.$callLine.')';
        }
        LoggerNDC::push($localeMsg);
        switch ($type) {

            case 'debug':
                $this->logger->debug($message);
            break;

            case 'notice':
                $this->logger->info($message);
            break;

            case 'info':
                $this->logger->info($message);
            break;

            case 'warning':
                $this->logger->warn($message);
            break;

            case 'error':
                $this->logger->error($message);
            break;

            default:
                $this->logger->error($message);
            break;
        }

    }
}
