<?php

namespace app\controllers;

use app\models\Author;
use yii\db\Exception;
use yii\web\Controller;

class AuthorController extends Controller
{
    /**
     * @param int $year
     * @return string
     * @throws Exception
     */
    public function actionTop(int $year): string
    {
        if (!$year) {
            throw new Exception('Необходимо указать год');
        }

        $authors = Author::find()->select(['name', 'COUNT(author_book.book_id) AS book_count'])
            ->join('RIGHT JOIN', 'author_book', 'author_book.author_id = authors.id')
            ->join('LEFT JOIN', 'books', 'author_book.book_id = books.id')
            ->where(['books.published_year' => $year])
            ->groupBy('name')
            ->orderBy('book_count DESC')
            ->limit(10)
            ->all();

        $years = (new \yii\db\Query())->select('published_year AS year')->from('books')
            ->groupBy('published_year')->orderBy('published_year DESC')
            ->all();

        return $this->render('top', [
            'authors' => $authors,
            'year' => $year,
            'years' => $years,
        ]);
    }
}