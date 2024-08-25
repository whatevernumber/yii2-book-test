<?php

namespace app\jobs;

use app\helpers\SMSPilot;
use app\models\Author;
use app\models\Subscription;
use yii\base\BaseObject;

class SMSSubscriptionJob extends BaseObject implements \yii\queue\JobInterface
{
    public int $author_id;
    public string $book_title;

    public function execute($queue)
    {
        $phone_helper = new SMSPilot();
        $subs = Subscription::find()->where(['author_id' => $this->author_id])->all();
        $author = Author::findOne($this->author_id);

        $text = 'Вышла новая книга ' . $this->book_title . 'от ' . $author->name;

        foreach ($subs as $sub) {
            $phone_helper->send($sub->phone_number, $text);
        }
    }
}