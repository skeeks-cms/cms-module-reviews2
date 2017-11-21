<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 10.07.2015
 *
 * @var \skeeks\cms\reviews2\models\Reviews2Message $model
 */
/* @var $this yii\web\View */

?>

<div class="row margin-bottom-20">
    <div class="col-lg-2">
        <? if ($model->createdBy) : ?>
            <img src="<?= $model->createdBy->getAvatarSrc(); ?>" style="float: left; padding-right: 10px;"/>
            <?= $model->createdBy->displayName; ?>
        <? else : ?>
            <img src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>"/>
            <?= \Yii::t('skeeks/reviews2', 'Guest'); ?>
        <? endif; ?>
    </div>
    <div class="col-lg-10">
        <p>
            <?= \Yii::t('skeeks/reviews2', 'Voice'); ?>: <?= $model->rating; ?>
        </p>
        <? if ($model->comments) : ?>
            <p>
                <b><?= \Yii::t('skeeks/reviews2', 'Comment'); ?>:</b><br/>
                <?= $model->comments; ?>
            </p>
        <? endif; ?>

        <? if ($model->dignity) : ?>
            <p>
                <b><?= \Yii::t('skeeks/reviews2', 'Dignity'); ?>:</b><br/>
                <?= $model->dignity; ?>
            </p>
        <? endif; ?>

        <? if ($model->disadvantages) : ?>
            <p>
                <b><?= \Yii::t('skeeks/reviews2', 'Disadvantages'); ?>:</b><br/>
                <?= $model->disadvantages; ?>
            </p>
        <? endif; ?>
    </div>
    <div class="col-lg-12">
        <hr/>
    </div>
</div>
