<?php

App::uses('PSController', 'Controller');

/**
 * Class UserController
 *
 * User Controller implement actions related with user
 * same as login, logout, change password, ....
 *
 * @property User User
 *
 * @since v1.0
 */
class UserController extends PSController {

    public $uses = ['User'];

    /**
     * Before filter
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(['login']);
    }

    /**
     * API-AUTH-01
     * User Login API
     * User login into system
     *
     * @internal param array $data
     * @internal param string $user_id
     * @internal param string $password
     *
     * @return bool
     */
    public function login() {

        $validator = $this->getValidator();
        $validator->set($this->request->data);

        $validator->validate = [
            'user_id' => [
                'rule' => 'notBlank',
                'message' => __('ユーザーIDが必須です。'),
                'required' => true
            ],
            'password' => [
                'rule' => 'notBlank',
                'message' => __('パスワードが必須です。'),
                'required' => true
            ]
        ];

        if (!$validator->validates()) {
            return $this->_falseJson(ApiResponseCode::BAD_REQUEST, null, $validator->validationErrors);
        }

        if ($this->request->is('post')) {
            $user = $this->Auth->identify($this->request, $this->response);

            // Login success
            if (!empty($user)) {
                // Create access token
                $access_token = ApiTokenAuthenticate::generate_token($user);

                // Save access log
                $this->__saveAccessLog(ApiResponseCode::OK, $access_token);

                // Return response
                return $this->_trueJson([
                    'id' => $user['id'],
                    'user_id' => $user['user_id'],
                    'name' => $user['user_name'],
                    'email' => $user['mail_addr'],
                    'access_token' => $access_token
                ]);
            }
        }

        // Response authenticate failure
        $this->__saveAccessLog(ApiResponseCode::UNAUTHORIZED);
        return $this->_falseJson(ApiResponseCode::UNAUTHORIZED, __('ユーザーIDまたはパスワードが正しくありません。'));
    }

    /**
     * User logout
     * @return string
     */
    public function logout() {
        //return $this->authLogout();
    }

    /**
     * Refresh token for keeping token alive
     *
     * @return bool
     */
    public function refresh_token() {

        $user =  $this->Auth->user();

        return $this->_trueJson([
            'access_token' => ApiTokenAuthenticate::generate_token($user)
        ]);
    }

    /**
     * Save access log
     *
     * @param int $status
     * @param string $access_token
     */
    private function __saveAccessLog($status, $access_token = '') {
        parent::_startAccessLog([
            'start_datetime' => date('Y-m-d H:i:s'),
            'try_login_id' => isset($this->request->data['user_id']) ? $this->request->data['user_id'] : null,
            'success_flg' => $status == ApiResponseCode::OK ? 1 : 0,
            'session_id' => $access_token,
            'client_ip' => $this->request->clientIp(),
            'server_ip' => env('SERVER_ADDR'),
            'agent' => env('HTTP_USER_AGENT'),
            'method' => $this->request->method(),
            'url' => $this->request->url,
            'referer' => $this->referer(),
            'http_status' => $status
        ]);
    }

}
