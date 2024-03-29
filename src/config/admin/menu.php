<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 12.03.2015
 */
return [

    'other' =>
        [
            'items' =>
                [
                    [
                        "label" => \Yii::t('skeeks/reviews2', "Reviews"),
                        "img" => ['\skeeks\cms\reviews2\assets\Reviews2Asset', 'icons/reviews.jpg'],

                        'items' =>
                            [
                                [
                                    "label" => \Yii::t('skeeks/reviews2', "Reviews"),
                                    "url" => ["reviews2/admin-message"],
                                    "img" => ['\skeeks\cms\reviews2\assets\Reviews2Asset', 'icons/reviews.jpg'],
                                ],

                                /*[
                                    "label" => \Yii::t('skeeks/reviews2', "Settings"),
                                    "url" => ["cms/admin-settings", "component" => 'skeeks\cms\reviews2\components\Reviews2Component'],
                                    "img" => ['skeeks\cms\assets\CmsAsset', 'images/icons/settings-big.png'],
                                    "activeCallback" => function ($adminMenuItem) {
                                        return (bool)(\Yii::$app->request->getUrl() == $adminMenuItem->getUrl());
                                    },
                                ],*/

                            ]
                    ],
                ]
        ]
];