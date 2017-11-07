<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UnauthorizedHttpException;

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
     * @throws UnauthorizedHttpException
     */
    public function actionLogin()
    {
        $request = Yii::$app->request;
        $email = $request->post('email');
        $password = $request->post('password');

        if (empty($email) || empty($password)) {
            throw new UnauthorizedHttpException();
        }

        /** @var User $user */
        $user = User::findOne(['email' => $email]);

        if (!empty($user)) {
            // On DEV environment we can login with any account using any password.
            $hasInvalidPassword = (!YII_ENV_DEV && !Yii::$app->getSecurity()->validatePassword($password, $user->encrypted_password));

            if ($hasInvalidPassword) {
                throw new UnauthorizedHttpException();
            }
        }

        $user->access_token = Yii::$app->getSecurity()->generateRandomString();

        if ($user->save()) {
            Yii::$app->user->login($user);
        }

        return $user;
    }
}