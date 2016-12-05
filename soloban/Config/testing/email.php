<?php
/* ###############################################################################
 *
 * システム名
 *   ラジカルオプティ サロン事業 POSシステム
 *
 * 機能
 *   CahePHP メール設定 [個人・デフォルト環境]
 *
 * バージョン
 *   0.0.0_0
 *
 * 履歴
 *   2016/1/04 T.Deguchi
 *     新規作成
 *
 * ###############################################################################
 */

// ###############################################################################
//  変数・定義値
// ###############################################################################

return [
    'default' => [
        'transport' => 'Smtp',
        'from' => [
            'crony-test_nal@crony.jp' => 'POS(NAL-Testing)'
        ],
        'host' => 'mail.rdcl-opti.co.jp',
        'port' => 587,
        'timeout' => 30,
        'log' => false,
        'username' => 'crony-test_nal@crony.jp',
        'password' => 'mh3WjcDmN88Ew2JfzpkM'
    ],
    'notifier' => [
        'transport' => 'Smtp',
        'from' => [
            'crony-test_nal@crony.jp' => 'POS(NAL-Testing)'
        ],
        'host' => 'mail.rdcl-opti.co.jp',
        'port' => 587,
        'timeout' => 30,
        'log' => false,
        'username' => 'crony-test_nal@crony.jp',
        'password' => 'mh3WjcDmN88Ew2JfzpkM'
    ]
];
