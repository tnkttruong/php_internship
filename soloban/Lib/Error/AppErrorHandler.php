<?php

App::uses('ErrorHandler', 'Error');
App::uses('AppErrorNotifier', 'Lib');

/**
 * Class AppErrorHandler
 */
class AppErrorHandler extends ErrorHandler {

    /**
     * @param Exception|ParseError $exception
     */
    public static function handleException($exception) {

        parent::handleException($exception);

        // Send notifier
        // Config notifier file
        $configFile = APP.'Config'.DS.APP_ENV.DS.'error_notifier.php';
        if (!file_exists($configFile)) {
            return;
        }

        $config = include $configFile;
        $errorNotifier = new AppErrorNotifier($config);
        $errorNotifier->sendException($exception);
    }

    /**
     * @param int $code
     * @param string $description
     * @param null $file
     * @param null $line
     * @param null $context
     * @return bool
     */
    public static function handleError($code, $description, $file = null, $line = null, $context = null) {
        list(, $level) = ErrorHandler::mapErrorCode($code);

        if (APP_ENV === ENV_PRODUCTION && $level === LOG_ERR) {
            // 致命的なエラー
            return ErrorHandler::handleFatalError($code, $description, $file, $line);
        }

        return ErrorHandler::handleError($code, $description, $file, $line, $context);
    }

}
