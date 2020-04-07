<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.06.2015
 */
return [
    'components' => [
        'reviews2' => [
            'class' => '\skeeks\cms\reviews2\components\Reviews2Component',
        ],

        'i18n' => [
            'translations' => [
                'skeeks/reviews2' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@skeeks/cms/reviews2/messages',
                    'fileMap'  => [
                        'skeeks/reviews2' => 'main.php',
                    ],
                ],
            ],
        ],

        'authManager' => [
            'config' => [
                'permissions' => [
                    [
                        'name'        => \skeeks\cms\reviews2\components\Reviews2Component::PERMISSION_ADD_REVIEW,
                        'description' => ['skeeks/reviews2', 'Adding reviews'],
                    ],
                ],
            ],
        ],
    ],
];