<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author_book}}`.
 */
class m240823_103301_create_author_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_book}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer(),
            'book_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-author',
            'author_book',
            'author_id',
            'authors',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book',
            'author_book',
            'book_id',
            'books',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-unique',
            'author_book',
            ['book_id', 'author_id'],
            true,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('PRAGMA foreign_keys = OFF');
        $this->dropTable('{{%author_book}}');
    }
}
