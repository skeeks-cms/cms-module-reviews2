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
    <?=\Yii::t('skeeks/reviews2','Added a new review');?> #<?= $model->id; ?>
<?= Html::endTag('h1'); ?>

<?= Html::beginTag('p'); ?>
    <?=\Yii::t('skeeks/reviews2','Review submitted successfully from the page');?>: <?= Html::a($model->page_url, $model->page_url); ?><br />
    <?=\Yii::t('skeeks/reviews2','Date and time of sending');?>: <?= \Yii::$app->formatter->asDatetime($model->created_at) ?><br />
    <?=\Yii::t('skeeks/reviews2','Unique review number');?>: <?= $model->id; ?>
<?= Html::endTag('p'); ?>

<?= Html::beginTag('h3'); ?>
    <?=\Yii::t('skeeks/reviews2','Data review');?>:
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
    <?=\Yii::t('skeeks/reviews2','Use tool for manage review ');?>: <?= Html::a(\Yii::t('skeeks/reviews2','here'), \skeeks\cms\helpers\UrlHelper::construct('reviews2/admin-message/update', ['pk' => $model->id])->enableAdmin()->enableAbsolute()->toString()); ?>.
<?= Html::endTag('p'); ?>