<?php

namespace fall1600\Package\Ecpay\Invoice\Tests;

use fall1600\Package\Ecpay\Invoice\Merchant;
use PHPUnit\Framework\TestCase;

class MerchantTest extends TestCase
{
    public function test_encrypt()
    {
        //arrange

        $merchantId = '2000132';

        $hashKey = 'A123456789012345';

        $hashIv = 'B123456789012345';

        $merchant = new Merchant($merchantId, $hashKey, $hashIv);

        $data = [
            'Name' => 'Test',
            'ID' => 'A123456789',
        ];

        $expected = '7woM9RorZKAtXJRVccAb0qhHYm+5lnlhBzyfh5EZdNck7PacNsRHgv/Jvp//ajJidqcQcs0UmAgPQVjXQHeziw==';

        //act
        $encrypted = $merchant->encrypt($data);

        //assert
        $this->assertEquals($expected, $encrypted);
    }

    public function test_decrypt()
    {
        //arrange
        $merchantId = '2000132';

        $hashKey = 'A123456789012345';

        $hashIv = 'B123456789012345';

        $merchant = new Merchant($merchantId, $hashKey, $hashIv);

        $encrypted = '7woM9RorZKAtXJRVccAb0qhHYm+5lnlhBzyfh5EZdNck7PacNsRHgv/Jvp//ajJidqcQcs0UmAgPQVjXQHeziw==';

        $expected = [
            'Name' => 'Test',
            'ID' => 'A123456789',
        ];

        //act
        $decrypted = $merchant->decrypt($encrypted);

        //assert
        $this->assertEquals($expected, $decrypted);
    }
}
