<?php

namespace fall1600\Package\Ecpay\Invoice\Tests\Info;

use fall1600\Package\Ecpay\Invoice\Constants\SpecialTaxType;
use fall1600\Package\Ecpay\Invoice\Constants\TaxType;
use fall1600\Package\Ecpay\Invoice\Constants\VatType;
use fall1600\Package\Ecpay\Invoice\Contracts\ContactInterface;
use fall1600\Package\Ecpay\Invoice\Contracts\OrderInterface;
use fall1600\Package\Ecpay\Invoice\Info\BasicInfo;
use fall1600\Package\Ecpay\Invoice\Info\Info;
use PHPUnit\Framework\TestCase;

class InfoTest extends TestCase
{
    public function dataProviderWillException()
    {
        return [
            [TaxType::DUTIABLE, SpecialTaxType::ONE],
            [TaxType::DUTIABLE, SpecialTaxType::TWO],
            [TaxType::DUTIABLE, SpecialTaxType::THREE],
            [TaxType::DUTIABLE, SpecialTaxType::FOUR],
            [TaxType::DUTIABLE, SpecialTaxType::FIVE],
            [TaxType::DUTIABLE, SpecialTaxType::SIX],
            [TaxType::DUTIABLE, SpecialTaxType::SEVEN],
            [TaxType::DUTIABLE, SpecialTaxType::EIGHT],
            [TaxType::DUTIABLE, 'foo'],
            [TaxType::ZERO, SpecialTaxType::ONE],
            [TaxType::ZERO, SpecialTaxType::TWO],
            [TaxType::ZERO, SpecialTaxType::THREE],
            [TaxType::ZERO, SpecialTaxType::FOUR],
            [TaxType::ZERO, SpecialTaxType::FIVE],
            [TaxType::ZERO, SpecialTaxType::SIX],
            [TaxType::ZERO, SpecialTaxType::SEVEN],
            [TaxType::ZERO, SpecialTaxType::EIGHT],
            [TaxType::ZERO, 'foo'],
            [TaxType::MIX, SpecialTaxType::ONE],
            [TaxType::MIX, SpecialTaxType::TWO],
            [TaxType::MIX, SpecialTaxType::THREE],
            [TaxType::MIX, SpecialTaxType::FOUR],
            [TaxType::MIX, SpecialTaxType::FIVE],
            [TaxType::MIX, SpecialTaxType::SIX],
            [TaxType::MIX, SpecialTaxType::SEVEN],
            [TaxType::MIX, SpecialTaxType::EIGHT],
            [TaxType::MIX, 'foo'],
            [TaxType::FREE, SpecialTaxType::SEVEN],
            [TaxType::FREE, 'foo'],
            [TaxType::SPECIAL, SpecialTaxType::NONE],
            [TaxType::SPECIAL, 'foo'],
        ];
    }

    /**
     * @dataProvider dataProviderWillException
     * @param  string  $taxType
     * @param  string  $specialTaxType
     */
    public function test_construct_will_exception(string $taxType, string $specialTaxType)
    {
        $this->expectException(\LogicException::class);

        //arrange
        $order = $this->getMockBuilder(OrderInterface::class)
            ->getMockForAbstractClass();

        $contact = $this->getMockBuilder(ContactInterface::class)
            ->getMockForAbstractClass();

        //act
        $info = new BasicInfo($order, $contact, VatType::YES, $taxType, $specialTaxType);

        //assert
    }

    public function dataProvider()
    {
        return [
            [TaxType::DUTIABLE, SpecialTaxType::NONE],
            [TaxType::ZERO, SpecialTaxType::NONE],
            [TaxType::MIX, SpecialTaxType::NONE],
            [TaxType::FREE, SpecialTaxType::EIGHT],
            [TaxType::SPECIAL, SpecialTaxType::ONE],
            [TaxType::SPECIAL, SpecialTaxType::TWO],
            [TaxType::SPECIAL, SpecialTaxType::THREE],
            [TaxType::SPECIAL, SpecialTaxType::FOUR],
            [TaxType::SPECIAL, SpecialTaxType::FIVE],
            [TaxType::SPECIAL, SpecialTaxType::SIX],
            [TaxType::SPECIAL, SpecialTaxType::SEVEN],
            [TaxType::SPECIAL, SpecialTaxType::EIGHT],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param  string  $taxType
     * @param  string  $specialTaxType
     */
    public function test_construct(string $taxType, string $specialTaxType)
    {
        //arrange
        $order = $this->getMockBuilder(OrderInterface::class)
            ->getMockForAbstractClass();

        $contact = $this->getMockBuilder(ContactInterface::class)
            ->getMockForAbstractClass();

        //act
        $info = new BasicInfo($order, $contact, VatType::YES, $taxType, $specialTaxType);

        //assert
        $this->assertInstanceOf(Info::class, $info);
    }
}
