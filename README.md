Reviews for content elements
===================================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist skeeks/cms-module-reviews2 "*"
```

or add

```
"skeeks/cms-module-reviews2": "*"
```

Configuration app
----------

```php

'components' =>
[
    'reviews2' => [
        'class'         => '\skeeks\cms\reviews2\components\Reviews2Component',
    ]
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
'modules' =>
[
    'reviews2' => [
        'class'         => '\skeeks\cms\reviews2\Module',
    ]
]

```

##Links
* [Web site](http://en.cms.skeeks.com)
* [Web site (rus)](http://cms.skeeks.com)
* [Author](http://skeeks.com)
* [ChangeLog](https://github.com/skeeks-cms/cms-module-reviews2/blob/master/CHANGELOG.md)


___

> [![skeeks!](https://skeeks.com/img/logo/logo-no-title-80px.png)](https://skeeks.com)  
<i>SkeekS CMS (Yii2) — fast, simple, effective!</i>  
[skeeks.com](https://skeeks.com) | [cms.skeeks.com](https://cms.skeeks.com)