<?php

/** @var yii\web\View $this */

/** @var app\models\Book $book */

/** @var app\models\Subscription $subs_form */

/** @var yii\bootstrap5\ActiveForm $form */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html; ?>

<div class="book-page-container">
    <div class="book-page">
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert-failure">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
        <div class="book-title">
            <h1>
                <?= $book->title ?>
            </h1>
        </div>
        <ul class="book-author-list">
            <?php foreach ($book->authors as $key => $author): ?>
                <li class="book-author">
                    <?= $author->name . (array_key_last($book->authors) === $key ? '' : ',&nbsp;' )?>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="book-year">
            <span>
                <?= $book->published_year ?>
            </span>
        </p>

        <div class="book-page-content">
            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="card_control">
                    <a href="<?= \yii\helpers\Url::to(['book/edit/' . $book->id]) ?>">
                        Редактировать
                    </a>
                    <a href="<?= \yii\helpers\Url::to(['book/delete/' . $book->id]) ?>">
                        Удалить
                    </a>
                </div>
            <?php endif; ?>

            <div class="book-page-section">
                <?php if ($book->cover): ?>
                    <div class="book-cover">
                        <img width="400px" src="/web/img/covers/<?= $book->cover ?>"
                             alt="Обложка книги: <?= $book->title ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="book-page-section">
                <p class="book-description">
                    <?= $book->description ?>
                </p>

                <?php $form = ActiveForm::begin([
                    'id' => 'subscription-form',
                    'action' => ['/subscribe'],
                    'method' => 'post',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                        'inputOptions' => ['class' => 'col-lg-3 form-control'],
                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
                ]); ?>

                <?= $form->field($subs_form, 'phone_number')
                    ->textInput(['autofocus' => false, 'type' => 'tel', 'placeholder' => '+7'])
                    ->label('Укажите номер телефона для подписки на новые книги автора:', ['class' => 'phone_number_label'])
                ?>

                <?php foreach ($book->authors as $author): ?>
                    <?= Html::hiddenInput('subs[]', $value = $author->id) ?>
                <?php endforeach; ?>

                <div class="form-group">
                    <div>
                        <?= Html::submitButton('Подписаться', ['class' => 'subscription-button', 'name' => 'subscribe-button']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<style>
    .book-page-container {
        width: 90%;
        margin: 0 auto;
    }

    .book-page {
        padding: 15px;
        width: 100%;
    }

    .book-page-content {
        display: flex;
        flex-direction: row;
        gap: 10px;
        align-items: start;
    }

    .book-page .book-title {
        text-decoration: none;
        color: black;
    }

    .book-page .book-author-list {
        display: flex;
        flex-direction: row;
    }

    .book-page .book-author {
        font-weight: bold;
        font-style: italic;
        margin-bottom: 0;
    }

    .book-year {
        margin-left: 2rem;
        margin-bottom: 0;
        font-size: 18px;
        font-style: italic;
        color: #a6b5cc;
    }

    .book-page .book-cover {
        width: fit-content;
        padding: 15px;
        object-fit: contain;
    }

    .book-page .book-description {
        margin-top: 15px;
        font-size: 20px;
        text-align: justify;
    }

    .book-page #subscription-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        row-gap: 15px;
        padding: 15px;
        background-color: white;
        font-size: 18px;
        border: 1px solid #a6b5cc;
    }

    .book-page .phone_number_label {
        width: 100%;
        margin-bottom: 15px;
    }

    .book-page .subscription-button {
        padding: 10px 20px;
        background-color: #dc3545;
        color: #ffffff;
        border: none;
    }

    .subscription-button:hover {
        background-color: #c9203f;
    }

    .alert {
        min-width: 150px;
        max-width: 600px;
        padding: 20px;
        border-radius: 5px;
        font-size: 18px;
        color: white;
    }

    .alert-success {
        background-color: #93d278;
    }

    .alert-failure {
        background-color: #bb5b87;
    }

    .card_control > a {
        color: #a6b5cc;
        text-decoration: none;
        margin-right: 10px;
    }

    .card_control > a:hover {
        color: #dc3545;
    }
</style>