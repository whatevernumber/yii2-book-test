<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('catalog');
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->login()) {
            return $this->redirect('catalog');
        }

        $form->password = '';
        return $this->render('login', [
            'login_form' => $form,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout(): \yii\web\Response
    {
        Yii::$app->user->logout();
        return $this->goBack();
    }
}