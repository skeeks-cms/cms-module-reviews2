<?php

use yii\helpers\Html;
use skeeks\cms\modules\admin\widgets\form\ActiveFormUseTab as ActiveForm;
/* @var $this yii\web\View */
/* @var $action \skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelEditAction */
/* @var $model \skeeks\cms\reviews2\models\Reviews2Message */
if (!$model->isNewRecord)
{
    if ($model->status == \skeeks\cms\reviews2\models\Reviews2Message::STATUS_NEW && !$model->processed_by)
    {
        $model->processed_by = \Yii::$app->user->identity->id;
        $model->processed_at = \Yii::$app->formatter->asTimestamp(time());
        $model->status = \skeeks\cms\reviews2\models\Reviews2Message::STATUS_PROCESSED;

        $model->save();
    }
}
?>

<? $form = ActiveForm::begin(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2','Basic information')); ?>

    <?= $form->field($model, 'element_id')->widget(
        \skeeks\cms\modules\admin\widgets\formInputs\CmsContentElementInput::className()
    ); ?>

    <?= $form->field($model, 'rating')->radioList(\Yii::$app->reviews2->ratings); ?>
    <?= $form->field($model, 'comments')->textarea(['rows' => 5]); ?>
    <?= $form->field($model, 'dignity')->textarea(['rows' => 5]); ?>
    <?= $form->field($model, 'disadvantages')->textarea(['rows' => 5]); ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2','Author')); ?>
    <?= $form->fieldSelect($model, 'created_by', \yii\helpers\ArrayHelper::map(
            \skeeks\cms\models\User::find()->active()->all(),
            'id',
            'displayName'
        )) ?>

    <?= $form->field($model, 'user_name')->textInput(); ?>
    <?= $form->field($model, 'user_email')->textInput(); ?>
    <?= $form->field($model, 'user_phone')->textInput(); ?>
    <?= $form->field($model, 'user_city')->textInput(); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2','Processing')); ?>
    <?= $form->fieldSelect($model, 'status', \skeeks\cms\reviews2\models\Reviews2Message::getStatuses()); ?>

    <?= $form->fieldSelect($model, 'processed_by', \yii\helpers\ArrayHelper::map(
            \skeeks\cms\models\User::find()->active()->all(),
            'id',
            'displayName'
        )); ?>

    <?= $form->field($model, 'published_at')->widget(\kartik\datecontrol\DateControl::classname(), [
        //'displayFormat' => 'php:d-M-Y H:i:s',
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
    ]); ?>


<?= $form->fieldSetEnd(); ?>

<? if (!$model->isNewRecord) : ?>
    <?= $form->fieldSet(\Yii::t('skeeks/reviews2','Additional information')); ?>
        <?= \yii\widgets\DetailView::widget([
            'model'         => $model,
            'attributes'    =>
            [
                [
                    'attribute'     => 'id',
                    'label'         => \Yii::t('skeeks/reviews2','Message number'),
                ],

                [
                    'attribute' => 'created_at',
                    'value' => \Yii::$app->formatter->asDatetime($model->created_at, 'medium') . "(" . \Yii::$app->formatter->asRelativeTime($model->created_at) . ")",
                ],

                [
                    'format' => 'raw',
                    'label' => \Yii::t('skeeks/reviews2','Sent from site'),
                    'value' => "<a href=\"{$model->site->url}\" target=\"_blank\" data-pjax=\"0\">{$model->site->name}</a>",
                ],

                [
                    'format' => 'raw',
                    'label' => \Yii::t('skeeks/reviews2','Sender'),
                    'value' => "{$model->createdBy->displayName}",
                ],

                [
                    'attribute' => 'ip',
                    'label' => \Yii::t('skeeks/reviews2','Ip address of the sender'),
                ],

                [
                    'attribute' => 'page_url',
                    'format' => 'raw',
                    'label' => \Yii::t('skeeks/reviews2','Sent from the page'),
                    'value' => Html::a($model->page_url, $model->page_url, [
                        'target' => '_blank',
                        'data-pjax' => 0
                    ])
                ],
            ]
        ]); ?>

    <?= $form->fieldSetEnd(); ?>



    <?= $form->fieldSet(\Yii::t('skeeks/reviews2','For developers')); ?>

    <div class="sx-block">
      <h3><?=\Yii::t('skeeks/reviews2','Additional information that may be useful in some cases, the developers.');?></h3>
      <small><?=\Yii::t('skeeks/reviews2','For the convenience of viewing the data, you can use the service');?>: <a href="http://jsonformatter.curiousconcept.com/#" target="_blank">http://jsonformatter.curiousconcept.com/#</a></small>
    </div>
    <hr />


        <?= \yii\widgets\DetailView::widget([
            'model'         => $model,
            'attributes'    =>
            [
                [
                    'attribute' => 'data_server',
                    'format' => 'raw',
                    'label' => 'SERVER',
                    'value' => "<textarea class='form-control' rows=\"10\">" . \yii\helpers\Json::encode($model->data_server) . "</textarea>"
                ],

                [
                    'attribute' => 'data_cookie',
                    'format' => 'raw',
                    'label' => 'COOKIE',
                    'value' => "<textarea class='form-control' rows=\"5\">" . \yii\helpers\Json::encode($model->data_cookie) . "</textarea>"
                ],

                [
                    'attribute' => 'data_session',
                    'format' => 'raw',
                    'label' => 'SESSION',
                    'value' => "<textarea class='form-control' rows=\"5\">" . \yii\helpers\Json::encode($model->data_session) . "</textarea>"
                ],

                [
                    'attribute' => 'data_request',
                    'format' => 'raw',
                    'label' => 'REQUEST',
                    'value' => "<textarea class='form-control' rows=\"10\">" . \yii\helpers\Json::encode($model->data_request) . "</textarea>"
                ],

            ]
        ]); ?>

    <?= $form->fieldSetEnd(); ?>
<? endif; ?>
<?= $form->buttonsStandart($model); ?>

<? ActiveForm::end(); ?>
