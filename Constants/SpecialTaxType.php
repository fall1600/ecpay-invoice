<?php

namespace FbBuy\Package\Ecpay\Invoice\Constants;

use MyCLabs\Enum\Enum;

class SpecialTaxType extends Enum
{
    public const NONE = '';
    // 1:代表酒家及有陪侍服務之茶室、咖啡廳、酒吧之營業稅稅率, 稅率為 25%
    public const ONE = '1';
    // 2:代表夜總會、有娛樂節目之餐飲店之營業稅稅率,稅率為 15%
    public const TWO = '2';
    // 3:代表銀行業、保險業、信託投資業、證券業、期貨業、
    //   票券業及典當業之專屬本業收入(不含銀行業、保險業經營銀行、保險本業收入)之營業稅稅率,稅率為 2%
    public const THREE = '3';
    // 4:代表保險業之再保費收入之營業稅稅率,稅率為 1%
    public const FOUR = '4';
    // 5:代表銀行業、保險業、信託投資業、證券業、期貨業、票券業及典當業之非專屬本業收入之營業稅稅率,稅率為 5%
    public const FIVE = '5';
    // 6:代表銀行業、保險業經營銀行、保險本業收入之營業稅稅率(適用於民國 103 年 07 月以後銷售額) ,稅率為 5%
    public const SIX = '6';
    // 7:代表銀行業、保險業經營銀行、保險本業收入之營業稅稅率(適用於民國 103 年 06 月以前銷售額) ,稅率為 5%
    public const SEVEN = '7';
    // 8:代表空白為免稅或非銷項特種稅額之資料
    public const EIGHT = '8';

    public static $rules = [
        // 當課稅類別[TaxType]為 1/2/9 時,則此欄位請帶空字串
        TaxType::DUTIABLE => self::NONE,
        TaxType::ZERO => self::NONE,
        TaxType::MIX => self::NONE,
        // 當課稅類別[TaxType]為 3 時,此欄位請填入數字【8】
        TaxType::FREE => self::EIGHT,
        // 當課稅類別[TaxType]為 4 時,則該參數必填,可填入數字【1-8】,
        TaxType::SPECIAL => [
            self::ONE, self::TWO, self::TWO, self::THREE, self::FOUR, self::FIVE, self::SIX, self::SEVEN, self::EIGHT
        ]
    ];

    protected $taxRates = [
        self::ONE => 0.25,
        self::TWO => 0.15,
        self::THREE => 0.02,
        self::FOUR => 0.01,
        self::FIVE => 0.05,
        self::SIX => 0.05,
        self::SEVEN => 0.05,
        self::EIGHT => 0,
    ];
}
