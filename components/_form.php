<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 08.07.2015
 */
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\reviews2\components\Reviews2Component */
?>

<?= $form->fieldSet('Основное'); ?>
    <?= $form->fieldRadioListBoolean($model, 'enabledBeforeApproval'); ?>
    <?= $form->fieldInputInt($model, 'maxValue')->hint(\Yii::t('skeeks/reviews2','You can specify the maximum rating value, ie the user will vote, exposing a rating from 1 to the value you specified in steps of 1.')); ?>
    <?= $form->fieldInputInt($model, 'maxCountMessagesForUser'); ?>
    <?= $form->field($model, 'messageSuccessBeforeApproval')->textarea(['rows' => 4]); ?>
    <?= $form->field($model, 'messageSuccess')->textarea(['rows' => 4]); ?>

    <?= $form->fieldSelectMulti($model, 'enabledFieldsOnGuest', [
        'user_name'     => \Yii::t('skeeks/reviews2','Username'),
        'user_email'    => \Yii::t('skeeks/reviews2','User Email'),
        'comments'      => \Yii::t('skeeks/reviews2','Comment'),
        'dignity'       => \Yii::t('skeeks/reviews2','Dignity'),
        'disadvantages' => \Yii::t('skeeks/reviews2','Disadvantages'),
        'verifyCode'    => \Yii::t('skeeks/reviews2','Verify code'),
    ]); ?>
    <?= $form->fieldSelectMulti($model, 'enabledFieldsOnUser', [
        'user_name'     => \Yii::t('skeeks/reviews2','Username'),
        'user_email'    => \Yii::t('skeeks/reviews2','User Email'),
        'comments'      => \Yii::t('skeeks/reviews2','Comment'),
        'dignity'       => \Yii::t('skeeks/reviews2','Dignity'),
        'disadvantages' => \Yii::t('skeeks/reviews2','Disadvantages'),
        'verifyCode'    => \Yii::t('skeeks/reviews2','Verification Code'),
    ]); ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2','Security')); ?>
    <?= $form->fieldRadioListBoolean($model, 'securityEnabledRateLimit'); ?>
    <?= $form->fieldInputInt($model, 'securityRateLimitRequests'); ?>
    <?= $form->fieldInputInt($model, 'securityRateLimitTime'); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2','Contact elements')); ?>
    <?= $form->field($model, 'elementPropertyRatingCode')->textInput(); ?>
    <?= $form->field($model, 'elementPropertyCountCode')->textInput(); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet(\Yii::t('skeeks/reviews2','Notice')); ?>
    <?= $form->field($model, 'notifyEmails')->widget(
            \skeeks\cms\widgets\formInputs\EditedSelect::className(),
            [
                'controllerRoute' => 'cms/admin-user-email',
                'items' => \yii\helpers\ArrayHelper::map(
                    \skeeks\cms\models\CmsUserEmail::find()->all(),
                    'value',
                    'value'
                ),
                'multiple' => true
            ]
    ); ?>
    <?= $form->field($model, 'notifyPhones')->widget(
        \skeeks\cms\widgets\formInputs\EditedSelect::className(),
            [
            'controllerRoute' => 'cms/admin-user-phone',
            'items' => \yii\helpers\ArrayHelper::map(
                \skeeks\cms\models\CmsUserPhone::find()->all(),
                'value',
                'value'
            ),
            'multiple' => true
        ]
); ?>
<?= $form->fieldSetEnd(); ?>


<?= $form->fieldSet(\Yii::t('skeeks/reviews2','Access')); ?>

     <? \yii\bootstrap\Alert::begin([
        'options' => [
          'class' => 'alert-warning',
      ],
    ]); ?>
    <b><?=\Yii::t('skeeks/reviews2','Attention!');?></b> <?=\Yii::t('skeeks/reviews2','Permissions are stored in real time. Thus, these settings are independent of site or user.');?>
    <? \yii\bootstrap\Alert::end()?>

    <?= \skeeks\cms\widgets\rbac\PermissionForRoles::widget([
        'permissionName'        => \skeeks\cms\reviews2\components\Reviews2Component::PERMISSION_ADD_REVIEW,
        'label'                 => \Yii::t('skeeks/reviews2','Who can add a review on the site'),
    ]); ?>

<?= $form->fieldSetEnd(); ?>



