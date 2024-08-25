<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subscriptions".
 *
 * @property int $id
 * @property int $phone_number
 * @property int $author_id
 */
class Subscription extends \yii\db\ActiveRecord
{
    public array $subs = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_number'], 'required', 'message' => 'Заполните номер телефона'],
            [['phone_number', 'author_id'], 'integer', 'message' => 'Неверный формат'],
            ['phone_number', 'match', 'pattern' => '/^\+7[\d]{10}$/', 'message' => 'Неверный формат'],
            ['subs', 'each', 'rule' => ['integer']],
            ['phone_number', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone_number' => 'Номер телефона',
            'author_id' => 'Автор',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id']);
    }
}
