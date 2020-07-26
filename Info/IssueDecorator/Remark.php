<?php

namespace FbBuy\Package\Ecpay\Invoice\Info\IssueDecorator;

use FbBuy\Package\Ecpay\Invoice\Info\Info;

class Remark extends Info
{
    /**
     * @var Info
     */
    protected $info;

    /**
     * @var string
     */
    protected $remark;

    public function __construct(Info $info, string $remark)
    {
        $this->info = $info;

        $this->remark = $remark;
    }

    public function getInfo()
    {
        return $this->info->getInfo() +
            [
                'InvoiceRemark' => $this->remark,
            ];
    }
}
