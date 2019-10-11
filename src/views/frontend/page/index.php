<?php

use yii\web\View;
use DmitriiKoziuk\yii2Pages\forms\PageUpdateForm;

/**
 * @var $this View
 * @var $page PageUpdateForm
 */

$this->title = $page->meta_title;
$this->registerMetaTag(['name' => 'meta_description', 'content' => $page->meta_description]);

echo $page->content;
