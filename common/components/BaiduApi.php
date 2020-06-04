<?php
namespace common\components;

use yii;
use yii\base\UserException;

/**
 * 百度API
 *
 */
class BaiduApi
{
    public static $uri = 'https://aip.baidubce.com/';

    /**
     * 获取accessToken
     *
     * @return void
     */
    private function getAccessToken()
    {
        $data = Yii::$app->cache->get('baidu-api-access-token');
        if ($data !== false) {
            return $data['access_token'];
        }
        $post_data['grant_type'] = 'client_credentials';
        $post_data['client_id'] = 'Kczt1GBRXYNj3beLEabU4Zmp';
        $post_data['client_secret'] = 'FILx9WzUWoN5VEXi9SKr6L2IdFQRZ5QR';

        $data = $this->request('oauth/2.0/token', $post_data, false);
        Yii::$app->cache->set('baidu-api-access-token', $data, $data['expires_in'] - 300);
        return $data['access_token'];
    }

    private function request($api, $post_data, $token = true)
    {
        $client = new \yii\httpclient\Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);
        Yii::info($api, 'api');
        // Yii::info($post_data, 'api');
        $url = self::$uri.$api;
        if($token) $url .= '?access_token='.$this->getAccessToken();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($post_data)
            ->send();

        if ($response->isOk) {
            Yii::info($response->data, 'api');
            return $response->data;
        }
        return false;
    }

    /**
     * 身份证识别
     *
     * @param string $image
     * @param string $id_card_side
     * @return void
     */
    public function OcrIdcard($image, $id_card_side)
    {
        if (!in_array($id_card_side, ['front', 'back'])) {
            throw new UserException('身份证正反面参数必须是 front back 之一');
        }

        $api = 'rest/2.0/ocr/v1/idcard';
        $post_data['image'] = base64_encode(file_get_contents(Yii::getAlias('@webroot' . $image)));
        $post_data['id_card_side'] = $id_card_side;
        $post_data['detect_risk'] = 'true';
        $post_data['detect_direction'] = 'true';
        return $this->request($api, $post_data);
    }

    /**
     * 图像主体检测
     *
     * @param string $image
     * @return void
     */
    public function ObjectDetect($image)
    {
        $api = 'rest/2.0/image-classify/v1/object_detect';
        $post_data['image'] = base64_encode(file_get_contents(Yii::getAlias('@webroot' . $image)));
        return $this->request($api, $post_data);
    }

    /**
     * 人脸对比
     *
     * @param string $image
     * @return void
     */
    public function FaceCompare($image, $image_another)
    {
        $api = 'rest/2.0/face/v3/match';
        $post_data[0]['image'] = base64_encode(file_get_contents(Yii::getAlias('@webroot' . $image)));
        $post_data[0]['image_type'] = 'BASE64';
        $post_data[0]['face_type'] = 'LIVE';
        $post_data[0]['quality_control'] = 'LOW';
        $post_data[0]['liveness_control'] = 'NONE';

        $post_data[1]['image'] = base64_encode(file_get_contents(Yii::getAlias('@webroot' . $image_another)));
        $post_data[1]['image_type'] = 'BASE64';
        $post_data[1]['face_type'] = 'LIVE';
        $post_data[1]['quality_control'] = 'LOW';
        $post_data[1]['liveness_control'] = 'NONE';

        return $this->request($api, $post_data);
    }

    /**
     * 人脸对比
     *
     * @param string $image
     * @return void
     */
    public function FaceCompareBaxs($image, $image_another)
    {
        $api = 'rest/2.0/face/v3/match';
        $post_data[0]['image'] = $image;
        $post_data[0]['image_type'] = 'URL';
        $post_data[0]['face_type'] = 'LIVE';
        $post_data[0]['quality_control'] = 'LOW';
        $post_data[0]['liveness_control'] = 'NONE';

        $post_data[1]['image'] = base64_encode(file_get_contents(Yii::getAlias('@webroot' . $image_another)));
        $post_data[1]['image_type'] = 'BASE64';
        $post_data[1]['face_type'] = 'LIVE';
        $post_data[1]['quality_control'] = 'LOW';
        $post_data[1]['liveness_control'] = 'NONE';

        return $this->request($api, $post_data);
    }

    /**
     * 活体检测
     *
     * @param string $image
     * @return void
     */
    public function FaceVerify($image)
    {
        $api = 'rest/2.0/face/v3/faceverify';
        $post_data[0]['image'] = base64_encode(file_get_contents(Yii::getAlias('@webroot' . $image)));
        $post_data[0]['image_type'] = 'BASE64';

        return $this->request($api, $post_data);
    }

    /**
     * 人脸对比
     *
     * @param string $image
     * @return void
     */
    public function FaceCompareBaxsByBase64($image, $image_another)
    {
        $api = 'rest/2.0/face/v3/match';
        $post_data[0]['image'] = $image;
        $post_data[0]['image_type'] = 'BASE64';
        $post_data[0]['face_type'] = 'LIVE';
        $post_data[0]['quality_control'] = 'LOW';
        $post_data[0]['liveness_control'] = 'NONE';

        $post_data[1]['image'] = base64_encode(file_get_contents(Yii::getAlias('@webroot' . $image_another)));
        $post_data[1]['image_type'] = 'BASE64';
        $post_data[1]['face_type'] = 'LIVE';
        $post_data[1]['quality_control'] = 'LOW';
        $post_data[1]['liveness_control'] = 'NONE';

        return $this->request($api, $post_data);
    }
}
