<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.06.2015
 */
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<? $pjax = \skeeks\cms\modules\admin\widgets\Pjax::begin(); ?>

    <?php echo $this->render('_search', [
        'searchModel'   => $searchModel,
        'dataProvider'  => $dataProvider
    ]); ?>

<?= \skeeks\cms\modules\admin\widgets\GridViewStandart::widget([
    'dataProvider'      => $dataProvider,
    'filterModel'       => $searchModel,
    'adminController'   => $controller,
    'pjax'              => $pjax,
    'isOpenNewWindow'   => @$isOpenNewWindow ? true : false,
    'columns' => [
        [
            'attribute' => 'status',
            'class' => \yii\grid\DataColumn::className(),
            'filter' => \skeeks\cms\reviews2\models\Reviews2Message::getStatuses(),
            'format' => 'raw',
            'value' => function(\skeeks\cms\reviews2\models\Reviews2Message $model)
            {
                if ($model->status == \skeeks\cms\reviews2\models\Reviews2Message::STATUS_NEW)
                {
                    $class = "default";
                } else if ($model->status == \skeeks\cms\reviews2\models\Reviews2Message::STATUS_PROCESSED)
                {
                    $class = "warning";
                }  else if ($model->status == \skeeks\cms\reviews2\models\Reviews2Message::STATUS_CANCELED)
                {
                    $class = "danger";
                } else if ($model->status == \skeeks\cms\reviews2\models\Reviews2Message::STATUS_ALLOWED)
                {
                    $class = "success";
                }

                return '<span class="label label-' . $class . '">' . \yii\helpers\ArrayHelper::getValue(\skeeks\cms\reviews2\models\Reviews2Message::getStatuses(), $model->status) . '</span>';
            }
        ],


        [
            'class' => \skeeks\cms\grid\CreatedAtColumn::className(),
            'label' => \Yii::t('skeeks/reviews2','Added')
        ],
        [
            'class' => \skeeks\cms\grid\CreatedByColumn::className(),
        ],

        [
            'class' => \skeeks\cms\grid\SiteColumn::className(),
        ],

        [
            'attribute' => 'element_id',
            'relation' => 'element',
            'class' => \skeeks\cms\grid\CmsContentElementColumn::className(),
        ],

        [
            'filter' => \skeeks\cms\models\CmsContent::getDataForSelect(),
            'attribute' => 'content_id',
            'class' => \yii\grid\DataColumn::className(),
            'value' => function(\skeeks\cms\reviews2\models\Reviews2Message $model)
            {
                return ($model->element && $model->element->cmsContent) ? $model->element->cmsContent->name : '-';
            }
        ],

        [
            'filter' => \Yii::$app->reviews2->ratings,
            'attribute' => 'rating',
            'class' => \yii\grid\DataColumn::className(),

        ],
        /*'comments',*/
        /*'user_name',
        'user_email',
        'user_phone',
        'user_city'*/

    ],
]); ?>

<? $pjax::end(); ?>

