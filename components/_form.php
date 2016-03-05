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
    <?= $form->fieldInputInt($model, 'maxValue')->hint('Вы можете указать максимальное значение рейтинга, то есть пользователь будет голосовать, высталяя оценку от 1 до указанного вами значения с шагом 1.'); ?>
    <?= $form->fieldInputInt($model, 'maxCountMessagesForUser'); ?>
    <?= $form->field($model, 'messageSuccessBeforeApproval')->textarea(['rows' => 4]); ?>
    <?= $form->field($model, 'messageSuccess')->textarea(['rows' => 4]); ?>

    <?= $form->fieldSelectMulti($model, 'enabledFieldsOnGuest', [
        'user_name'     => 'Имя пользователя',
        'user_email'    => 'Email пользователя',
        'comments'      => 'Комментарий',
        'dignity'       => 'Достоинства',
        'disadvantages' => 'Недостатки',
        'verifyCode'    => 'Проверочный код',
    ]); ?>
    <?= $form->fieldSelectMulti($model, 'enabledFieldsOnUser', [
        'user_name'     => 'Имя пользователя',
        'user_email'    => 'Email пользователя',
        'comments'      => 'Комментарий',
        'dignity'       => 'Достоинства',
        'disadvantages' => 'Недостатки',
        'verifyCode'    => 'Проверочный код',
    ]); ?>

<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet('Безопасность'); ?>
    <?= $form->fieldRadioListBoolean($model, 'securityEnabledRateLimit'); ?>
    <?= $form->fieldInputInt($model, 'securityRateLimitRequests'); ?>
    <?= $form->fieldInputInt($model, 'securityRateLimitTime'); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet('Связь с элементами'); ?>
    <?= $form->field($model, 'elementPropertyRatingCode')->textInput(); ?>
    <?= $form->field($model, 'elementPropertyCountCode')->textInput(); ?>
<?= $form->fieldSetEnd(); ?>

<?= $form->fieldSet('Уведомления'); ?>
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


<?= $form->fieldSet('Доступ'); ?>

     <? \yii\bootstrap\Alert::begin([
        'options' => [
          'class' => 'alert-warning',
      ],
    ]); ?>
    <b>Внимание!</b> Права доступа сохраняются в режиме реального времени. Так же эти настройки не зависят от сайта или пользователя.
    <? \yii\bootstrap\Alert::end()?>

    <?= \skeeks\cms\widgets\rbac\PermissionForRoles::widget([
        'permissionName'        => \skeeks\cms\reviews2\components\Reviews2Component::PERMISSION_ADD_REVIEW,
        'label'                 => 'Кто может добавлять отзыв на сайте',
    ]); ?>

<?= $form->fieldSetEnd(); ?>



