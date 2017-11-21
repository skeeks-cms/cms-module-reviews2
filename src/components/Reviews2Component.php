<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 03.07.2015
 */

namespace skeeks\cms\reviews2\components;

use skeeks\cms\base\Component;
use skeeks\cms\components\Cms;
use skeeks\cms\controllers\AdminCmsContentElementController;
use skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelFilesAction;
use skeeks\cms\modules\admin\controllers\AdminController;
use skeeks\cms\modules\admin\controllers\events\AdminInitEvent;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;
use yii\widgets\ActiveForm;

/**
 * @proprty array $ratings
 * @proprty array $notifyEmails
 *
 * Class Reviews2Component
 * @package skeeks\cms\reviews2\components
 */
class Reviews2Component extends Component
{
    const PERMISSION_ADD_REVIEW = 'reviews2.add.review';

    public $enabledBeforeApproval = Cms::BOOL_Y;
    public $maxValue = 5;

    public $maxCountMessagesForUser = 1;

    public $elementPropertyRatingCode = "reviews2_rating";
    public $elementPropertyCountCode = "reviews2_count";

    //public $notifyEmails                            = [];
    public $notify_emails = '';
    public $notifyPhones = [];

    public $securityEnabledRateLimit = Cms::BOOL_Y;
    public $securityRateLimitRequests = 10;
    public $securityRateLimitTime = 3600;

    public $messageSuccessBeforeApproval = "";
    public $messageSuccess = "";

    public $enabledFieldsOnUser = ['comments', 'dignity', 'disadvantages'];
    public $enabledFieldsOnGuest = ['comments', 'user_email', 'user_name', 'dignity', 'disadvantages', 'verifyCode'];

    /**
     * Можно задать название и описание компонента
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/reviews2', 'Reviews'),
        ]);
    }

    public function init()
    {
        if (!$this->messageSuccessBeforeApproval) $this->messageSuccessBeforeApproval = \Yii::t('skeeks/reviews2', 'Review successfully added, and will be published at the site after being moderated.');
        if (!$this->messageSuccess) $this->messageSuccess = \Yii::t('skeeks/reviews2', 'Reviewed added successfully, thank you.');
        parent::init();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['enabledBeforeApproval'], 'string'],
            [['maxValue'], 'integer', 'min' => 5, 'max' => 1000],
            [['securityRateLimitRequests'], 'integer'],
            [['securityRateLimitTime'], 'integer'],
            [['elementPropertyRatingCode'], 'string'],
            [['elementPropertyCountCode'], 'string'],
            [['messageSuccessBeforeApproval'], 'string'],
            [['messageSuccess'], 'string'],
            [['notify_emails'], 'string'],
            [['notifyPhones'], 'safe'],
            [['maxCountMessagesForUser'], 'integer'],
            [['enabledFieldsOnGuest'], 'safe'],
            [['enabledFieldsOnUser'], 'safe'],
            [['securityEnabledRateLimit'], 'string'],
            [['enabledBeforeApproval', 'securityEnabledRateLimit'], 'in', 'range' => array_keys(\Yii::$app->cms->booleanFormat())],

            [['notify_emails'], function ($attribute) {
                if ($this->notifyEmails) {
                    foreach ($this->notifyEmails as $email) {
                        $validator = new EmailValidator();

                        if (!$validator->validate($email, $error)) {
                            $this->addError($attribute, $email . ' — ' . \Yii::t('skeeks/reviews2', 'Incorrect email address'));
                            return false;
                        }
                    }
                }

            }],
        ]);
    }


    /**
     * @return array
     */
    public function getNotifyEmails()
    {
        $emailsAll = [];
        if ($this->notify_emails) {
            $emails = explode(",", $this->notify_emails);

            foreach ($emails as $email) {
                $emailsAll[] = trim($email);
            }
        }

        return $emailsAll;
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'enabledBeforeApproval' => \Yii::t('skeeks/reviews2', 'Use reviews pre-moderation'),
            'maxValue' => \Yii::t('skeeks/reviews2', 'Maximum value of rating'),

            'elementPropertyRatingCode' => \Yii::t('skeeks/reviews2', 'Relation rating value with element property'),
            'elementPropertyCountCode' => \Yii::t('skeeks/reviews2', 'Relation reviews amount with element property'),

            'notify_emails' => \Yii::t('skeeks/reviews2', 'Notify emails'),
            'notifyPhones' => \Yii::t('skeeks/reviews2', 'Notify phones'),

            'securityEnabledRateLimit' => \Yii::t('skeeks/reviews2', 'Enable protection by IP'),
            'securityRateLimitRequests' => \Yii::t('skeeks/reviews2', 'Max reviews'),
            'securityRateLimitTime' => \Yii::t('skeeks/reviews2', 'The time for which will be placed the maximum number of user reviews'),

            'messageSuccessBeforeApproval' => \Yii::t('skeeks/reviews2', 'Notice of review successfully added (if the pre-moderation)'),
            'messageSuccess' => \Yii::t('skeeks/reviews2', 'Notice of review successfully added (without pre-moderation)'),

            'enabledFieldsOnGuest' => \Yii::t('skeeks/reviews2', 'Fields in the form of adding a review (not authorized)'),
            'enabledFieldsOnUser' => \Yii::t('skeeks/reviews2', 'Fields in the form of adding a review (user is autorized)'),

            'maxCountMessagesForUser' => \Yii::t('skeeks/reviews2', 'The maximum number of reviews to one article per user (0 - unlimited)'),
        ]);
    }

    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeHints(), [
            'notify_emails' => \Yii::t('skeeks/reviews2', 'You can specify multiple Email addresses (separated by commas), which will be sent notification of new reviews.'),
        ]);
    }


    public function renderConfigForm(ActiveForm $form)
    {
        echo \Yii::$app->view->renderFile(__DIR__ . '/_form.php', [
            'form' => $form,
            'model' => $this
        ], $this);
    }

    /**
     * @return array
     */
    public function getRatings()
    {

        for ($i = 1; $i <= $this->maxValue; $i++) {
            $result[$i] = $i;
        }

        foreach ($result as $key => $value) {
            if (!$value) {
                unset($result[$key]);
            }
        }

        return $result;
    }
}