<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Device;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UnauthorizedHttpException;

/**
 * User Controller API
 */
class DeviceController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Device';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }
}