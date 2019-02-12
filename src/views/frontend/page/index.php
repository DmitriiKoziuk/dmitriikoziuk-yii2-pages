<?php

/**
 * @var $this \yii\web\View
 * @var $pageEntity \DmitriiKoziuk\yii2Pages\entities\PageEntity
 */

$this->title = $pageEntity->getMetaTitle();
$this->registerMetaTag(['name' => 'description', 'content' => $pageEntity->getMetaDescription()]);
$this->registerLinkTag(['rel' => 'canonical', 'href' => $pageEntity->getUrl()]);

echo $pageEntity->getContent();