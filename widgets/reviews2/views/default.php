<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 07.07.2015
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\reviews2\widgets\reviews2\Reviews2Widget */

$model = $widget->modelMessage;
?>


<? if ($widget->enabledPjaxPagination == \skeeks\cms\components\Cms::BOOL_Y) : ?>
    <? \skeeks\cms\modules\admin\widgets\Pjax::begin(); ?>
<? endif; ?>

    <? echo \yii\widgets\ListView::widget([
        'dataProvider'      => $widget->dataProvider,
        'itemView'          => 'review-item',
        'emptyText'          => '',
        'options'           =>
        [
            'tag'   => 'div',
        ],
        'itemOptions' => [
            'tag' => false
        ],
        'layout'            => "\n{items}{$summary}\n<p class=\"row\">{pager}</p>"
    ])?>

<? if ($widget->enabledPjaxPagination == \skeeks\cms\components\Cms::BOOL_Y) : ?>
    <? \skeeks\cms\modules\admin\widgets\Pjax::end(); ?>
<? endif; ?>


<? $form = \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::begin([
    'action'        => \skeeks\cms\helpers\UrlHelper::construct('/reviews2/backend/submit')->toString(),
    'validationUrl' => \skeeks\cms\helpers\UrlHelper::construct('/reviews2/backend/submit')->enableAjaxValidateForm()->toString()
]); ?>

    <?= $form->field($model, 'element_id')->hiddenInput([
        'value' => $widget->cmsContentElement->id
    ])->label(false); ?>

    <? if (\Yii::$app->user->isGuest) : ?>
        <?= $form->field($model, 'user_name')->textInput(); ?>
        <?= $form->field($model, 'user_email')->hint('Email не будет опубликован публично')->textInput(); ?>
    <? endif; ?>
    <?= $form->field($model, 'rating')->radioList(\Yii::$app->reviews2->ratings); ?>
    <?= $form->field($model, 'comments')->textarea([
        'rows' => 5
    ]); ?>


    <?= \yii\helpers\Html::submitButton("" . \Yii::t('app', $widget->btnSubmit), [
        'class' => $widget->btnSubmitClass,
    ]); ?>

<? \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::end(); ?>