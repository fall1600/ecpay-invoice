# ecpay-invoice

## How to use

#### 建立發票資訊 (BasicInfo)
 - $merchantId: 你在綠界申請的商店代號
 - $order: 你的訂單物件, 務必實作package 中的OrderInterface
 - $contact: 聯繫付款人方式, 務必實作package 中的ContactInterface
 - $vatType: 商品單價是否為含稅價
```php
$info = new BasicInfo($order, $contact, $vatType = 1);
```

#### 發票要去的地方(印出來, 捐贈, 載具), 則一decorate
 - $name: 買方姓名
 - $addr: 買方地址
 - $identifier: 統一編號
 - $loveCode: 捐贈碼
 - $carrierType: 載具類型(1: 綠界會員, 2: 自然人憑證, 3: 手機載具)
 - $carrierVal: 載具值
```php
$info = new Paper($info, $name, $addr, $identifier);
$info = new Donate($info, $loveCode = '168001');
$info = new Carrier($info, $carrierType, $carrierVal);
```

#### 建立Ecpay 物件, 注入商店資訊, 帶著發票資訊開立發票
 - $merchantId: 你在綠界商店代號
 - $hashKey: 你在綠界商店專屬的HashKey
 - $hashIv: 你在綠界商店專屬的HashIV
 
```php
$ecpay = new Ecpay();
$ecpay
    ->setIsProduction(false) // 設定環境, 預設就是走正式機
    ->setMerchant(new Merchant($merchantId, $hashKey, $hashIv))
    ->issue($info);
```

#### 查詢發票明細
 - $order: 你的訂單物件, 務必實作package 中的OrderInterface
```php
$ecpay->queryIssue($order);
```

#### 作廢發票
 - $invoiceNumber: 發票號碼
 - $reason: 作廢原因
```php
$ecpay->invalid(string $invoiceNumber, string $reason = '');
```

#### 查詢作廢明細
```php
$ecpay->queryInvalid($order);
```

#### 折讓發票, 建立折讓發票資訊, 塞入需折讓的品項
 - $invoiceNumber: 發票號碼
 - $returnUrl: 一律走線上折讓, 需要掛returnUrl, 用來收買家同意後綠界的webhook
 - $vatType: 單價是否為含稅價
```php
$info = new AllowanceBasicInfo($invoiceNumber, $returnUrl, $vatType = 1);
$info->appendItem(ItemInterface $item);
$info->appendItem(ItemInterface $item);

// 折讓通常要通知買方, 有掛NotifyByEmail, 或NotifyBySms 就會個別通知
$info = new NotifyByEmail($info, 'fbbuy@fbbuy.com.tw');
$info = new NotifyBySms($info, '0988123456');

$ecpay->allowance(AllowanceInfo $info);
```

#### 查詢折讓明細
 - $invoiceNumber: 發票號碼
 - $allowanceNumber: 作廢號碼
```php
$ecpay->queryAllowance($invoiceNumber, $allownceNumber);
```

#### 作廢折讓
 - $invoiceNumber: 發票號碼
 - $allowanceNumber: 作廢號碼
 - $reason: 原因
```php
$ecpay->invalidAllowance($invoiceNumber, $allownceNumber, $reason);
```

#### 查詢作廢折讓明細
 - $invoiceNumber: 發票號碼
 - $allowanceNumber: 作廢號碼
```php
$ecpay->queryInvalidAllowance($invoiceNumber, $allownceNumber);
```

#### 驗證手機載具
 - $carrier: 手機載具
```php
$ecpay->verifyCarrier($carrier);
```

#### 驗證捐贈代碼
 - $lovecode: 捐贈代碼
```php
$ecpay->verifyLovecode($lovecode);
```