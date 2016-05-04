<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.05.2015
 */
namespace skeeks\cms\reviews2\controllers;

use skeeks\cms\modules\admin\actions\modelEditor\AdminMultiModelEditAction;
use skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelEditAction;
use skeeks\cms\modules\admin\actions\modelEditor\ModelEditorGridAction;
use skeeks\cms\modules\admin\controllers\AdminModelEditorController;
use skeeks\cms\reviews2\models\Reviews2Message;
use skeeks\modules\cms\form2\models\Form2Form;
use yii\helpers\ArrayHelper;

/**
 * Class AdminMessageController
 * @package skeeks\cms\reviews2\controllers
 */
class AdminMessageController extends AdminModelEditorController
{
    public function init()
    {
        $this->name                     = \Yii::t('skeeks/reviews2',"Management reviews");
        $this->modelShowAttribute       = "id";
        $this->modelClassName           = Reviews2Message::className();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(),
            [
                "status-allowed-multi" =>
                [
                    'class' => AdminMultiModelEditAction::className(),
                    "name" => \Yii::t('skeeks/reviews2',"Accept"),
                    //"icon"              => "glyphicon glyphicon-trash",
                    "eachCallback" => [$this, 'eachMultiStatusAllowed'],
                ],

                "status-canceled-multi" =>
                [
                    'class' => AdminMultiModelEditAction::className(),
                    "name" => \Yii::t('skeeks/reviews2',"Cancel"),
                    //"icon"              => "glyphicon glyphicon-trash",
                    "eachCallback" => [$this, 'eachMultiStatusCanceled'],
                ],

                "status-processed-multi" =>
                [
                    'class' => AdminMultiModelEditAction::className(),
                    "name" => \Yii::t('skeeks/reviews2',"In progress"),
                    //"icon"              => "glyphicon glyphicon-trash",
                    "eachCallback" => [$this, 'eachMultiStatusProcessed'],
                ],
            ]
        );
    }


    /**
     * @param $model
     * @param $action
     * @return bool
     */
    public function eachMultiStatusAllowed($model, $action)
    {
        try
        {
            $model->status = Reviews2Message::STATUS_ALLOWED;
            return $model->save(false);
        } catch (\Exception $e)
        {
            return false;
        }
    }
    /**
     * @param $model
     * @param $action
     * @return bool
     */
    public function eachMultiStatusCanceled($model, $action)
    {
        try
        {
            $model->status = Reviews2Message::STATUS_CANCELED;
            return $model->save(false);
        } catch (\Exception $e)
        {
            return false;
        }
    }

    /**
     * @param $model
     * @param $action
     * @return bool
     */
    public function eachMultiStatusProcessed($model, $action)
    {
        try
        {
            $model->status = Reviews2Message::STATUS_PROCESSED;
            return $model->save(false);
        } catch (\Exception $e)
        {
            return false;
        }
    }
}