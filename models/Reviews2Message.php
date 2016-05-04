<?php

namespace skeeks\cms\reviews2\models;

use skeeks\cms\captcha\CaptchaValidator;
use skeeks\cms\helpers\Request;
use skeeks\cms\models\behaviors\Serialize;
use skeeks\cms\models\behaviors\TimestampPublishedBehavior;
use skeeks\cms\models\CmsContent;
use skeeks\cms\models\CmsContentElement;
use skeeks\cms\models\CmsSite;
use skeeks\cms\models\CmsUser;
use skeeks\cms\models\User;
use Yii;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%reviews2_message}}".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $processed_at
 * @property integer $processed_by
 *
 * @property integer $element_id
 * @property integer $content_id
 * @property string $dignity
 * @property string $disadvantages
 * @property string $comments
 * @property integer $rating
 * @property integer $status
 * @property string $ip
 * @property string $page_url
 * @property string $data_server
 * @property string $data_session
 * @property string $data_cookie
 * @property string $data_request
 * @property string $site_code
 * @property string $user_name
 * @property string $user_email
 * @property string $user_phone
 * @property string $user_city
 *
 * @property User $processedBy
 * @property CmsContent $content
 * @property User $createdBy
 * @property CmsContentElement $element
 * @property CmsSite $siteCode
 * @property CmsSite $site
 * @property User $updatedBy
 */
class Reviews2Message extends \skeeks\cms\models\Core
{
    public $verifyCode;

    //Сценарий вставки отзыва с сайтовой части + captcha
    const SCENARIO_SITE_INSERT = 'siteInsert';

    const STATUS_NEW            = 0;
    const STATUS_PROCESSED      = 5;
    const STATUS_ALLOWED        = 10;
    const STATUS_CANCELED       = 15;


    /*static public $statuses =
    [
        self::STATUS_NEW                => "Новый",
        self::STATUS_PROCESSED          => "В обработке",
        self::STATUS_ALLOWED            => "Допущен",
        self::STATUS_CANCELED           => "Отклонен",
    ];*/

    static public function getStatuses()
    {
        return [
            self::STATUS_NEW                => \Yii::t('skeeks/reviews2',"New"),
            self::STATUS_PROCESSED          => \Yii::t('skeeks/reviews2',"In Progress"),
            self::STATUS_ALLOWED            => \Yii::t('skeeks/reviews2',"Approved"),
            self::STATUS_CANCELED           => \Yii::t('skeeks/reviews2',"Rejected"),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reviews2_message}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->on(BaseActiveRecord::EVENT_AFTER_INSERT,    [$this, "checkDataAfterSave"]);
        $this->on(BaseActiveRecord::EVENT_AFTER_UPDATE,    [$this, "checkDataAfterSave"]);

        $this->on(BaseActiveRecord::EVENT_BEFORE_INSERT,    [$this, "checkDataBeforeSave"]);
        $this->on(BaseActiveRecord::EVENT_BEFORE_UPDATE,    [$this, "checkDataBeforeSave"]);
    }

