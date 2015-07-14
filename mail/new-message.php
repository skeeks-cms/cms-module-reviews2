<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 19.03.2015
 */
use skeeks\cms\mail\helpers\Html;
/**
 * @var $model \skeeks\cms\reviews2\models\Reviews2Message
 */
?>
<?= Html::beginTag('h1'); ?>
    Добавлен новый отзыв #<?= $model->id; ?>
<?= Html::endTag('h1'); ?>

<?= Html::beginTag('p'); ?>
    Отзыв успешно отправлен со страницы: <?= Html::a($model->page_url, $model->page_url); ?><br />
    Дата и время отправки: <?= \Yii::$app->formatter->asDatetime($model->created_at) ?><br />
    Уникальный номер отзыва: <?= $model->id; ?>
<?= Html::endTag('p'); ?>

<?= Html::beginTag('h3'); ?>
    Данные отзыва:
<?= Html::endTag('h3'); ?>

<?= Html::beginTag('p'); ?>


    <?= \yii\widgets\DetailView::widget([
        'model'         => $model,
        'attributes'    =>
        [
            'id',
            'rating',
            'comments',
            'dignity',
            'disadvantages',
        ]
    ])?>

<?= Html::endTag('p'); ?>


<?= Html::beginTag('p'); ?>
    Для управления отзыва используйте инструмент: <?= Html::a('тут', \skeeks\cms\helpers\UrlHelper::construct('reviews2/admin-message/update', ['pk' => $model->id])->enableAdmin()->enableAbsolute()->toString()); ?>.
<?= Html::endTag('p'); ?>