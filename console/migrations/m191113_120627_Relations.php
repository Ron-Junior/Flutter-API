<?php

use yii\db\Schema;
use yii\db\Migration;

class m191113_120627_Relations extends Migration
{

    public function init()
    {
       $this->db = 'db';
       parent::init();
    }

    public function safeUp()
    {
        $this->addForeignKey('fk_user_device_id',
            '{{%user}}','device_id',
            '{{%device}}','id',
            'CASCADE','CASCADE'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_user_device_id', '{{%user}}');
    }
}
