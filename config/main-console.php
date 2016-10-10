<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 07.03.2015
 */
return [
    'components' =>
    [
        'reviews2' =>
        [
            'class'         => '\skeeks\cms\reviews2\components\Reviews2Component',
        ],

        'i18n' => [
            'translations' =>
            [
                'skeeks/reviews2' => [
                    'class'             => 'yii\i18n\PhpMessageSource',
                    'basePath'          => '@skeeks/cms/reviews2/messages',
                    'fileMap' => [
                        'skeeks/reviews2' => 'main.php',
                    ],
                ]
            ]
        ],
    ],



    /*'modules' =>
    [
        'reviews2' => [
            'class'         => '\skeeks\cms\reviews2\Module',
        ]
    ]*/
];