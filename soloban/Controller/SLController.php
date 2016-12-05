<?php
/* ###############################################################################
 *
 * システム名
 *   ラジカルオプティ サロン事業 POSシステム
 *
 * 機能
 *   CakePHP 設定読みこみ
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

App::uses('AppController', 'Controller');

// ###############################################################################
//  変数・定義値
// ###############################################################################

// ###############################################################################
//  クラス
// ###############################################################################

class SLController extends AppController
{
    /**
     * モデルフィールド変数.
     */
    public $uses = array('User');

    /**
     * コンポーネントフィールド変数.
     */
    public $components = array(
        'Security',
        'Transaction',
        'Session',
        'Auth'
    );

    /**
     * ヘルパーフィールド変数.
     */
    public $helpers = array('Cache');

    /**
     * メッセージフィールド変数.
     */
    public $message;

    /**
     * 事前処理.
     */
    public function beforeFilter()
    {
        // 継承元処理
        parent::beforeFilter();

        // ログ
        $this->beforeLog();

        // ブラウザキャッシュ無効
        $this->response->disableCache();

        // セキュリティチェック
        $this->Security->blackHoleCallback = '_securityError';
    }

    /**
     * 事前ログ.
     */
    public function beforeLog()
    {
        $this->log($this->getQuery(), LOG_DEBUG);
    }

    /**
     * リクエスト取得.
     *
     * @return string 取得した値
     */
    public function &getQuery()
    {
        if ($this->request->is('post')) {
            return $this->request->data;
        }

        return $this->request->query;
    }

    /**
     * セキュリティエラーの場合の振る舞い。
     *
     * @param string $type
     */
    public function _securityError($type)
    {

        $this->log('security error:['.$type.']:'.$this->request->url, LOG_WARNING);

        switch ($type) {
            case 'secure':
                // No need to log secure when response for switching to ssl
                $this->_falseJson(ApiResponseCode::FORBIDDEN, __('SSL is required'));
                $this->response->send();
                $this->shutdownProcess();

                // Exit application
                exit;

                break;
        }
    }
}

// ###############################################################################
//  関数
// ###############################################################################

// ###############################################################################
//  処理
// ###############################################################################

