<?php

namespace FbBuy\Package\Ecpay\Invoice\Contracts;

interface CustomerInterface
{
    /**
     * 客戶編號
     * @return string
     */
    public function getCustomerId();

    /**
     * 統一編號
     * @return string
     */
    public function getCustomerIdentifier();

    /**
     * 手機或信箱的值
     * @return string
     */
    public function getContact();

    /**
     * email or phone
     * @return string
     */
    public function getContactType();
}
