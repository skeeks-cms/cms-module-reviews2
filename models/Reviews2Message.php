<?php

namespace skeeks\cms\reviews2\models;

use Yii;

/**
 * This is the model class for table "{{%reviews2_message}}".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
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
 * @property string $additional_data
 * @property string $site_code
 * @property string $user_name
 * @property string $user_email
 * @property string $user_phone
 * @property string $user_city
 *
 * @property CmsContent $content
 * @property CmsUser $createdBy
 * @property CmsContentElement $element
 * @property CmsSite $siteCode
 * @property CmsUser $updatedBy
 */
class Reviews2Message extends \skeeks\cms\models\Core
{
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
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'element_id', 'content_id', 'rating', 'status'], 'integer'],
            [['element_id', 'content_id', 'rating'], 'required'],
            [['dignity', 'disadvantages', 'comments', 'data_server', 'data_session', 'data_cookie', 'data_request', 'additional_data'], 'string'],
            [['ip'], 'string', 'max' => 32],
            [['page_url'], 'string', 'max' => 500],
            [['site_code'], 'string', 'max' => 15],
            [['user_name', 'user_email', 'user_phone', 'user_city'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'element_id' => Yii::t('app', 'Element ID'),
            'content_id' => Yii::t('app', 'Content ID'),
            'dignity' => Yii::t('app', 'Dignity'),
            'disadvantages' => Yii::t('app', 'Disadvantages'),
            'comments' => Yii::t('app', 'Comments'),
            'rating' => Yii::t('app', 'Rating'),
            'status' => Yii::t('app', 'Status'),
            'ip' => Yii::t('app', 'Ip'),
            'page_url' => Yii::t('app', 'Page Url'),
            'data_server' => Yii::t('app', 'Data Server'),
            'data_session' => Yii::t('app', 'Data Session'),
            'data_cookie' => Yii::t('app', 'Data Cookie'),
            'data_request' => Yii::t('app', 'Data Request'),
            'additional_data' => Yii::t('app', 'Additional Data'),
            'site_code' => Yii::t('app', 'Site Code'),
            'user_name' => Yii::t('app', 'User Name'),
            'user_email' => Yii::t('app', 'User Email'),
            'user_phone' => Yii::t('app', 'User Phone'),
            'user_city' => Yii::t('app', 'User City'),
        ];
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
    public function getUpdatedBy()
    {
        return $this->hasOne(CmsUser::className(), ['id' => 'updated_by']);
    }
}
