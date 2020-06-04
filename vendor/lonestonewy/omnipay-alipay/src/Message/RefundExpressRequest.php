<?php
/**
 * Created by lonestone.
 * CreateTime: 16-4-3
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Alipay\Message\BasePurchaseRequest;


class RefundExpressRequest extends BasePurchaseRequest
{

    protected function validateData()
    {

        $this->validate(
            'service',
            'partner',
            'notify_url',
            'input_charset',
            'key',
            "refund_date",
            'detail_data',
            "batch_no",
            "batch_num"
        );
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validateData();
        $data              = array(
            "service"           => $this->getService(),
            "partner"           => $this->getPartner(),
            "_input_charset"    => $this->getInputCharset(),
            "notify_url"        => $this->getNotifyUrl(),
            "detail_data"       => $this->getDetailData(),
            "batch_no"          => $this->getBatchNo(),
            "batch_num"         => $this->getBatchNum(),
            "refund_date"          => $this->getRefundDate(),
        );
        $data              = array_filter($data);
        $data['sign']      = $this->getParamsSignature($data);
        $data['sign_type'] = $this->getSignType();
        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $options = array (
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            // CURLOPT_SSLCERTTYPE    => 'PEM',
            // CURLOPT_SSLKEYTYPE     => 'PEM',
            // CURLOPT_SSLCERT        => $this->getCertPath(),
            // CURLOPT_SSLKEY         => $this->getKeyPath(),
        );

        $request      = $this->httpClient->post($this->liveEndpoint, null, $data);
        $request->getCurlOptions()->merge($options);
        $responseData     = $request->send()->getBody(true);

        return $this->response = new RefundExpressResponse($this, $responseData);
    }

    public function getDetailData()
    {
        return $this->getParameter('detail_data');
    }

    public function setDetailData($value)
    {
        $this->setParameter('detail_data', $value);
    }

    public function getBatchNo()
    {
        return $this->getParameter('batch_no');
    }

    public function setBatchNo($value)
    {
        $this->setParameter('batch_no', $value);
    }

    public function getBatchNum()
    {
        return $this->getParameter('batch_num');
    }

    public function setBatchNum($value)
    {
        $this->setParameter('batch_num', $value);
    }

    public function getRefundDate()
    {
        return $this->getParameter('refund_date');
    }

    public function setRefundDate($value)
    {
        $this->setParameter('refund_date', $value);
    }
}
