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

/**
 * @proprty array $ratings
 *
 * Class Reviews2Component
 * @package skeeks\cms\reviews2\components
 */
class Reviews2Component extends Component
{
    public $enabledBeforeApproval                   = Cms::BOOL_Y;
    public $maxValue                                = 5;

    public $elementPropertyRatingCode               = "reviews2_rating";
    public $elementPropertyCountCode                = "reviews2_count";

    public $notifyEmails                            = [];
    public $notifyPhones                            = [];

    public $securityEnabledRateLimit                = Cms::BOOL_Y;
    public $securityRateLimitRequests               = 10;
    public $securityRateLimitTime                   = 3600;

    /**
     * Можно задать название и описание компонента
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name'          => 'Отзывы',
        ]);
    }

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


    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['enabledBeforeApproval'], 'string'],
            [['maxValue'], 'integer', 'min' => 5, 'max' => 1000],
            [['securityRateLimitRequests'], 'integer'],
            [['securityRateLimitTime'], 'integer'],
            [['elementPropertyRatingCode'], 'string'],
            [['elementPropertyCountCode'], 'string'],
            [['notifyEmails'], 'safe'],
            [['notifyPhones'], 'safe'],
            [['securityEnabledRateLimit'], 'string'],
            [['enabledBeforeApproval', 'securityEnabledRateLimit'], 'in', 'range' => array_keys(\Yii::$app->cms->booleanFormat())],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'enabledBeforeApproval'                 => 'Использовать премодерацию отзывов',
            'maxValue'                              => 'Максимальное значение рейтинга',

            'elementPropertyRatingCode'             => 'Связь значения рейтинга со свойством элемента',
            'elementPropertyCountCode'              => 'Связь количества отзывов со свойством элемента',

            'notifyEmails'                          => 'Email адреса для уведомлений',
            'notifyPhones'                          => 'Телефонные номера для уведомлений',

            'securityEnabledRateLimit'              => 'Включить защиту по IP',
            'securityRateLimitRequests'             => 'Максимальное количество отзывов',
            'securityRateLimitTime'                 => 'Время за которое будет размещено максимальное количество отзывов',
        ]);
    }

    /**
     * @return array
     */
    public function getRatings()
    {
        for($i >= 1; $i <= $this->maxValue; $i ++)
        {
            $result[$i] = $i;
        }

        foreach ($result as $key => $value)
        {
            if (!$value)
            {
                unset($result[$key]);
            }
        }

        return $result;
    }
}