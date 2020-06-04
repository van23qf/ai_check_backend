<?php
namespace common\components;

use yii;

/**
 * Excel导出操作
 *
 */
class Excel
{
    public static function export($data, $title, $labels = null, $saveAsFile = false)
    {
        if (YII_ENV === 'prod') {
            $client = new \Memcache();
            $client->connect('localhost', 11211);
            $pool = new \Cache\Adapter\Memcache\MemcacheCachePool($client);
            $simpleCache = new \Cache\Bridge\SimpleCache\SimpleCacheBridge($pool);
            \PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()->setCreator(Yii::$app->user->identity->username)
            ->setLastModifiedBy(Yii::$app->user->identity->username)
            ->setSubject($title);

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        $pColumn = 1;
        if ($labels == null) {
            $labels = array_keys($data[0]);
        }

        if (empty($data)) {
            throw new yii\base\UserException('导出数据为空，请检查导出条件');
        }

        $used_labels = array_keys($data[0]);
        foreach ($labels as $attribute => $label) {
            if (in_array($attribute, $used_labels)) {
                $sheet->setCellValueByColumnAndRow($pColumn, 1, $label);
                $pColumn++;
            }
        }

        if (count($data) > 0) {
            foreach ($data as $key => $row) {
                $pColumn = 1;
                foreach ($row as $col) {
                    if (is_numeric($col) && strlen($col) >= 11) { //超长数字如手机号、身份证号，用文本表示
                        $sheet->setCellValueExplicitByColumnAndRow($pColumn, ($key + 2), $col, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    } else {
                        $sheet->setCellValueByColumnAndRow($pColumn, ($key + 2), $col);
                    }

                    $pColumn++;
                }
            }
        }
        $basicData = $sheet->toArray();

        //画边框
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        if (!$saveAsFile) {
            header('Content-Type: application/ms-excel');
            header('Content-Disposition: attachment;filename="' . mb_convert_encoding($title . '.xlsx', 'GBK', 'UTF-8') . '"');
            header('Cache-Control: max-age=0');
        }

        $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $objWriter->save($saveAsFile ? (strtolower(PHP_OS) == 'winnt' ? mb_convert_encoding($saveAsFile, 'GBK', 'UTF-8') : $saveAsFile) : 'php://output');
        die();
    }

    public static function exportByHTML($table, $title, $freezePane = '', $saveAsFile = false)
    {
        //保存到临时文件
        $html = '<?xml encoding="UTF-8"><html><head><title>TEMPLATE</title></head><body>' . $table . '</body></html>';
        $filename = Yii::getAlias('@runtime/temp_html_' . md5($html) . '.html');
        file_put_contents($filename, $html);

        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('HTML');
        $objReader->setReadDataOnly(true);
        $phpExcel = $objReader->load($filename);
        $currentSheet = $phpExcel->getActiveSheet();
        $currentSheet->setTitle($title);
        if ($freezePane) {
            $currentSheet->FreezePane($freezePane);
        }

        if (Yii::$app instanceof yii\web\Application) {
            $phpExcel->getProperties()->setCreator(Yii::$app->user->identity->username)->setLastModifiedBy(Yii::$app->user->identity->username);
        }
        $phpExcel->getProperties()->setSubject($title);
        $phpExcel->removeSheetByIndex(0);
        $phpExcel->addSheet($currentSheet);

        //画边框
        $currentSheet->getStyle('A1:' . $currentSheet->getHighestColumn() . $currentSheet->getHighestRow())
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        if (!$saveAsFile) {
            header('Content-Type: application/ms-excel');
            header('Content-Disposition: attachment;filename="' . mb_convert_encoding($title . '.xlsx', 'GBK', 'UTF-8') . '"');
            header('Cache-Control: max-age=0');
        }

        $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($phpExcel);
        $objWriter->save($saveAsFile ? (strtolower(PHP_OS) == 'winnt' ? mb_convert_encoding($saveAsFile, 'GBK', 'UTF-8') : $saveAsFile) : 'php://output');

        @unlink($filename);
        die();
    }

    /**
     * 导出记录
     * @param  ActiveQuery $query
     * @param  string $title 文件标题前缀
     * @return void
     */
    public static function exportByQuery(\yii\db\ActiveQuery $query, array $map, $title = '')
    {
        $model = new $query->modelClass;
        $labels = [];
        $attributeLabels = $model->attributeLabels();

        foreach ($map as $key => $value) {
            if (is_numeric($key)) {
                $labels[$attributeLabels[$value]] = mb_strpos($attributeLabels[$value], '额') !== false || mb_strpos($attributeLabels[$value], '费') !== false ? 'price' : 'string';
            } else {
                $labels[$attributeLabels[$key]] = mb_strpos($attributeLabels[$key], '额') !== false || mb_strpos($attributeLabels[$key], '费') !== false ? 'price' : 'string';
            }
        }

        $writer = new \XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $labels);
        $query = $query->orderBy(['id' => SORT_DESC]);
        foreach ($query->each() as $row) {
            $model = $query->modelClass::findOne($row['id']);
            $data = [];
            foreach ($map as $attr => $value) {
                if (strpos($value, '.') !== false) {
                    $parts = \explode('.', $value);
                    $val = $model;
                    foreach ($parts as $part) {
                        $val = $val[$part];
                    }
                    $data["{$attr}"] = htmlspecialchars(is_array($val) ? implode('/', $val) : $val);
                    continue;
                }
                $data["{$value}"] = htmlspecialchars(is_array($model[$value]) ? implode('/', $model[$value]) : $model[$value]);
            }
            $row_options = array_fill(0, count($data), array('border' => 'left,right,top,bottom', 'border-style' => 'thin'));
            $writer->writeSheetRow('Sheet1', $data, $row_options);
        }
        $filename = Yii::getAlias('@runtime') . '/output' . time() . rand(1, 1000000) . '.xlsx';
        $writer->writeToFile($filename);

        Yii::$app->response->sendFile($filename, $title.'.xlsx');
        unlink($filename);
        Yii::$app->end();
    }

        /**
     * 导出记录
     * @param  array $data
     * @param  string $title 文件标题前缀
     * @param  array $labels
     * @return void
     */
    public static function exportCustom($data, $title, $labels = null, $end = true)
    {
        $writer = new \XLSXWriter();
        if ($labels) {
            $header_options = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'fill' => '#DDEBF6');
            $writer->writeSheetHeader('Sheet1', $labels, $header_options);
        }
        $row_options = array('border' => 'left,right,top,bottom', 'border-style' => 'thin');
        foreach ($data as $key => $value) {
            $writer->writeSheetRow('Sheet1', $value, $row_options);
        }
        
        $filename = Yii::getAlias('@runtime') . '/output' . time() . rand(1, 1000000) . '.xlsx';
        $writer->writeToFile($filename);

        if ($end) {
            Yii::$app->response->sendFile($filename, $title.'.xlsx');
            @unlink($filename);
            Yii::$app->end();
        } else {
            return $filename;
        }
    }
}
