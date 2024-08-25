<?php
/** @var yii\web\View $this */

/** @var app\models\Book[] $books */

/** @var yii\data\Pagination $pages */

use yii\widgets\LinkPager;
?>

<div class="book_catalog">
    <?php foreach ($books as $book): ?>
        <div class="book_card">
            <div class="book_title">
                <a href="<?= \yii\helpers\Url::to('book/' . $book->id) ?>">
                    <h2>
                        <?= $book->title ?>
                    </h2>
                </a>
            </div>

            <div class="book_year">
                <span>
                    <?= $book->published_year ?>
                </span>
            </div>
            <div class="card_content">
                <div class="card_section">
                    <?php if ($book->cover): ?>
                    <div class="book_cover">
                        <a href="<?= \yii\helpers\Url::to('book/' . $book->id) ?>">
                            <img width="250px" height="250px" src="/web/img/covers/<?= $book->cover ?>" alt="Обложка книги: <?= $book->title ?>">
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card_section">
                    <ul class="book_author_list">
                        <?php foreach ($book->authors as $key => $author): ?>
                            <li class="book_author">
                                <?= $author->name . (array_key_last($book->authors) === $key ? '' : ',&nbsp;' )?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <p>
                        <?= $book->description ?>
                    </p>
                </div>
            </div>

            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="card_control">
                    <a href="<?= \yii\helpers\Url::to(['book/edit/' . $book->id]) ?>">
                        Редактировать
                    </a>
                    <a href="<?= \yii\helpers\Url::to(['book/delete' . $book->id]) ?>">
                        Удалить
                    </a>
                </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
</div>

<?php

echo LinkPager::widget([
        'pagination' => $pages,
    ]);
?>

<style>
    .book_catalog {
        padding: 15px;
        width: 75%;
        display: flex;
        flex-direction: column;
        row-gap: 15px;
        background-color: rgba(166, 181, 204, 0.1);
        border-radius: 10px;
    }

    .book_card .card_content {
        display: flex;
        flex-direction: row;
        gap: 10px;
        align-items: center;
    }

    .book_card {
        display: flex;
        flex-direction: column;
        width: 100%;
        padding: 15px;
        border: 1px solid #a6b5cc;
        border-radius: 5px;
        text-align: justify;
        box-shadow: 2px 2px 4px 4px rgba(166, 181, 204, 0.25);
    }

    .book_card .book_title {
        padding-left: 15px;
        transition-duration: 200ms;
    }

    .book_title > a {
        color: inherit;
        text-decoration: none;
    }

    .book_title > a > h2 {
        margin-bottom: 0;
    }

    .book_title:hover {
        color: #dc3545;
        position: relative;
    }

    .book_title:hover::before {
        content: "";
        display: flex;
        width: fit-content;
        height: fit-content;
        position: absolute;
        left: -5px;
        top: 15px;
        border: solid #dc3545;
        border-width: 0 3px 3px 0;
        padding: 3px;
        transform: rotate(-45deg);
    }

    .book_title:active::before {
        transform: rotate(45deg);
    }

    .book_card .book_year {
        margin-left: 2rem;
        margin-bottom: 0;
        font-size: 18px;
        font-style: italic;
        color: #a6b5cc;
    }

    .book_card .book_cover {
        padding: 15px;
    }

    .book_cover img {
        width: fit-content;
        object-fit: contain;
        box-shadow: -4px 4px 4px 4px #a6b5cc;
    }

    .book_card .book_author_list {
        display: flex;
        flex-direction: row;
    }

    .book_card .book_author {
        font-weight: bold;
        font-style: italic;
        margin-bottom: 2rem;
    }

    .book_card .card_control > a {
        color: #a6b5cc;
        text-decoration: none;
        margin-right: 10px;
    }

    .book_card .card_control > a:hover {
        color: #dc3545;
    }
</style>
