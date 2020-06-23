<?php

namespace FbBuy\Package\Ecpay\Invoice\Info\IssueDecorator;

use FbBuy\Package\Ecpay\Invoice\Info\Info;

class Paper extends Info
{
    /**
     * @var Info
     */
    protected $info;

    /**
     * 客戶名稱
     * @var string
     */
    protected $name;

    /**
     * 客戶地址
     * @var string
     */
    protected $addr;

    /**
     * 統一編號
     * @var string
     */
    protected $identifier;

    public function __construct(Info $info, string $name, string $addr, string $identifier)
    {
        $this->info = $info;

        $this->name = $name;

        $this->addr = $addr;

        $this->identifier = $identifier;
    }

    public function getInfo()
    {
        return $this->info->getInfo() +
            [
                'Print' => 1,
                'CustomerName' => $this->name,
                'CustomerAddr' => $this->addr,
                'CustomerIdentifier' => $this->identifier,
            ];
    }
}
