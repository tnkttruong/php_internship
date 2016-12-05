<?php

define('PS_VERSION', 1);

define('JSON_ROOT', WWW_ROOT.'json/');

define('POS_BITMASK_POSITION', 13);

$config['AppSecurity']['crypt_key'] = '6040c2b04ab566275af974895d76003d71a2b821';

define('MENU_TYPE_SERVICES', 'services');
define('MENU_TYPE_GOODS', 'goods');
define('MENU_TYPE_OTHERS', 'others');

define('STAFF_TYPE_GENERAL', 'A');
define('STAFF_TYPE_MANAGER', 'Z');

define('PAYSLIP_CONDITION_TYPE', 'Z');

define('SETTLEMENT_TYPE_A', 'A'); // 開店（営業開始前）
define('SETTLEMENT_TYPE_B', 'B'); // 中間（営業開始〜営業終了）
define('SETTLEMENT_TYPE_C', 'C'); // 精算（営業終了後）

define('SETTLEMENT_CD_1', '1'); // 入金
define('SETTLEMENT_CD_2', '2'); // 過剰調整
define('SETTLEMENT_CD_3', '3'); // 出金
define('SETTLEMENT_CD_4', '4'); // 不足調整
define('SETTLEMENT_CD_5', '5'); // 点検
define('SETTLEMENT_CD_6', '6'); // 銀行預け入れ
define('SETTLEMENT_CD_7', '7'); // 釣銭準備

define('PAYMENT_TYPE_A', 'A'); // 現金
define('PAYMENT_TYPE_B', 'B'); // クレジット
define('PAYMENT_TYPE_C', 'C'); // その他

define('SETTLEMENT_CONDITION_TYPE_A', 'A'); // 精算作業中
define('SETTLEMENT_CONDITION_TYPE_Z', 'Z'); // 精算完了

define('SETTLEMENT_ROLE_TYPE_A', 'A'); // 店舗スタッフ
define('SETTLEMENT_ROLE_TYPE_Z', 'Z'); // システム

define('MENU_CD_A1', 'A1'); // サービス
define('MENU_CD_B1', 'B1'); // 店販商品
define('MENU_CD_C1', 'C1'); // 施術指名(個人)
define('MENU_CD_C2', 'C2'); // 施術指名(男性)
define('MENU_CD_C3', 'C3'); // 施術指名(女性)
define('MENU_CD_D1', 'D1'); // 特殊調整(全体調整)
define('MENU_CD_D2', 'D2'); // 特殊調整(サービス調整)
define('MENU_CD_D3', 'D3'); // 特殊調整(店販商品調整)
define('MENU_CD_E1', 'E1'); // 一時メニュー

define('MENU_TYPE_A', 'A'); // サービス
define('MENU_TYPE_B', 'B'); // 店販商品
define('MENU_TYPE_C', 'C'); // 施術指名
define('MENU_TYPE_D', 'D'); // 特殊調整
define('MENU_TYPE_E', 'E'); // 一時メニュー

define('MENU_ENTITY_TYPE_A', 'A'); // グランドメニュー
define('MENU_ENTITY_TYPE_B', 'B'); // 店舗固有メニュー

define('RECEIPT', 'receipt');

define('TYPE_SETTLEMENT', 'settlement');
define('TYPE_MID_CHECK', 'mid_check');
define('TYPE_OPENING', 'opening');
define('TYPE_OPENING_MANUAL', 'manual');
define('TYPE_OPENING_AUTO', 'auto');

$config['MenuType'] = [
    MENU_TYPE_SERVICES => ['A'],
    MENU_TYPE_GOODS => ['B'],
    MENU_TYPE_OTHERS => ['C', 'D', 'E']
];

$config['SettlementType'] = [
    SETTLEMENT_TYPE_A => '開店',
    SETTLEMENT_TYPE_B => '中間',
    SETTLEMENT_TYPE_C => '精算'
];

$config['SettlementCd'] = [
    SETTLEMENT_CD_1 => '入金',
    SETTLEMENT_CD_2 => '過剰調整',
    SETTLEMENT_CD_3 => '出金',
    SETTLEMENT_CD_4 => '不足調整',
    SETTLEMENT_CD_5 => '点検',
    SETTLEMENT_CD_6 => '銀行預け入れ',
    SETTLEMENT_CD_7 => '釣銭準備'
];

$config['DepositWithdrawType'] = [
    'A' => '入金',
    'B' => '出金',
    'C' => '売上回収'
];

$config['PaymentMethodType'] = ['A', 'B', 'C'];
$config['CreditCardType'] = ['A', 'B', 'C', 'D', 'E', 'F'];
$config['GenCodeArray'] = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
$config['SettlementCdArray'] = ['1', '2', '3', '4', '5', '6', '7'];
$config['DepositSettlementCdArray'] = ['1', '4'];
$config['WithdrawSettlementCdArray'] = ['2', '3', '6'];
$config['TypeParamArray'] = ['settlement', 'mid_check', 'opening'];