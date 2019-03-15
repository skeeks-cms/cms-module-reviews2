<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 07.07.2015
 */

namespace skeeks\cms\reviews2\widgets\reviews2;

use skeeks\cms\base\Widget;
use skeeks\cms\base\WidgetRenderable;
use skeeks\cms\components\Cms;
use skeeks\cms\helpers\UrlHelper;
use skeeks\cms\models\CmsContentElement;
use skeeks\cms\models\Search;
use skeeks\cms\reviews2\models\Reviews2Message;
use skeeks\modules\cms\form2\models\Form2Form;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/**
 * Class FormWidget
 * @package skeeks\cms\cmsWidgets\text
 */
class Reviews2Widget extends WidgetRenderable
{
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/reviews2', 'Widget reviews')
        ]);
    }


    //Навигация
    public $enabledPaging = CMS::BOOL_Y;
    public $enabledPjaxPagination = CMS::BOOL_Y;

    public $pageSize = 100;
    public $pageParamName = 'review2';

    //Сортировка
    public $orderBy = "published_at";
    public $order = SORT_DESC;

    //Дополнительные настройки
    public $label = "";

    //Условия для запроса
    public $limit = 0;
    public $statuses = [Reviews2Message::STATUS_ALLOWED];
    public $site_codes = [];
    public $createdBy = [];


    public $btnSubmit = "";
    public $btnSubmitClass = 'btn btn-primary';

    public function init()
    {
        if (!$this->btnSubmit) $this->btnSubmit = \Yii::t('skeeks/reviews2', 'Add a review');
        parent::init();
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),
            [
                'btnSubmit' => \Yii::t('skeeks/reviews2', 'The inscription on the button to send the form'),
                'btnSubmitClass' => \Yii::t('skeeks/reviews2', 'Class form submit button'),


                'enabledPaging' => \Yii::t('skeeks/reviews2', 'Enable pagination'),
                'enabledPjaxPagination' => \Yii::t('skeeks/reviews2', 'Enable ajax navigation'),
                'pageParamName' => \Yii::t('skeeks/reviews2', 'Names of pages parameter for pagination'),
                'pageSize' => \Yii::t('skeeks/reviews2', 'Number of records on one page'),

                'orderBy' => \Yii::t('skeeks/reviews2', 'Order by'),
                'order' => \Yii::t('skeeks/reviews2', 'Sorting direction'),

                'label' => \Yii::t('skeeks/reviews2', 'Label'),

                'limit' => \Yii::t('skeeks/reviews2', 'The maximum number of entries in the sample (limit)'),
                'statuses' => \Yii::t('skeeks/reviews2', 'Consider statuses'),
                'site_codes' => \Yii::t('skeeks/reviews2', 'Consider sites'),
                'createdBy' => \Yii::t('skeeks/reviews2', 'Created By'),

            ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [

                ['btnSubmit', 'string'],
                ['btnSubmitClass', 'string'],

                ['enabledPaging', 'string'],
                ['enabledPjaxPagination', 'string'],
                ['pageParamName', 'string'],
                ['pageSize', 'string'],

                ['orderBy', 'string'],
                ['order', 'integer'],

                ['label', 'string'],

                ['limit', 'integer'],
                ['statuses', 'safe'],
                ['site_codes', 'safe'],
                ['createdBy', 'safe'],


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
     * @var CmsContentElement
     */
    public $cmsContentElement;

    /**
     * @var Reviews2Message
     */
    public $modelMessage;

    public function run()
    {
        $this->initDataProvider();

        if ($this->createdBy) {
            $this->dataProvider->query->andWhere([Reviews2Message::tableName() . '.created_by' => $this->createdBy]);
        }

        if ($this->site_codes) {
            $this->dataProvider->query->andWhere([Reviews2Message::tableName() . '.site_code' => $this->site_codes]);
        }

        if ($this->statuses) {
            $this->dataProvider->query->andWhere([Reviews2Message::tableName() . '.status' => $this->statuses]);
        }

        if ($this->cmsContentElement) {
            $this->dataProvider->query->andWhere([Reviews2Message::tableName() . '.element_id' => $this->cmsContentElement->id]);
        }

        if ($this->limit) {
            $this->dataProvider->query->limit($this->limit);
        }

        $this->modelMessage = new Reviews2Message();

        return parent::run();
    }


    /**
     * @var ActiveDataProvider
     */
    public $dataProvider = null;

    /**
     * @var Search
     */
    public $search = null;

    public function initDataProvider()
    {
        $this->search = new Search(Reviews2Message::className());
        $this->dataProvider = $this->search->getDataProvider();

        if ($this->enabledPaging == Cms::BOOL_Y) {
            $this->dataProvider->getPagination()->defaultPageSize = $this->pageSize;
            $this->dataProvider->getPagination()->pageParam = $this->pageParamName;
        } else {
            $this->dataProvider->pagination = false;
        }

        if ($this->orderBy) {
            $this->dataProvider->getSort()->defaultOrder =
                [
                    $this->orderBy => (int)$this->order
                ];
        }

        return $this;
    }
}