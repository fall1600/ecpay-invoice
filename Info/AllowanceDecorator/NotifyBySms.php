<?php

namespace fall1600\Package\Ecpay\Invoice\Info\AllowanceDecorator;

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

        if (isset($result['AllowanceNotify']) && $result['AllowanceNotify'] == \EcpayAllowanceNotifyType::Email) {
            $result['AllowanceNotify'] = \EcpayAllowanceNotifyType::All;
        } else {
            $result['AllowanceNotify'] = \EcpayAllowanceNotifyType::Sms;
        }

        $result['NotifyPhone'] = $this->phone;
        return $result;
    }
}
