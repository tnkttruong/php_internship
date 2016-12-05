<?php
/* ###############################################################################
 *
 * システム名
 *   ラジカルオプティ サロン事業 POSシステム
 *
 * 機能
 *   CahePHP データベース設定 [テスト環境]
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

/**
 * rdb: モデル及びセッション及び用データベース設定
 * s3: AWS S3設定.
 */
return array(
    'rdb' => array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'salon_app',
        'password' => 'salon_app',
        'database' => 'salon_app_testing',
        'prefix' => '',
        'encoding' => 'utf8',
    ),
    's3' => array(
        'datasource' => 'S3'
        , 'useS3' => true
        , 'bucket_name' => 'naljapan-soloban'
        , 'key' => 'AKIAIZPDRLLAO7NWWQWQ'
        , 'secret' => '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'
        , 'default_cache_config' => ''
        , 'certificate_authority' => false
    )
);
