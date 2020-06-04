<?php
namespace common\components;

use yii;
use yii\helpers\Html;

class Formatter extends \yii\i18n\Formatter
{
    /**
     * Formats the array value as readable format.
     * @param mixed $value the value to be formatted
     * @return string the formatted result
     */
    public function asArray($value)
    {
        if (!empty($value) && is_array($value)) {
            $html .= '<table>';
            foreach ($value as $name => $val) {
                $html .= Html::tag('tr', '<td style="font-weight:normal">' . ($name + 1) . '：</td><td>' . nl2br($val) . '</td>');
            }
            $html .= '</table>';
            return $html;
        } else {
            return $value;
        }

    }

    /**
     * 与框架自带的区别是不判断是否有http://
     * Formats the value as a hyperlink.
     * @param mixed $value the value to be formatted
     * @return string the formatted result
     */
    public function asUrl($value, $options = [])
    {
        return Html::a(Html::encode($value), $value, $options);
    }

    public function asMaskIDSN($value)
    {
        $pattern = '/^(\d{3})(\d{8,11})(\d{4})$/';
        return preg_replace($pattern, '$1***********$3', $value);
    }

    public function asMaskMobile($value)
    {
        $pattern = '/^(\d{7})(\d{4})$/';
        return preg_replace($pattern, '*******$2', $value);
    }

    public function asMaskName($value)
    {
        $len = mb_strlen($value, Yii::$app->charset);
        if ($len < 2) {
            return $value;
        }

        $start = ($len > 2) ? mb_substr($value, 0, 1, Yii::$app->charset) : '';
        $end = mb_substr($value, -1, 1, Yii::$app->charset);
        return $start . str_repeat('*', $len - 1 - mb_strlen($start, Yii::$app->charset)) . $end;
    }

    public function asMaskBlankDate($value)
    {
        if ($value == '0000-00-00') {
            return '--';
        }

        return $value;
    }
}
