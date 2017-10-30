<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;


/**
 * User Controller API
 */
class UserController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\User';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['create', 'login', 'options']
        ];

        return $behaviors;
    }

    /**
     * Logs in the user and return it's model
     * @return User
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionLogin()
    {
        $request = Yii::$app->request->post();
        $user = User::findOne(['email' => $request['email']]);
        $mobile = Yii::$app->request->post('mobile');

        if (empty($user))
            throw new NotFoundHttpException('Usuário e/ou senha inválidos');

        if ((!isset($mobile) || empty($mobile)) && $user->user_type != 'administrator')
            throw new NotFoundHttpException('Usuário e/ou senha inválidos');

        if (!Yii::$app->getSecurity()->validatePassword($request['password'], $user->encrypted_password))
            throw new NotFoundHttpException('Usuário e/ou senha inválidos');

        Yii::$app->user->login($user);
        return $user;
    }
}