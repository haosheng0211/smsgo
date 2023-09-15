## SMSGo 使用說明

歡迎使用 SMSGo！本說明將引導您如何在您的項目中集成並使用這個包，以便輕鬆地透過 SMSGo 服務發送簡訊。

### 安裝

使用 Composer 命令來安裝 SMSGo Composer 包：

```sh
composer require smsgo/smsgo
```

### 使用範例

以下是一個簡單的範例，展示了如何使用 SMSGo Composer 包來發送簡訊：

```php
<?php

require 'vendor/autoload.php';

use SMSGO\SMSGo;

// 填入您的會員帳號和密碼/API Key
$username = 'your_username';
$password = 'your_password_or_api_key';

// 初始化 SMSGo 物件
$smsGo = new SMSGo($username, $password);

// 設定發送簡訊的參數
$dstaddr = '09xxxxxxxx'; // 收件人手機號碼
$smbody = '您好，這是一則測試簡訊。'; // 簡訊內容

try {
    // 發送簡訊
    $response = $smsGo->sendSMS($dstaddr, $smbody);
    echo '簡訊發送成功：' . $response;
} catch (GuzzleException $e) {
    echo '簡訊發送失敗，錯誤訊息：' . $e->getMessage();
}
```

### 方法說明

#### 初始化 SMSGo 物件

使用 `SMSGo` 類別的建構函式來初始化物件，並傳入您的會員帳號和密碼或 API Key。

```php
$smsGo = new SMSGo($username, $password);
```

#### 發送簡訊

使用 `sendSMS` 方法來發送簡訊。您需要提供收件人手機號碼和簡訊內容。可選的參數包括編碼方式和其他選項。

```php
$response = $smsGo->sendSMS($dstaddr, $smbody, $encoding, $options);
```

#### 查詢簡訊狀態

您可以使用 `querySMSStatus` 方法來查詢單筆或多筆簡訊的發送狀態。

```php
$response = $smsGo->querySMSStatus($msgid, $dstaddr);
```

或

```php
$response = $smsGo->queryBulkSMSStatus($msgid, $dstaddr);
```

#### 查詢剩餘點數

使用 `queryPoints` 方法來查詢您的帳戶剩餘點數。

```php
$response = $smsGo->queryPoints();
```

#### 取消預約發送

如果您需要取消預約發送的簡訊，可以使用 `cancelScheduledSMS` 方法。

```php
$response = $smsGo->cancelScheduledSMS($msgid);
```

### 注意事項

請確保您已正確填寫會員帳號和密碼/API Key，以及收件人的手機號碼和簡訊內容。

以上僅為簡單的使用範例，您可以根據您的項目需求進一步擴展和調整程式碼。如有疑問，您可以參考 [SMSGo 官方 API 文件](https://www.smsgo.com.tw/api/SMSGO%20API_V6.2.pdf)。