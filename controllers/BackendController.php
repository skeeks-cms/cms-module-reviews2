<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.05.2015
 */
namespace skeeks\cms\reviews2\controllers;

use skeeks\cms\components\Cms;
use skeeks\cms\helpers\RequestResponse;
use skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelEditAction;
use skeeks\cms\modules\admin\actions\modelEditor\ModelEditorGridAction;
use skeeks\cms\modules\admin\controllers\AdminModelEditorController;
use skeeks\cms\reviews2\models\Reviews2Message;
use skeeks\modules\cms\form2\models\Form2Form;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Class AdminMessageController
 * @package skeeks\cms\reviews2\controllers
 */
class BackendController extends Controller
{
    public function actionSubmit()
    {
        $rr = new RequestResponse();

        $model = new Reviews2Message();

        if ($rr->isRequestOnValidateAjaxForm())
        {
            return $rr->ajaxValidateForm($model);
        }

        if ($rr->isRequestAjaxPost())
        {
            $model->scenario = Reviews2Message::SCENARIO_SITE_INSERT;

            $model->page_url    = \Yii::$app->request->referrer;
            if ($model->load(\Yii::$app->request->post()) && $model->save())
            {
                $rr->success = true;

                if (\Yii::$app->reviews2->enabledBeforeApproval == Cms::BOOL_Y)
                {
                    $rr->message = \Yii::$app->reviews2->messageSuccessBeforeApproval;
                } else
                {
                    $rr->message        = \Yii::$app->reviews2->messageSuccess;

                    //Отключена предмодерация, сразу публикуем
                    $model->status      = Reviews2Message::STATUS_ALLOWED;
                    $model->save();
                }
            } else
            {
                $rr->success = false;
                $rr->message = "Отзыв не добавлен";
            }
        }

        return $rr;
    }
}