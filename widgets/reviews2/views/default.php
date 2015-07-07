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
<? $form = \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::begin([
    'action'        => \skeeks\cms\helpers\UrlHelper::construct('/reviews2/backend/submit')->toString(),
    'validationUrl' => \skeeks\cms\helpers\UrlHelper::construct('/reviews2/backend/submit')->enableAjaxValidateForm()->toString()
]); ?>

    <?= $form->field($model, 'element_id')->hiddenInput([
        'value' => $widget->cmsContentElement->id
    ])->label(false); ?>

    <?= $form->field($model, 'content_id')->hiddenInput([
        'value' => $widget->cmsContentElement->content_id
    ])->label(false); ?>

    <?= $form->field($model, 'rating')->textInput(); ?>
    <?= $form->field($model, 'comments')->textarea([
        'rows' => 5
    ]); ?>


    <?= \yii\helpers\Html::submitButton("" . \Yii::t('app', $widget->btnSubmit), [
        'class' => $widget->btnSubmitClass,
    ]); ?>

<? \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::end(); ?>