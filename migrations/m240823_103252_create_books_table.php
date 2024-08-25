<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m240823_103252_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'isbn' => $this->string()->unique()->notNull(),
            'description' => $this->text(),
            'published_year' => $this->smallInteger()->notNull(),
            'cover' => $this->string(),
        ]);

        $this->createIndex('idx-year', 'books', ['published_year']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
