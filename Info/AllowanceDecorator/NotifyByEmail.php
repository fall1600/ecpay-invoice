<?php

namespace fall1600\Package\Ecpay\Invoice\Info\AllowanceDecorator;

use fall1600\Package\Ecpay\Invoice\Constants\AllowanceNotifyType;
use fall1600\Package\Ecpay\Invoice\Info\AllowanceInfo;

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

        if (isset($result['AllowanceNotify']) && $result['AllowanceNotify'] == AllowanceNotifyType::SMS) {
            $result['AllowanceNotify'] = AllowanceNotifyType::ALL;
        } else {
            $result['AllowanceNotify'] = AllowanceNotifyType::EMAIL;
        }

        $result['NotifyMail'] = $this->email;
        return $result;
    }
}
