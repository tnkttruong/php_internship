<?php

App::uses('CakeEmail', 'Network/Email');

/**
 * Class ErrorNotifierDriverEmail
 *
 * @author Duy Ton <duytt@nal.vn>
 */
class ErrorNotifierDriverEmail {

    var $emailObj = null;

    /**
     * ErrorNotifierDriverEmail constructor.
     * @param array $configs
     */
    public function __construct($configs = []) {

        $this->emailObj = new CakeEmail($configs['transport']);

        $this->emailObj->to($configs['to']);
    }

    /**
     * Send
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function send($subject = '', $message = '') {

        // Send email
        try {
            $this->emailObj->subject($subject)->send($message);
        } catch (Exception $e) {

        }

        return true;
    }

}