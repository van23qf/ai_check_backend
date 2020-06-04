<?php

namespace Omnipay\Alipay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class BaseAbstractResponse
 * @package Omnipay\WechatPay\Message
 */
class RefundExpressResponse extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        $data = $this->getData();
        \Yii::info('退款返回', 'payment');
        \Yii::info($data, 'payment');
        $doc = new \DOMDocument();
        $doc->loadXML($data);

        //获取成功标识is_success
        $itemIs_success= $doc->getElementsByTagName( "is_success" );
        $nodeIs_success = $itemIs_success->item(0)->nodeValue;

        //获取错误代码 error
        $itemError_code= $doc->getElementsByTagName( "error" );
        $nodeError_code = $itemError_code->item(0)->nodeValue;

        if($nodeIs_success == 'T'){
            return true;
        }
        throw new \Exception($nodeError_code);
    }
}
