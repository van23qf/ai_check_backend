<?php
namespace common\components;

use Yii;
use yii\web\Response;
use Da\QrCode\QrCode;
use Da\QrCode\Contracts\ErrorCorrectionLevelInterface;

class QRCodeUtil
{
    public static function make($text, $logo = null, $size=7)
    {
        $qrCode = new QrCode($text);
        $qrCode = $qrCode->setSize(240)
            ->setMargin(5)
            ->setErrorCorrectionLevel(ErrorCorrectionLevelInterface::MEDIUM);

        if($logo){
            $logo = Yii::getAlias('@webroot').$logo;
            if(file_exists($logo)){
                $qrCode = $qrCode->useLogo($logo)->setLogoWidth(60);
            }
        }
        
        return $qrCode->writeString();
    }
}