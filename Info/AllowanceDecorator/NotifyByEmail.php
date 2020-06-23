<?php

namespace FbBuy\Package\Ecpay\Invoice\Info\AllowanceDecorator;

use FbBuy\Package\Ecpay\Invoice\Info\AllowanceInfo;

class NotifyByEmail extends AllowanceInfo
{
    /**
     * @var AllowanceInfo
     */
    protected $info;

    /**
     * @var string
     */
    protected $email;

    public function __construct(AllowanceInfo $info, string $email)
    {
        $this->info = $info;

        $this->email = $email;
    }

    public function getInfo()
    {
        $result = $this->info->getInfo();

        if (isset($result['AllowanceNotify']) && $result['AllowanceNotify'] == \EcpayAllowanceNotifyType::Sms) {
            $result['AllowanceNotify'] = \EcpayAllowanceNotifyType::All;
        } else {
            $result['AllowanceNotify'] = \EcpayAllowanceNotifyType::Email;
        }

        $result['NotifyMail'] = $this->email;
        return $result;
    }
}
