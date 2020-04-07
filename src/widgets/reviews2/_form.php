<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.05.2015
 */
/* @var $this yii\web\View */
?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2', 'Display')); ?>
<?= $form->field($model, 'viewFile')->textInput(); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2', 'Pagination')); ?>
<?= $form->field($model, 'enabledPaging')->checkbox([
    'uncheck' => \skeeks\cms\components\Cms::BOOL_N,
    'value'   => \skeeks\cms\components\Cms::BOOL_Y,
]); ?>
<?= $form->field($model, 'enabledPjaxPagination')->checkbox([
    'uncheck' => \skeeks\cms\components\Cms::BOOL_N,
    'value'   => \skeeks\cms\components\Cms::BOOL_Y,
]); ?>
<?= $form->field($model, 'pageSize'); ?>
<?= $form->field($model, 'pageParamName')->textInput(); ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2', 'Sort and count')); ?>
<?= $form->field($model, 'limit'); ?>
<?= $form->fieldSelect($model, 'orderBy', (new \skeeks\cms\reviews2\models\Reviews2Message())->attributeLabels()); ?>
<?= $form->fieldSelect($model, 'order', [
    SORT_ASC => "ASC (" . \Yii::t('skeeks/reviews2', 'from smallest to largest') . ")",
    SORT_DESC => "DESC (" . \Yii::t('skeeks/reviews2', 'from highest to lowest') . ")",
]); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2', 'Settings')); ?>

<?= $form->field($model, 'btnSubmit')->textInput(); ?>
<?= $form->field($model, 'btnSubmitClass')->textInput(); ?>

<?= $form->fieldSetEnd(); ?>
