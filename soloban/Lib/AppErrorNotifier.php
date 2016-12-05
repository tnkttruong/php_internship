<?php

App::uses('ErrorNotifierDriverEmail', 'Lib/ErrorNotifier');

/**
 * Class AppErrorNotifier
 */
class AppErrorNotifier {

    const MASKED_LABEL = "[MASKED]";

    /**
     * @var object
     */
    protected $driver = null;

    /**
     * @var bool
     */
    public $sendable = false;

    /**
     * @var null|string
     */
    public $project = null;

    /**
     * @var string default email template
     */
    public $defaultTemplate =<<<EOT
[Project]: %s

[Message]: %s

[URL]:
%s

[GET]:
%s

[POST]:
%s

[SERVER]:
%s

[BACKTRACE]:
%s

EOT;

    /**
     * マスクパラメータ一覧
     * @var array
     */
    protected $masked_parameters = [
        'token', 'password', 'password_confirmation', 'email', 'address', 'phone'
    ];

    /**
     * AppErrorNotifier constructor.
     * @param array $config
     */
    public function __construct($config = []) {

        $keys = ['sendable', 'project', 'masked_parameters'];

        // Set config
        foreach ($keys as $key) {
            if (isset($config[$key])) {
                $this->{$key} = $config[$key];
            }
        }

        switch ($config['driver']) {
            default:
                $this->driver = new ErrorNotifierDriverEmail($config['driver_config']);
                break;
        }
    }

    /**
     * 例外通知
     *
     * @access public
     * @param object $exception
     * @return bool
     */
    public function sendException($exception)
    {
        $subject = get_class($exception) . ": " . $exception->getMessage();

        $message = sprintf("%s at %s:%d",
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );

        return $this->send($message, array(
            'subject' => $subject,
            'exception' => $exception
        ));
    }

    /**
     * 通知
     *
     * @access public
     * @param string $message
     * @param array $options
     * @return bool
     */
    public function send($message, $options = [])
    {
        if ( ! array_key_exists('subject', $options)) {
            $options['subject'] = $message;
        }
        $options['subject'] = sprintf("%s:[%s] %s", $this->project, APP_ENV, $options['subject']);

        $bodyContent = $this->getBodyContent($message, $options);

        $result = $this->driver->send($options['subject'], $bodyContent);

        return $result;
    }

    /**
     * Build error body
     * @param $message
     * @param array $options
     * @return string
     */
    protected function getBodyContent($message, $options = []) {
        return vsprintf($this->defaultTemplate, [
            $this->project,
            $message,
            $this->getUri(),
            $this->parseArrayToString($this->getGET()),
            $this->parseArrayToString($this->getPOST()),
            $this->parseArrayToString($this->server()),
            isset($options['exception']) ? $options['exception']->getTraceAsString() : ''
        ]);
    }

    /**
     * Parse array to string
     * @param $args
     * @return string
     */
    protected function parseArrayToString($args) {

        $return = '';

        foreach ($args AS $key => $val) {
            $return .= $key . ': ' . $val . "\n";
        }

        return $return;
    }

    /**
     * Get url string
     * @return string
     */
    protected function getUri()
    {
        return $this->maskString(sprintf("%s://%s%s",
            empty($_SERVER['HTTPS']) ? "http" : "https",
            $_SERVER['SERVER_NAME'],
            $_SERVER['REQUEST_URI']
        ));
    }

    /**
     * @return array
     */
    protected function getGET() {
        return $this->arrayToBodyArguments($_GET);
    }

    /**
     * @return array
     */
    protected function getPOST() {
        return $this->arrayToBodyArguments($_POST);

    }

    /**
     * @return array
     */
    protected function server() {
        $server = [];
        foreach ($_SERVER as $key => $value) {
            if (preg_match("/^(HTTP_|REMOTE_|SERVER_|DOCUMENT_ROOT)/", $key)) {
                $server[$key] = $value;
            }
        }
        ksort($server);

        return $server;
    }

    /**
     * @param $string
     * @return mixed
     */
    protected function maskString($string)
    {
        foreach ($this->masked_parameters as $parameter) {
            $string = preg_replace("/$parameter=.*?(&|$)/", sprintf("%s=%s$1", $parameter, self::MASKED_LABEL), $string);
        }

        return $string;
    }

    /**
     * Mask field in array
     *
     * @param array $params
     * @return array
     */
    protected function arrayToBodyArguments($params)
    {
        $return = [];
        foreach ($params as $key => $value) {
            $return[$key] = in_array($key, $this->masked_parameters) ? self::MASKED_LABEL : $value;
        }

        return $return;
    }

}
