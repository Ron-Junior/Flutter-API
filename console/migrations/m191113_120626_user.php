<?php

use yii\db\Schema;
use yii\db\Migration;

class m191113_120626_user extends Migration
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
            '{{%user}}',
            [
                'id'=> $this->primaryKey(11),
                'name'=> $this->string(45)->notNull(),
                'email'=> $this->string(45)->notNull(),
                'encrypted_password'=> $this->string(255)->null()->defaultValue(null),
                'access_token'=> $this->string(255)->null()->defaultValue(null),
                'password_reset_token'=> $this->string(255)->null()->defaultValue(null),
                'expiration_date_reset_token'=> $this->string(255)->null()->defaultValue(null),
                'device_id'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->createIndex('fk_user_device_idx','{{%user}}',['device_id'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('fk_user_device_idx', '{{%user}}');
        $this->dropTable('{{%user}}');
    }
}
