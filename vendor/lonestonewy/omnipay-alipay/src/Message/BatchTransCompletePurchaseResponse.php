<?php
/**
 * Created by lonestone.
 * CreateTime: 16-4-3
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Common\Message\AbstractResponse;

class BatchTransCompletePurchaseResponse extends AbstractResponse
{

    /**
     * @var ExpressCompletePurchaseRequest
     */
    protected $request;

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data['verify_success']) {
            return true;
        } else {
            return false;
        }
    }

    public function getResponseText()
    {
        if ($this->isSuccessful()) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    public function isTradeStatusOk()
    {
        return true;
    }
}
