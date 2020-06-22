<?php

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
     * 客戶名稱
     * @return string
     */
    public function getCustomerName();

    /**
     * 客戶地址
     * @return string
     */
    public function getCustomerAddress();

    /**
     * 客戶手機號碼
     * @return string
     */
    public function getCustomerPhone();

    /**
     * 客戶電子信箱
     * @return string
     */
    public function getCustomerEmail();
}
