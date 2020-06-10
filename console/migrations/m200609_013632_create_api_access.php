<?php

use yii\db\Migration;

/**
 * Class m200609_013632_create_api_access
 */
class m200609_013632_create_api_access extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('api_access',[
            'id' => $this->primaryKey(),
            'project_id' => $this->integer(10)->comment('项目ID'),
            'api_tag' => $this->string(16)->comment('API标记'),
            'is_enabled' => $this->tinyInteger(1)->defaultValue(1)->comment('是否启用'),
            'expired_at' => $this->dateTime()->comment('过期时间'),
            'created_at' => $this->dateTime()->comment('创建时间'),
            'updated_at' => $this->dateTime()->comment('修改时间'),
        ],' comment "接口权限"');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200609_013632_create_api_access cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200609_013632_create_api_access cannot be reverted.\n";

        return false;
    }
    */
}
