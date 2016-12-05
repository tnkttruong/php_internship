<?php
/* ###############################################################################
 *
 * システム名
 *   ラジカルオプティ サロン事業 POSシステム
 *
 * 機能
 *   CahePHP 設定読みこみ
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

$config['message'] = [];

$config['message']['fatal']['番号'] = '';
$config['message']['error'][300] = 'Multiple Choices. 複数ページの利用が可能です。';
$config['message']['error'][301] = 'Moved Parmanently. このアドレスは違うアドレスに移動しました。';
$config['message']['error'][302] = 'Moved Temporarily. このアドレスは一時的に別のアドレスにおいています。';
$config['message']['error'][303] = 'See Other. 他のページを参照してください。';
$config['message']['error'][304] = 'Not Modified. アクセスは許可されたが、対象の文書は更新されていなかった。';
$config['message']['error'][305] = 'Use Proxy. LocationフィールドのProxy経由でないとアクセス許可されません。';
$config['message']['error'][307] = 'Temporary Redirect. このアドレスは一時的に別のアドレスに属しています。';
$config['message']['error'][400] = 'Bad Request. タイプミス等、リクエストにエラーがあります。';
$config['message']['error'][401] = 'Unauthorixed. 認証に失敗しました。';
$config['message']['error'][403] = 'Forbidden. あなたにはアクセス権がありません。';
$config['message']['error'][404] = '(File)Not Found. 該当アドレスのページはありません、';
$config['message']['error'][405] = 'Method Not Allowed. 許可されていないメソッドタイプのリクエストを受けた。';
$config['message']['error'][406] = 'Not Acceptable. Acceptヘッダから判断された結果、受け入れられない内容を持っていた。';
$config['message']['error'][407] = 'Proxy Authentication Required. 最初にProxy認証が必要です。';
$config['message']['error'][408] = 'Request Time-out. リクエストの待ち時間内に反応がなかった。';
$config['message']['error'][409] = 'Conflict. そのリクエストは現在の状態のリソースと矛盾するため完了できなかった。';
$config['message']['error'][410] = 'Gone. そのリクエストはサーバでは利用できず転送先のアドレスも分からない。';
$config['message']['error'][411] = 'Length Required. 定義されたContent-Lengthの無いリクエストを拒否しました。';
$config['message']['error'][412] = 'Precondition Failed. 1つ以上のリクエストヘッダフィールドで与えられた条件がサーバ上のテストで不正であると判断しました。';
$config['message']['error'][413] = 'Request Entity Too Large. 処理可能量より大きいリクエストのため拒否しました。';
$config['message']['error'][414] = 'Request-URI Too Large. リクエストURIが長すぎるため拒否しました。';
$config['message']['error'][415] = 'Unsupported Media Type. リクエストされたメソッドに対してリクエストされたリソースがサポートしていないフォーマットであるため、サーバはリクエストのサービスを拒否しました。';
$config['message']['error'][416] = 'Requested range not satisfiable. リクエストにRangeヘッダフィールドは含まれていたが、If-Rangeリクエストヘッダフィールドがありません。';
$config['message']['error'][417] = 'Expectation Failed. Expectリクエストヘッダフィールド拡張が受け入れられなかった。';
$config['message']['error'][500] = 'Internal Server Error. CGIスクリプトなどでエラーが出た。';
$config['message']['error'][501] = 'Not Implemented. リクエストを実行するための必要な機能をサポートしていない。';
$config['message']['error'][502] = 'Bad Gateway. ゲートウェイやProxyとして動作しているサーバがリクエストを実行しようとしたら不正なレスポンスを受け取った。';
$config['message']['error'][503] = 'Service Unavailable. そのアドレスは事情によりアクセスできません。';
$config['message']['error'][504] = 'Gateway Time-out. リクエストを完了するために必要なDNSなどのサーバからレスポンスを受信できなかった。';
$config['message']['error'][505] = 'HTTP Version not supported. サポートされていないHTTPプロトコルバージョンを受けた。';
$config['message']['error'][999] = 'Version Error. サーバーとアプリケーションのバージョンが一致しません。';
$config['message']['warn']['番号'] = '';
$config['message']['info']['番号'] = '';

// ###############################################################################
//  クラス
// ###############################################################################

// ###############################################################################
//  関数
// ###############################################################################

// ###############################################################################
//  処理
// ###############################################################################

