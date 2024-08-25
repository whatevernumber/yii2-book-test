<?php

/** @var yii\web\View $this */

/** @var app\models\Book $book */

/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\Book */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = $book->title ? 'Редактировать ' . $book->title : 'Добавить книгу';
?>

<div class="add-book">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'book-form',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($book, 'title')->textInput(['autofocus' => true]) ?>
    <?= $form->field($book, 'isbn')->textInput() ?>
    <?= $form->field($book, 'description')->textarea(['rows' => 6]) ?>

    <?php foreach ($book->authors as $author): ?>
        <div class="field-book-book_authors">
            <?= Html::textInput('book_authors[]', $value = $author->name, ['class' => 'book-book_authors']) ?>
        </div>
    <?php endforeach; ?>
    <div class="field-book-book_authors-new">
        <?= Html::textInput('book_authors[]', '', ['class' => 'book-book_authors', 'placeholder' => 'Автор']) ?>
    </div>
    <div>
        <?php if ($book->getErrors('book_authors')): ?>
            <span class="error_authors">Укажите хотя бы одного автора</span>
        <?php endif; ?>
    </div>

    <div class="form_fields_control">
        <button type="button" id="add_field_button">+</button>
        <button type="button" id="remove_field_button">x</button>
    </div>

    <?= $form->field($book, 'published_year', ['options' => ['class' => 'year_field']])->textInput(['type' => 'number']) ?>

    <?php if ($book->cover): ?>
        <p>При загрузке нового файла обложка будет обновлена</p>
        <img width="50px" height="100px" src="/web/img/covers/<?= $book->cover ?>">
    <?php endif; ?>

    <?= $form->field($book, 'cover_image', ['options' => ['class' => 'new-file']])->fileInput(['multiple' => false])->label(''); ?>

    <div class="form-group">
        <div>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
    #add_field_button,
    #remove_field_button {
        padding: 3px 10px 5px;
        background-color: #ffffff;
        border: 1px solid #dc3545;
        font-weight: bolder;
    }

    .year_field > label {
        width: 100%;
    }

    .error_authors {
        color: #dc3545;
    }
</style>

<?php $this->registerJs(
    <<<JS
        const add_name_button = $('#add_field_button');
        const remove_button = $('#remove_field_button');
        
        add_name_button.click(add_field);
        remove_button.click(remove_field);
        
        function add_field(e) {
            const fieldset = $(this).parent().prev('div');
            const new_field = fieldset.clone();
        
            new_field.find('input').val('');
            new_field.insertAfter(fieldset);
        }
        
        function remove_field (e) {
            const fields_left = document.querySelectorAll('.book-book_authors');
        
            if (fields_left.length > 1) {
                $(this).parent().prev('div').remove();
            } else {
                ($(fields_left[0]).val(''));
            }
        }
    JS
); ?>
