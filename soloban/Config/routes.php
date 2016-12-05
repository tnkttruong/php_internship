<?php
/* ###############################################################################
 *
 * システム名
 *   ラジカルオプティ サロン事業 POSシステム
 *
 * 機能
 *   CahePHP ルーティング設定
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

// ###############################################################################
//  変数・定義値
// ###############################################################################

// ###############################################################################
//  クラス
// ###############################################################################

// ###############################################################################
//  関数
// ###############################################################################

// ###############################################################################
//  処理
// ###############################################################################

// JSONマッピング
Router::mapResources([
    'top',
    'version',
]);

// トップページは空のJSONを返す(TODOサービスページへリダイレクト)
Router::connect(
    '/', ['controller' => 'top', 'action' => 'index']
);

// API-AUTH-01 User login
Router::connect(
    '/login', ['controller' => 'User', 'action' => 'login', '[method]' => 'POST']
);

// API-AUTH-02 User logout
Router::connect(
    '/logout', ['controller' => 'User', 'action' => 'logout']
);

// API-CATEGORY-01 List category
Router::connect(
    '/categories', ['controller' => 'categories', 'action' => 'getList', '[method]' => 'GET']
);

// API-CATEGORY-02 Create temporary menu
Router::connect(
    '/categories/create_tmp_menu', ['controller' => 'categories', 'action' => 'createTmpMenu', '[method]' => 'POST']
);

// API-ITEM-01 list product
Router::connect(
    '/product_list', ['controller' => 'items', 'action' => 'getList', '[method]' => 'GET'  ]
);

// API-ITEM-02  product detail
Router::connect(
    '/product_detail', ['controller' => 'items', 'action' => 'getDetail', '[method]' => 'GET']
);

// API-STAFF-01 Password authentication
Router::connect(
    '/staffs/password', ['controller' => 'staffs', 'action' => 'passwordAuthentication', '[method]' => 'POST']
);

// API-STAFF-02 List staffs working on the day
Router::connect(
    '/staffs', ['controller' => 'staffs', 'action' => 'getList', '[method]' => 'GET']
);

// API-STAFF-02 List staffs working on the day
Router::connect(
    '/deposits/create', ['controller' => 'deposits', 'action' => 'create', '[method]' => 'POST']
);

// API-DEPOSIT-05 Delete deposit/withdraw
Router::connect(
    '/deposits/delete', ['controller' => 'deposits', 'action' => 'delete', '[method]' => 'DELETE']
);

// API-PAYMENT-01 Create Payment
Router::connect(
    '/payments', ['controller' => 'payments', 'action' => 'create', '[method]' => 'POST']
);

// ルーティングデフォルト
CakePlugin::routes();
require CAKE.'Config'.DS.'routes.php';
