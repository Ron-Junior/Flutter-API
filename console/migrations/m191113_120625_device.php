<?php

use yii\db\Schema;
use yii\db\Migration;

class m191113_120625_device extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%device}}',
            [
                'id'=> $this->primaryKey(11),
                'token'=> $this->string(255)->notNull(),
                'system'=> $this->string(45)->notNull(),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%device}}');
    }
}
