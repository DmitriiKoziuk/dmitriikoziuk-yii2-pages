<?php

use yii\helpers\Html;
use DmitriiKoziuk\yii2Pages\PagesModule;

/**
 * @var $this yii\web\View
 * @var $pageInputForm \DmitriiKoziuk\yii2Pages\forms\PageInputForm
 */

$this->title = Yii::t(PagesModule::TRANSLATE, 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t(PagesModule::TRANSLATE, 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'pageInputForm' => $pageInputForm,
    ]) ?>

</div>
