<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    /**
     * beforeFilter function
     */
    public function beforeFilter() {

    }

    /**
     * Handle rendering error for api service
     * @param $error
     */
    public function appError($error) {

        $errorCode = ApiResponseCode::INTERNAL_SERVER_ERROR;
        $errorMessage = null;

        switch (true) {
            case $error instanceof MissingControllerException:
                $errorCode = ApiResponseCode::NOT_FOUND;
                break;

            case $error instanceof MissingActionException:
                $errorCode = ApiResponseCode::NOT_FOUND;
                break;

            case $error instanceof ForbiddenException:
                $errorCode = ApiResponseCode::FORBIDDEN;
                $errorMessage = $error->getMessage();
        }

        $this->_falseJson($errorCode, $errorMessage);
        $this->response->send();
    }

    /**
     * Build json response for api request
     *
     * @param bool $status
     * @param array $result
     * @param array $extra
     *
     * @return bool
     */
    public function _buildJson($status, $result = null, $extra = []) {
        $response = [
            'status' => ApiResponseCode::OK,
            'message' => 'OK',
            'success' => $status
        ];

        if (is_array($extra)) {
            $response = array_merge($response, $extra);
        }

        if (!is_null($result)) {
            $response['data'] = $result;
        }

        $this->layout = $this->autoRender = false;
        $this->response->statusCode($response['status']);
        $this->response->type('application/json');
        $this->response->body(json_encode($response));

        return true;
    }

    /**
     * Failure json response
     *
     * @param int $errorCode
     * @param string $errorMessage
     * @param array $extra
     *
     * @return bool
     */
    public function _falseJson($errorCode = null, $errorMessage = '', $extra = []) {

        $response = [
            'status' => $errorCode,
            'message' => empty($errorMessage) ? $this->getMessage($errorCode) : $errorMessage
        ];

        if (!empty($extra)) {
            $response['errors'] = $extra;
        }

        return $this->_buildJson(false, null, $response);
    }

    /**
     * True json response
     *
     * @param array $data
     * @param array $extra
     *
     * @return bool
     */
    public function _trueJson($data = [], $extra = null) {
        return $this->_buildJson(true, $data, $extra);
    }

    /**
     * 指定したエラータイプのメッセージを返す.
     *
     * @param int $code ステータスコード
     * @param string $type エラー種別
     *
     * @return string 取得結果
     */
    public function getMessage($code, $type = 'error')
    {
        if ($this->message === null) {
            $this->message = Configure::read('message');
        }

        try {
            return isset($this->message[$type][$code]) ? $this->message[$type][$code] : $type;
        } catch (Exception $e) {
            $this->log($e, LOG_ERR);
        }

        return $this->message['error'][500];
    }
}
