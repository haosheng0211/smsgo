<?php

namespace SMSGo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SMSGo
{
    public const API_URL = 'https://www.smsgo.com.tw/sms_gw';

    public $username;

    public $password;

    /**
     * @param string $username 會員帳號
     * @param string $password 會員密碼 或 API Key
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * 簡訊發送
     *
     * @param string $dstaddr  接收簡訊之手機號碼，一次發送多筆號碼可用逗號隔開(不可超過 50 筆)。
     *                         國內號碼 09 開頭，十碼的數字；
     *                         國際號碼請在開頭多個%2b 例如傳大陸: %2b8613681912700
     *                         其中 86 是大陸國碼，後面(以 13、15、18 開頭) 11 位數字是大陸手機號碼。
     * @param string $smbody   簡訊內容，中英文長度為 70 個字元，純英文為 160 個字元
     *                         若 encoding 為 LBIG5/LASCII/LUCS2，則大小為 330 個中英文字
     *                         若 encoding 為 PUSH，則此欄為 wap push title
     * @param string $encoding BIG5/ASCII/UCS2/PBIG5/PASCII/LBIG5/LASCII/LUCS2/PUSH
     *                         預設值為 BIG5 (註: 此 encoding 可提供簡訊購作訊息處理，以及手機接收訊息後該用何
     *                         種編碼讀取等之用。P 表 POPUP 簡訊、L 表長簡訊、PUSH 表 wap push)
     * @param array  $options  可選參數
     *                         - string $dlvtime 預約時間，格式為 YYYY/MM/DD hh24:mm:ss
     *                         - string $wapurl 當 encoding 設為 PUSH 才可以使用這個 tag
     *                         - string $replyurl receiver 若有回覆簡訊時，vender 用來接收該回覆訊息的網址。(需另計點)
     *                         當 encoding 設為 BIG5/ASCII/UCS2 才可以使用這個 tag
     *                         - int $replydays 收取用戶回覆的天數，預設為 3(天)最大值不能超過 30(天)，
     *                         當 encoding 設為 BIG5/ASCII/UCS2 才可以使用這個 tag
     *                         - string $response 狀態回報網址，預設為空字串(不回報)
     *                         - string $rtype 回傳訊息之格式: JSON / XML / 省略 (default，為一般之文字內容)
     *
     * @throws GuzzleException
     */
    public function sendSMS(string $dstaddr, string $smbody, string $encoding = 'BIG5', array $options = []): string
    {
        $params = array_merge([
            'username' => $this->username,
            'password' => $this->password,
            'dstaddr'  => $dstaddr,
            'smbody'   => $smbody,
            'encoding' => $encoding,
        ], $options);

        $response = $this->httpClient()->get(self::API_URL . '/sendsms.aspx', [
            'query' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * 單筆查詢發送狀態.
     *
     * @param string $msgid   訊息代號
     * @param string $dstaddr 電話號碼
     *
     * @throws GuzzleException
     */
    public function querySMSStatus(string $msgid, string $dstaddr): string
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'msgid'    => $msgid,
            'dstaddr'  => $dstaddr,
        ];

        $response = $this->httpClient()->get(self::API_URL . '/query.asp', [
            'query' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * 多筆查詢發送狀態.
     *
     * @param string $msgid   訊息代號 (查詢多個 msgid 時可用逗號分隔)
     * @param string $dstaddr 電話號碼 (查詢多個電話號碼時可用逗號分隔)
     *
     * @throws GuzzleException
     */
    public function queryBulkSMSStatus(string $msgid, string $dstaddr): string
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'msgid'    => $msgid,
            'dstaddr'  => $dstaddr,
        ];

        $response = $this->httpClient()->get(self::API_URL . '/query.asp', [
            'query' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * 查詢剩餘點數.
     *
     * @throws GuzzleException
     */
    public function queryPoints(): string
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        $response = $this->httpClient()->get(self::API_URL . '/query_point.asp', [
            'query' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * 取消預約發送
     *
     * @param string $msgid 訊息代號
     *
     * @throws GuzzleException
     */
    public function cancelScheduledSMS(string $msgid): string
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'msgid'    => $msgid,
        ];

        $response = $this->httpClient()->get(self::API_URL . '/sendsms_cancel.asp', [
            'query' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    private function httpClient(): Client
    {
        return new Client([
            'connect_timeout' => 20,
        ]);
    }
}
