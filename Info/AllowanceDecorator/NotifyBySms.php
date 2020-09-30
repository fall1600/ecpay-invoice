<?php

namespace fall1600\Package\Ecpay\Invoice\Info\AllowanceDecorator;

use fall1600\Package\Ecpay\Invoice\Constants\AllowanceNotifyType;
use fall1600\Package\Ecpay\Invoice\Info\AllowanceInfo;

class NotifyBySms extends AllowanceInfo
{
    /**
     * @var AllowanceInfo
     */
    protected $info;

    /**
     * @var string
     */
    protected $phone;

    public function __construct(AllowanceInfo $info, string $phone)
    {
        $this->info = $info;

        $this->phone = $phone;
    }

    public function getInfo()
    {
        $result = $this->info->getInfo();

        if (isset($result['AllowanceNotify']) && $result['AllowanceNotify'] == AllowanceNotifyType::EMAIL) {
            $result['AllowanceNotify'] = AllowanceNotifyType::ALL;
        } else {
            $result['AllowanceNotify'] = AllowanceNotifyType::SMS;
        }

        $result['NotifyPhone'] = $this->phone;
        return $result;
    }
}
