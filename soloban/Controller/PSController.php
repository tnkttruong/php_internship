<?php
/* ###############################################################################
 *
 * システム名
 *   ラジカルオプティ サロン事業 POSシステム
 *
 * 機能
 *   CahePHP POSベースコントローラ
 *
 * バージョン
 *   0.0.0_0
 *
 * 履歴
 *   2016/02/08 K.Sonohara
 *     新規作成
 *
 * ###############################################################################
 */

App::uses('SLController', 'Controller');
App::uses('ApiTokenAuthenticate', 'Controller/Component/Auth');

// ###############################################################################
//  変数・定義値
// ###############################################################################

// ###############################################################################
//  クラス
// ###############################################################################

/**
 * POSベースコントローラ.
 */
class PSController extends SLController {

    protected static $_user = [];

    protected static $sessionKey = 'Auth.User';

    /**
     * コンポーネントフィールド変数.
     */
    public $components = [
        'Security' => [
            'csrfCheck' => false
        ],
        'RequestHandler',
        'Auth',
        'Transaction',
        'Deposit'
    ];

    /**
     * ヘルパーフィールド変数.
     */
    public $helpers = ['Cache'];
    /**
     * 事前処理.
     */
    public function beforeFilter() {
        // 継承元処理
        parent::beforeFilter();

        $this->layout = $this->autoRender = false;

        AuthComponent::$sessionKey = false;

        $this->Auth->authenticate = [
            'ApiToken' => [
                'userModel' => 'User',
                'userFields' => null,
                'passwordHasher' => 'Simple',
                'fields' => [
                    'username' => 'user_id',
                    'password' => 'password'
                ],
                'scope' => [
                    'del_flg' => 0
                ]
            ]
        ];

        $this->Auth->loginAction = [
            'controller' => 'user',
            'action' => 'login'
        ];

        // セキュリティ設定
        $this->Security->validatePost = false;
        $this->Security->csrfUseOnce = false;
        $this->Security->unlockedActions = 'index';
        $this->Security->requireSecure();

        /*
        // 認証エラー対応 @TODO 認証済みの場合のみ許可
        if ($this->request->is('options')) {
            $this->Auth->allow();
            $this->action = 'options';
            return;
        }

        // 処理内容調整
        try {
            $a = $this->initAction();
            if ($a) {
                $this->action = $a;
            }
        } catch (Exceptin $e) {
            $this->log($e, LOG_ERR);

            $this->action = 'block';
        }
        */
    }

    /**
     * オーナーID取得.
     *
     * @return int
     */
    public function getOwnerID() {
        $data['User']['user_id'] = env('PHP_AUTH_USER');
        $data['User']['password'] = env('PHP_AUTH_PW');
        $ret = $this->User->login($data);
        return $ret['owner_id'];
    }

    /**
     * デバイスID取得.
     *
     * @return string 取得した値
     */
    public function getDeviceID() {
        return $this->request->header('DeviceUUID');
    }

    /**
     * ユーザーID取得.
     *
     * @return int 取得した値
     */
    public function getUserID() {
        return $this->Auth->user('id');
    }

    /**
     * ショップID取得.
     *
     * @param int $userId
     *
     * @return int 取得した値
     */
    public function getShopID($userId) {
        $this->loadModel('User');
        $shopId = $this->User->queryGetShopIdByUserId($userId);

        if (empty($shopId)) {
            return $this->_falseJson(ApiResponseCode::BAD_REQUEST, null, __('Shop ID not found. You can not access!'));
        }

        return $shopId;
    }

    /**
     * 適応アクション処理.
     *
     * @return string 処理メソッド名
     */
    public function initAction() {
        return 'block';
    }

    /**
     * ブロック処理.
     */
    public function block() {

    }

    /**
     * インデックス処理
     * 本処理は規定エラー処理が含まれおり実体はonIndexへ記載すること.
     */
    public function index() {
        try {
            $this->onIndex();
        } catch (Exception $e) {
            $this->log($e, LOG_ERR);
            $this->returnError(500);
        }
    }

    /**
     * Create access log of POS device
     *
     * @param array $params
     */
    protected function _startAccessLog($params) {
        $this->loadModel('PosAccessLog');
        $this->PosAccessLog->create();
        $this->PosAccessLog->save($params);
    }

    /**
     * Get validator object
     *
     * @param string $validator_class
     *
     * @return object
     */
    public function getValidator($validator_class = 'Validator') {
        $this->loadModel($validator_class);

        return $this->{$validator_class};
    }

    /**
     * Gen stock_money_code, payslip_code and inventory_code
     *
     * @param int $id
     *
     * @return string
     */
    public function genStockPayslipInventoryCode($id) {
        if (empty($id)) {
            return '';
        }

        $alphabets = Configure::read('GenCodeArray');;
        $firstYear = mb_substr(date("Y"), 2, 1);
        $lastYear = mb_substr(date("Y"), 3, 1);
        $minutes = date("i");
        $idStr = strtoupper(base_convert($id, 10, 36));

        return $alphabets[$firstYear].$alphabets[$lastYear].$idStr.$minutes;
    }

    /**
     * Get today value
     *
     * @return string
     */
    public function getToday() {
        return date('Y-m-d');
    }

    /**
     * Get yesterday value
     *
     * @return string
     */
    public function getYesterday() {
        return date('Y-m-d', strtotime(' -1 day'));
    }

    /**
     * Get today time value
     *
     * @return string
     */
    public function getTodayTime() {
        return date('Y-m-d H:i:s');
    }

    /**
     * Get yesterday time value
     *
     * @return string
     */
    public function getYesterdayTime() {
        return date('Y-m-d H:i:s', strtotime(' -1 day'));
    }

    /**
     * Generate customer identification number
     *
     * @param array $data
     * @internal param int $id
     *
     * @return null|string
     */
    public function genIdentificationNum($data = []) {
        if (empty($data)) {
            return null;
        }

        $cusZeroFillId = sprintf('%010d', $data['id']);
        $cusThenCode = '0';
        $baseNum = '0000'.$cusZeroFillId.$cusThenCode;
        $checkDigit = $this->__getCheckDigit($baseNum);

        return ($checkDigit === null) ? null : $baseNum.$checkDigit;
    }

    /**
     * Function check digits
     *
     * @param string $str
     *
     * @return int|null
     */
    private function __getCheckDigit($str) {
        $length = strlen($str);

        if ($length != 15) {
            return null;
        }

        $reverse = strrev($str);
        $sum = 0;

        for ($i = 0; $i < $length; $i++) {
            $sum += (($i % 2) == 0) ? (int) $reverse[$i] : (int) $reverse[$i] * 7;
        }

        $checkDigit = 10 - ($sum % 10);

        if ($checkDigit == 10) {
            $checkDigit = 0;
        }

        return $checkDigit;
    }

}

// ###############################################################################
//  関数
// ###############################################################################

// ###############################################################################
//  処理
// ###############################################################################

