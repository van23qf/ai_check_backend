<?php
namespace common\components;

use Yii;

class MutilpleDomainUrlManager extends \yii\web\UrlManager
{
    public $domains = array();

    public function createUrl($domain, $params = array())
    {
        if (func_num_args() === 1) {
            $params = $domain;
            $domain = false;
        }
        $bak = $this->getBaseUrl();
        if ($domain) {
            if (!isset($this->domains[$domain])) {
                throw new \yii\base\InvalidConfigException('Please configure UrlManager of domain "' . $domain . '".');
            }
            $this->setBaseUrl($this->domains[$domain]);
        }
        $url = parent::createUrl($params);
        $this->setBaseUrl($bak);
        return $url;
    }
}