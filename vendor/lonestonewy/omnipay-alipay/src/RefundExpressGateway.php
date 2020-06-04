<?php

namespace Omnipay\Alipay;

use Omnipay\Alipay\BaseAbstractGateway;

/**
 * 无密码退款即时到帐接口
 *
 * @package Omnipay\Alipay
 */
class RefundExpressGateway extends BaseAbstractGateway
{

    protected $service_name = 'refund_fastpay_by_platform_nopwd';

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Alipay Refund Express';
    }

    public function refund(array $parameters = array())
    {
        $this->setService($this->service_name);
        return $this->createRequest('\Omnipay\Alipay\Message\RefundExpressRequest', $parameters);
    }

    public function completeRefund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Alipay\Message\RefundExpressCompleteRequest', $parameters);
    }
}
