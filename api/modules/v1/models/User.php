<?php

namespace api\modules\v1\models;
use yii\web\IdentityInterface;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property string $access_token
 * @property string $password_reset_token
 * @property string $expiration_date_reset_token
 * @property int $device_id
 *
 * @property Device $device
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'encrypted_password', 'access_token'], 'required'],
            [['device_id'], 'integer'],
            [['name', 'email'], 'string', 'max' => 45],
            [['encrypted_password', 'access_token', 'password_reset_token', 'expiration_date_reset_token'], 'string', 'max' => 255],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'encrypted_password' => 'Encrypted Password',
            'access_token' => 'Access Token',
            'password_reset_token' => 'Password Reset Token',
            'expiration_date_reset_token' => 'Expiration Date Reset Token',
            'device_id' => 'Device ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }
}
