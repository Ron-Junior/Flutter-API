<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UnauthorizedHttpException;
use yii\web\BadRequestHttpException;

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
            'except' => ['create', 'login', 'register', 'options']
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
        // $email = $request->get('email');
        $email = $request->post('email');
        $password = $request->post('encrypted_password');
        Yii::debug(print_r($email,true));
        if (empty($email) || empty($password)) {
            throw new UnauthorizedHttpException();
        }

        /** @var User $user */
        $user = User::findOne(['email' => $email]);

        /** @var User $user */

        if (empty($user) || !Yii::$app->getSecurity()->validatePassword($password, $user->encrypted_password)) {
            throw new UnauthorizedHttpException("Usuário e/ou senha inválidos");
        }

        $user->access_token = Yii::$app->getSecurity()->generateRandomString();
        $user->encrypted_password =  Yii::$app->getSecurity()->generatePasswordHash($password);

        if ($user->save()) {
            Yii::$app->user->login($user);
        }

        return $user;
    }

    public function actionRegister()
    {
        $request = Yii::$app->request;

        if (!$request->post('name') ||
            !$request->post('email') ||
            !$request->post('encrypted_password')){
            throw new BadRequestHttpException('Campos obrigatórios precisam ser preenchidos.');
        }

        if (User::find()->where(['email' => $request->post('email')])->one()) {
            throw new BadRequestHttpException('O e-mail informado já foi cadastrado.');
        }

        /** @var User $user */
        $user = new User();
        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->access_token = Yii::$app->getSecurity()->generateRandomString();
        $user->encrypted_password =  Yii::$app->getSecurity()->generatePasswordHash($password);
        
        if (!$user->save()) {
            return $user->getFirstErrors();
        }

        return User::find()->where(['email' => $user->email])->one();
    }

}