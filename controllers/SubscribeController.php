<?php

namespace app\controllers;

use app\models\Author;
use app\models\Subscription;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

class SubscribeController extends Controller
{
    /**
     * @return \yii\web\Response
     * @throws Exception
     */
    public function actionSubscribe(): \yii\web\Response
    {
        $subscription = new Subscription();
        $subscription->load(Yii::$app->request->post());

        if ($subscription->validate()) {
            foreach (Yii::$app->request->post('subs') as $id) {
                $author = Author::findOne($id);

                if (!$author) {
                    throw new Exception('Такого автора не существует');
                }

                $sub = new Subscription();
                $sub->phone_number = preg_replace('[\D]', '', $subscription->phone_number);
                $sub->author_id = $author->id;

                try {
                    $sub->save(false);
                    Yii::$app->session->setFlash('success', "Подписка на новые книги автора " . $author->name . " успешна!");
                } catch (\Exception $exception) {
                    Yii::$app->session->setFlash('error', "Вы уже подписаны на книги автора " . $author->name);
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}