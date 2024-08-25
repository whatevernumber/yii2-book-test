<?php
/** @var yii\web\View $this */

/** @var array $years */

/** @var string $year */

/** @var app\models\Author[] $authors */
?>

<div class="top-page">
    <h1 class="top-header">
        Больше всего книг за <span style="color: #dc3545">
            <select id="year_select">
                <?php foreach ($years as $period): ?>
                    <option value="<?= $period['year'] ?>" <?= $period['year'] == $year ? 'selected' : '' ?>>
                        <?= $period['year'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </span> год
    </h1>

    <div class="top-table">
        <table>
            <thead>
            <tr>
                <td>Имя</td>
                <td>Количество книг</td>
            </tr>
            </thead>

            <?php foreach ($authors as $author): ?>
                <tr>
                    <td>
                        <?= $author->name ?>
                    </td>
                    <td>
                        <?= $author->book_count ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<style>
    .top-page {
        height: 100%;
    }

    .top-header {
        margin-top: 15px;
    }

    #year_select {
        margin: 0;
        padding: 5px 10px 10px;
        border-radius: 0;
        border: 1px solid #dc3545;
        outline: none;
        background: transparent;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        font-size: 24px;
        color: #dc3545;
        cursor: pointer;
    }

    #year_select:hover {
        background-color: #fff;
    }

    .top-table {
        margin-top: 50%;
        width: 100%;
    }

    .top-page table {
        width: 100%;
        font-size: 22px;
        border-collapse: collapse;
    }

    .top-page table td {
        padding: 15px;
    }

    .top-page table thead {
        margin-bottom: 15px;
    }

    .top-page table thead td {
        background-color: #a6b5cc;
        color: #ffffff;
        font-weight: bold;
        font-size: 18px;
        border: 1px solid #54585d;
    }

    .top-page table tbody td {
        color: #636363;
        border: 1px solid #dddfe1;
    }

    .top-page table tbody tr {
        background-color: #f9fafb;
    }

    .top-page table tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
</style>

<?php $this->registerJs(
    <<<JS
const select = $('#year_select');
select.on('change', function() {
    window.location.replace("/web/top/" + select.val())
});

JS
); ?>
