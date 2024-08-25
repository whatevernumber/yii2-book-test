<?php

namespace app\models;

use app\helpers\ISBNHelper;
use Yii;

/**
 * This is the model class for table "Books".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $published_year
 * @property string|null $cover
 */
class Book extends \yii\db\ActiveRecord
{
    public const COVER_PATH = 'img/covers';
    public array $book_authors = [];
    public $cover_image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'published_year', 'isbn'], 'required', 'message' => 'Необходимо заполнить {attribute}'],
            [['published_year'], 'integer', 'min' => '1900'],
            ['isbn', 'unique', 'message' => 'Такая книжка уже есть в системе'],
            ['isbn', 'match', 'pattern' => '/^(?:ISBN(?:-1[03])?:? )?(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$/',
                'message' => 'Неверный формат',
            ],
            ['isbn', 'validateIsbn'],
            [['title'], 'string', 'max' => 255],
            ['cover_image', 'image',
                'maxFiles' => 1,
                'skipOnEmpty' => true,
                'extensions' => ['jpg', 'jpeg'],
                'wrongExtension' => 'Неверный формат файла. Принимаются только картинки с расширением JPG',
            ],
            [['title', 'published_year', 'description', 'cover', 'isbn'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
            'published_year' => 'Год публикации',
            'cover' => 'Обложка',
            'book_authors' => 'Автор',
            'cover_image' => 'Обложка'
        ];
    }

    /**
     * Проверяет isbn
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateIsbn($attribute, $params): void
    {
        $isbnHelper = new ISBNHelper();

        if (!$isbnHelper->checkIsbn($this->isbn)) {
            $this->addError($attribute, 'Некорректный isbn');
        }
    }

    /**
     * Сохраняет обложку
     * @return void
     * @throws \Exception
     */
    public function saveCoverImage(): void
    {
        $name = uniqid('weRead');
        $extension = '.' . $this->cover_image->getExtension();
        if ($this->cover_image->saveAs(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . self::COVER_PATH . DIRECTORY_SEPARATOR . $name . $extension)) {
            $this->cover = $name . $extension;
        } else {
            throw new \Exception('Не удалось записать файл');
        }
    }

    /**
     * Удаляет обложку
     * @return void
     */
    public function deleteCoverImage(): void
    {
        unlink(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . self::COVER_PATH . DIRECTORY_SEPARATOR . $this->cover);
    }

    /**
     * Привязывает автора к книге
     * @param string $name
     * @return void
     * @throws \yii\db\Exception
     */
    public function attachAuthor(string $name): void
    {
        $author = Author::findOne(['name' => $name]);

        if (!$author) {
            $author = new Author();
            $author->name = $name;
            $author->save();
        }

        $this->link('authors', $author);
    }

    /**
     * Отвязывает автора от книги
     * @param string $name
     * @return void
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function detachAuthor(string $name): void
    {
        $author = Author::findOne(['name' => $name]);
        $this->unlink('authors', $author, true);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable('author_book', ['book_id' => 'id']);
    }
}
