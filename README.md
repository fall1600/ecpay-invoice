# ecpay-invoice

## How to use

#### 建立交易資訊 (BasicInfo)
 - $merchantId: 你在綠界申請的商店代號
 - $order: 你的訂單物件, 務必實作package 中的OrderInterface
 - $contact: 聯繫付款人方式, 務必實作package 中的ContactInterface
 - $vatType: 商品單價是否為含稅價
```php
$info = new BasicInfo($order, $contact, $vatType = 1);
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

#### 折讓發票
 - $info: 折讓資訊
```php
$ecpay->allowance(AllowanceInfo $info);
```

#### 查詢作廢明細
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

#### 查詢作廢明細
 - $invoiceNumber: 發票號碼
 - $allowanceNumber: 作廢號碼
```php
$ecpay->queryInvalidAllowance($invoiceNumber, $allownceNumber);
```