    /**
     * После сохранения или обновления рейтинга, нужно обновить элемент.
     *
     * @throws \skeeks\cms\relatedProperties\models\InvalidParamException
     */
    public function checkDataBeforeSave()
    {
        if ($this->element)
        {
            $this->content_id = $this->element->cmsContent->id;
        }
    }
    /**
     * После сохранения или обновления рейтинга, нужно обновить элемент.
     *
     * @throws \skeeks\cms\relatedProperties\models\InvalidParamException
     */
    public function checkDataAfterSave()
    {
        if (!$this->element)
        {
            return;
        }

        $relatedPropertiesModel = $this->element->relatedPropertiesModel;

        if (!$relatedPropertiesModel)
        {
            return;
        }

        //Выбор всех отзывов принятых к этому элементу, для рассчета рейтинга.
        $messages   = static::find()->where(['element_id' => $this->element->id])->andWhere(['status' => static::STATUS_ALLOWED])->all();

        $count              = 0;
        $ratingSumm         = 0;

        /**
         * @var self $message
         */
        foreach ($messages as $message)
        {
            $count ++;
            $ratingSumm = $ratingSumm + $message->rating;
        }

        if (!$count)
        {
            return ;
        }

        $ratingAll = ($ratingSumm / $count);

        if (\Yii::$app->reviews2->elementPropertyCountCode)
        {
            if ($relatedPropertiesModel->hasAttribute(\Yii::$app->reviews2->elementPropertyCountCode))
            {
                $relatedPropertiesModel->setAttribute(\Yii::$app->reviews2->elementPropertyCountCode, $count);
            }
        }

        if (\Yii::$app->reviews2->elementPropertyRatingCode)
        {
            if ($relatedPropertiesModel->hasAttribute(\Yii::$app->reviews2->elementPropertyRatingCode))
            {
                $relatedPropertiesModel->setAttribute(\Yii::$app->reviews2->elementPropertyRatingCode, $ratingAll);
            }
        }

        $relatedPropertiesModel->save();
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

            TimestampPublishedBehavior::className() => TimestampPublishedBehavior::className(),
            Serialize::className() =>
            [
                'class' => Serialize::className(),
                'fields' => ['data_server', 'data_session', 'data_cookie', 'data_request']
            ],

        ]);
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $result                                 = parent::scenarios();
        $result[self::SCENARIO_SITE_INSERT]     = ArrayHelper::merge($result[self::SCENARIO_DEFAULT], [
            'verifyCode'
        ]);
        return $result;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'element_id', 'content_id', 'status'], 'integer'],
            [['element_id', 'rating'], 'required'],
            [['dignity', 'disadvantages', 'comments'], 'string'],
            [['rating'], 'integer', 'min' => 1, 'max' => (int) \Yii::$app->reviews2->maxValue],
            [['ip'], 'string', 'max' => 32],
            [['page_url'], 'string'],
            [['site_code'], 'string', 'max' => 15],
            [['user_name', 'user_email', 'user_phone', 'user_city'], 'string', 'max' => 255],

            [['status'], 'in', 'range' => array_keys(self::getStatuses())],

            ['site_code', 'default', 'value' => \Yii::$app->cms->site->code],

            ['published_at', 'integer'],
            ['processed_at', 'integer'],
            ['processed_by', 'integer'],

            ['user_email', 'email'],

            ['data_request', 'default', 'value' => $_REQUEST],
            ['data_server', 'default', 'value' => $_SERVER],
            ['data_cookie', 'default', 'value' => $_COOKIE],
            ['data_session', 'default', 'value' => function(self $model, $attribute)
            {
                \Yii::$app->session->open();
                return $_SESSION;
            }],
            ['content_id', 'default', 'value' => function(self $model, $attribute)
            {
                return $model->element->cmsContent->id;
            }],
            ['ip', 'default', 'value' => \Yii::$app->request->userIP],

            [
                'verifyCode',
                \yii\captcha\CaptchaValidator::className(),
                'captchaAction' => 'reviews2/backend/captcha',
                'skipOnEmpty'   =>  $this->_skipOnEmptyVerifyCode(),
                'on'            => self::SCENARIO_SITE_INSERT
            ],
        ];
    }

    protected function _skipOnEmptyVerifyCode()
    {
        if (\Yii::$app->user->isGuest && in_array('verifyCode', \Yii::$app->reviews2->enabledFieldsOnGuest))
        {
            return false;
        }

        if (!\Yii::$app->user->isGuest && in_array('verifyCode', \Yii::$app->reviews2->enabledFieldsOnUser))
        {
            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('skeeks/reviews2', 'ID'),
            'created_by' => \Yii::t('skeeks/reviews2', 'Created By'),
            'updated_by' => \Yii::t('skeeks/reviews2', 'Updated By'),
            'created_at' => \Yii::t('skeeks/reviews2', 'Created At'),
            'updated_at' => \Yii::t('skeeks/reviews2', 'Updated At'),
            'published_at' => \Yii::t('skeeks/reviews2', 'Published At'),
            'element_id' => \Yii::t('skeeks/reviews2', 'Element'),
            'content_id' => \Yii::t('skeeks/reviews2', 'Content type'),
            'dignity' => \Yii::t('skeeks/reviews2', 'Dignity'),
            'disadvantages' => \Yii::t('skeeks/reviews2', 'Disadvantages'),
            'comments' => \Yii::t('skeeks/reviews2', 'Comment'),
            'rating' => \Yii::t('skeeks/reviews2', 'Rating'),
            'status' => \Yii::t('skeeks/reviews2', 'Status'),
            'ip' => \Yii::t('skeeks/reviews2', 'Ip'),
            'page_url' => \Yii::t('skeeks/reviews2', 'Page Url'),
            'data_server' => \Yii::t('skeeks/reviews2', 'Data Server'),
            'data_session' => \Yii::t('skeeks/reviews2', 'Data Session'),
            'data_cookie' => \Yii::t('skeeks/reviews2', 'Data Cookie'),
            'data_request' => \Yii::t('skeeks/reviews2', 'Data Request'),
            'site_code' => \Yii::t('skeeks/reviews2', 'Site'),
            'user_name' => \Yii::t('skeeks/reviews2', 'Name'),
            'user_email' => \Yii::t('skeeks/reviews2', 'Email'),
            'user_phone' => \Yii::t('skeeks/reviews2', 'Phone'),
            'user_city' => \Yii::t('skeeks/reviews2', 'City'),
            'processed_at' => \Yii::t('skeeks/reviews2', 'Processed At'),
            'processed_by' => \Yii::t('skeeks/reviews2', 'Processed By'),
            'verifyCode' => \Yii::t('skeeks/reviews2', 'Verification Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessedBy()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'processed_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(CmsContent::className(), ['id' => 'content_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(CmsContentElement::className(), ['id' => 'element_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteCode()
    {
        return $this->hasOne(CmsSite::className(), ['code' => 'site_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(CmsSite::className(), ['code' => 'site_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'updated_by']);
    }




    /**
     * Уведомить всех кого надо и как надо
     */
    public function notifyCreate()
    {
        if (\Yii::$app->reviews2->notifyEmails)
        {
            foreach (\Yii::$app->reviews2->notifyEmails as $email)
            {
                \Yii::$app->mailer->compose('@skeeks/cms/reviews2/mail/new-message', [
                    'model'          => $this
                ])
                ->setFrom([\Yii::$app->cms->adminEmail => \Yii::$app->cms->appName])
                ->setTo($email)
                ->setSubject(\Yii::t('skeeks/reviews2',"Added a new review")." #" . $this->id)
                ->send();
            }
        }
    }
}
