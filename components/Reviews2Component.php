<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 03.07.2015
 */
namespace skeeks\cms\reviews2\components;
use skeeks\cms\base\Component;
use skeeks\cms\controllers\AdminCmsContentElementController;
use skeeks\cms\modules\admin\actions\modelEditor\AdminOneModelFilesAction;
use skeeks\cms\modules\admin\controllers\AdminController;
use skeeks\cms\modules\admin\controllers\events\AdminInitEvent;
use yii\helpers\ArrayHelper;

/**
 * Class Reviews2Component
 * @package skeeks\cms\reviews2\components
 */
class Reviews2Component extends Component
{
    public function init()
    {
        parent::init();

        \Yii::$app->on(AdminController::EVENT_INIT, function (AdminInitEvent $e) {

            if ($e->controller instanceof AdminCmsContentElementController)
            {
                $e->controller->eventActions = ArrayHelper::merge($e->controller->eventActions, [
                    'reviews2' =>
                        [
                            'class'         => AdminOneModelFilesAction::className(),
                            'name'          => 'Отзывы',
                            'priority'      => 1000,
                        ],
                ]);
            }
        });
    }
}