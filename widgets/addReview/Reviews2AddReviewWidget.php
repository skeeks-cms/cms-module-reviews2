<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 07.07.2015
 */

namespace skeeks\cms\reviews2\widgets\addReview;

use skeeks\cms\base\Widget;
use skeeks\cms\base\WidgetRenderable;
use skeeks\cms\helpers\UrlHelper;
use skeeks\cms\models\CmsContentElement;
use skeeks\cms\reviews2\models\Reviews2Message;
use skeeks\modules\cms\form2\models\Form2Form;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class Reviews2AddReviewWidget
 * @package skeeks\cms\reviews2\widgets\addReview
 */
class Reviews2AddReviewWidget extends WidgetRenderable
{
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => 'Форма добавления отзыва'
        ]);
    }

    public $btnSubmit       = "Добавить отзыв";
    public $btnSubmitClass  = 'btn btn-primary';

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
        [
            'btnSubmit'         => 'Кнопка отправки',
            'btnSubmitClass'    => 'Класс кнопки отправки',
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
        [
            ['btnSubmit', 'string'],
            ['btnSubmitClass', 'string'],
        ]);
    }

    /**
     * @var CmsContentElement
     */
    public $cmsContentElement;

    /**
     * @var Reviews2Message
     */
    public $modelMessage;

    protected function _run()
    {
        if (!$this->cmsContentElement)
        {
            return "Не передан обязательный параметр 'cmsContentElement'";
        }

        $this->modelMessage = new Reviews2Message();

        return parent::_run();
    }

}