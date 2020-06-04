<?php

namespace Omnipay\Alipay;

use Omnipay\Alipay\BaseAbstractGateway;

/**
 * Class BatchTransGateway
 *
 * @package Omnipay\Alipay
 */
class BatchTransGateway extends BaseAbstractGateway
{

    protected $service_name = 'batch_trans_notify';

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Batch Trans Notify';
    }

    public function purchase(array $parameters = array())
    {
        $this->setService($this->service_name);
        return $this->createRequest('\Omnipay\Alipay\Message\BatchTransPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Alipay\Message\BatchTransCompletePurchaseRequest', $parameters);
    }
}
