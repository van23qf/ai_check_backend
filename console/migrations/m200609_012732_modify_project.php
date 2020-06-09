<?php

use yii\db\Migration;

/**
 * Class m200609_012732_modify_project
 */
class m200609_012732_modify_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project','app_name',$this->string(50)->null()->comment('应用名称'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200609_012732_modify_project cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200609_012732_modify_project cannot be reverted.\n";

        return false;
    }
    */
}
