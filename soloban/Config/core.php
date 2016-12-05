<?php
/* ###############################################################################
 *
 * システム名
 *   ラジカルオプティ サロン事業 POSシステム
 *
 * 機能
 *   CahePHP コア設定
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

// 環境識別
define('WEB_APP_ENV', 'WEB_APP_ENV');
define('APP_ENV', env(WEB_APP_ENV) ? env(WEB_APP_ENV) : 'my');
define('ENV_PRODUCTION', 'production');
define('ENV_STAGING', 'staging');
define('ENV_TESTING', 'testing');
define('ENV_DEMO', 'demo');
define('ENV_DEVELOPMENT', 'development');

// ###############################################################################
//  クラス
// ###############################################################################

// ###############################################################################
//  関数
// ###############################################################################

// ###############################################################################
//  処理
// ###############################################################################

// デバッグ設定
if (APP_ENV === ENV_PRODUCTION) {
    Configure::write('debug', 0);
} else {
    Configure::write('debug', 2);
}

Configure::write('Error', [
    'handler' => 'AppErrorHandler::handleError',
    'level' => E_ALL & ~E_DEPRECATED,
    'trace' => true,
]);

Configure::write('Exception', [
    'handler' => 'AppErrorHandler::handleException',
    'renderer' => 'AppExceptionRenderer',
    'log' => true,
    'skipLog' => [
        'NotFoundException',
        'ForbiddenException',
        'MissingControllerException',
        'MissingActionException',
        'PrivateActionException'
    ]
]);

Configure::write('App.encoding', 'UTF-8');
Configure::write('Cache.disable', true);

include APP.'Config'.DS.APP_ENV.DS.'session.php';

Configure::write('Security.salt', '80ecb41cafe9faa994e4c72e2473e14449463527');
Configure::write('Security.cipherSeed', '506754581276592135290500412969');

date_default_timezone_set('Asia/Tokyo');
Configure::write('Config.timezone', 'Asia/Tokyo');
Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');

Configure::write('JWT', [
    'secret' => '4T878676FP$!tRR#tj**DA29ejYSn',
    'expired_period' => '+1 day'
]);

$engine = 'File';

$duration = '+999 days';
if (Configure::read('debug') > 0) {
    $duration = '+10 seconds';
}
