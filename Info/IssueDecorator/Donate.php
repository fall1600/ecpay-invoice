<?php

namespace fall1600\Package\Ecpay\Invoice\Info\IssueDecorator;

use fall1600\Package\Ecpay\Invoice\Info\Info;

class Donate extends Info
{
    /**
     * @var Info
     */
    protected $info;

    /**
     * @var string
     */
    protected $loveCode;

    public function __construct(Info $info, string $loveCode = '168001')
    {
        $this->info = $info;

        $this->loveCode = $loveCode;
    }
    
    public function getInfo()
    {
        return $this->info->getInfo() +
            [
                'Print' => 0,
                'Donation' => 1,
                'LoveCode' => $this->loveCode,
            ];
    }
}