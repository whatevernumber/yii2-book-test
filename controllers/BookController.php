<?php

namespace app\controllers;

use app\jobs\SMSSubscriptionJob;
use app\models\Book;
use app\models\Subscription;
use Yii;
use yii\data\Pagination;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'get'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['add', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $booksQuery = Book::find()->with('authors');
        $pages = new Pagination(
            [
                'totalCount' => $booksQuery->count(),
                'pageSize' => 5,
            ]
        );
        $books = $booksQuery->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('catalog', [
            'books' => $books,
            'pages' => $pages,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function actionGet(int $id): string
    {
        $subscriptionForm = new Subscription();
        if (!$id) {
            throw new \Exception('ID не передан');
        }
        $book = Book::find()->with('authors')->where(['id' => $id])->one();

        if (!$book) {
            throw new \Exception('Такой книги не существует');
        }

        return $this->render('book', [
            'book' => $book,
            'subs_form' => $subscriptionForm
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionAdd(int $id): string
    {
        $new = false;

        if ($id) {
            $book = Book::find()->with('authors')->where(['id' => $id])->one();
        } else {
            $book = new Book();
            $new = true;
        }

        if ($this->request->getIsPost()) {

            $book->load($this->request->post());
            $cover = UploadedFile::getInstance($book,'cover_image');
            $book->cover_image = $cover;
            $newAuthors = array_filter($this->request->post('book_authors'));

            if (!empty($newAuthors) && $book->validate()) {
                // Обработка фотографий
                if ($book->cover_image) {
                    if ($book->cover) {
                        $book->deleteCoverImage();
                    }
                    $book->saveCoverImage();
                }

                $book->save(false);

                // Обработка авторов
                if ($book->authors) {

                    $oldAuthors = ArrayHelper::getColumn($book->authors, 'name');

                    // если убрали одного из сохранённых авторов
                    if ($diff = array_diff($oldAuthors, $newAuthors)) {
                        foreach ($diff as $author) {
                            $book->detachAuthor($author);
                        }
                    }

                    // если добавили нового автора
                    if ($diff = array_diff($newAuthors, $oldAuthors)) {
                        foreach ($diff as $author) {
                            $book->attachAuthor($author);
                        }
                    }
                } else {
                    foreach ($newAuthors as $author) {
                        $book->attachAuthor($author);
                    }
                }

                // Оповещение подписчиков при создании новой книги
                if ($new) {
                    foreach ($book->authors as $author) {
                        $subs = Subscription::find()->where(['author_id' => $author->id])->exists();
                        if ($subs) {
                            Yii::$app->queue->push(new SMSSubscriptionJob([
                                'author_id' => $author->id,
                                'book_title' => $book->title,
                            ]));
                        }
                    }
                }
                $this->redirect('/web/book/' . $book->id);
            } else {
                if (empty($newAuthors)) {
                    $book->addError('book_authors', 'Поле авторов не должно быть пустым');
                }
            }
        }

        return $this->render('form', [
            'book' => $book ?? null,
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id): \yii\web\Response
    {
        if (!$id) {
           throw new Exception('Не передан параметр');
        }

        $book = Book::find()->with('authors')->where(['id' => $id])->one();

        if ($book->cover) {
            $book->deleteCoverImage();
        }

        if (!$book) {
            throw new Exception('Такой книги не существует');
        }

        $book->delete();
        return $this->redirect('/web/catalog');
    }
}