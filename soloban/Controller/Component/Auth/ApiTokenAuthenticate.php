<?php
App::uses('BasicAuthenticate', 'Controller/Component/Auth');
App::import('Vendor', 'JWT', array('file' => 'firebase' . DS . 'php_jwt' . DS . 'JWT.php'));
/**
 * An api base authentication adapter for AuthComponent.  Provides the ability to authenticate using Token
 *
 * {{{
 *	$this->Auth->authenticate = array(
 *		'Authenticate.Token' => array(
 *			'fields' => array(
 *				'username' => 'username',
 *				'password' => 'password',
 *				'token' => 'public_key',
 *			),
 *			'parameter' => '_token',
 *			'header' => 'X-MyApiTokenHeader',
 *			'userModel' => 'User',
 *			'scope' => array('User.active' => 1)
 *		)
 *	)
 * }}}
 *
 */
class ApiTokenAuthenticate extends BasicAuthenticate {

    /**
     * Settings for this object.
     *
     * - `fields` The fields to use to identify a user by. Make sure `'token'` has been added to the array
     * - `parameter` The url parameter name of the token.
     * - `header` The token header value.
     * - `userModel` The model name of the User, defaults to User.
     * - `scope` Additional conditions to use when looking up and authenticating users,
     *    i.e. `array('User.is_active' => 1).`
     * - `recursive` The value of the recursive key passed to find(). Defaults to 0.
     * - `contain` Extra models to contain and store in session.
     *
     * @var array
     */
    public $settings = [
        'fields' => [
            'username' => 'username',
            'password' => 'password',
            'token' => 'token',
        ],
        'userFields' => null,
        'passwordHasher' => 'Simple',
        'parameter' => '_token',
        'header' => 'AuthorizationToken',
        'userModel' => 'User',
        'scope' => [],
        'recursive' => 0,
        'contain' => null
    ];

    /**
     * Constructor
     *
     * @param ComponentCollection $collection The Component collection used on this request.
     * @param array $settings Array of settings to use.
     * @throws CakeException
     */
    public function __construct(ComponentCollection $collection, $settings) {

        parent::__construct($collection, $settings);

        if (empty($this->settings['parameter']) && empty($this->settings['header'])) {
            throw new CakeException(__('You need to specify token parameter and/or header'));
        }
    }

    /**
     * Authenticate user
     *
     * @param CakeRequest $request The request object
     * @param CakeResponse $response response object.
     * @return mixed.  False on login failure.  An array of User data on success.
     */
    public function authenticate(CakeRequest $request, CakeResponse $response) {
        $user = $this->getUser($request);

        if (!$user) {
            $fields = $this->settings['fields'];
            return $this->_findUser(
                $request->data[$fields['username']],
                $request->data[$fields['password']]
            );
        }

        return $user;
    }

    /**
     * Unauthenticated function, call by AuthComponent
     * @param CakeRequest $request
     * @param CakeResponse $response
     * @return bool
     */
    public function unauthenticated(CakeRequest $request, CakeResponse $response) {
        $controller = $this->_Collection->getController();
        $url = '';

        if (isset($controller->request->url)) {
            $url = $controller->request->url;
        }

        $url = Router::normalize($url);
        $loginAction = Router::normalize($controller->Auth->loginAction);

        if ($url != $loginAction) {
            $controller->_falseJson(ApiResponseCode::UNAUTHORIZED);
            $controller->response->send();
            exit;
        }

        return false;
    }

    /**
     * @param CakeRequest $request
     * @return mixed
     */
    private function _getToken(CakeRequest $request)
    {
        if (!empty($this->settings['header'])) {
            $token = $request->header($this->settings['header']);
            if ($token) {

                if (strpos($token, 'Bearer ') === 0) {
                    return substr($token, 7);
                }
            }
        }

        if (!empty($this->settings['parameter']) && !empty($request->query[$this->settings['parameter']])) {
            return $request->query[$this->settings['parameter']];
        }

        return false;
    }

    /**
     * Get token information from the request.
     *
     * @param CakeRequest $request Request object.
     * @return mixed Either false or an array of user information
     */
    public function getUser(CakeRequest $request) {

        $token = $this->_getToken($request);
        if ($token) {

            try {
                $decode_token = \Firebase\JWT\JWT::decode($token, Configure::read('JWT.secret'), ['HS256']);
            } catch (Exception $e) {
                return false;
            }

            // Token is verify
            $decode_token = get_object_vars($decode_token);

            list(, $model) = pluginSplit($this->settings['userModel']);

            $user = $this->_findUser(array(
                $model . '.' . $this->settings['fields']['username'] => $decode_token['uid']
            ));

            if ($user) {
                unset($user[$this->settings['fields']['password']]);
                return $user;
            }
        }

        return false;
    }

    /**
     * Generate access token by user info
     *
     * @param array $user info
     *
     * @return string
     */
    public static function generate_token($user) {

        $payload = [
            'iat' => time(),
            'uid' => $user['user_id']
        ];

        if (Configure::read('JWT.expired_period')) {
            $payload['exp'] = strtotime(Configure::read('JWT.expired_period'));
        }

        return \Firebase\JWT\JWT::encode($payload, Configure::read('JWT.secret'));
    }

}
