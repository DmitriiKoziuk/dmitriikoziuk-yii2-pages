<?php

use yii\helpers\Html;
use DmitriiKoziuk\yii2Pages\PagesModule;
use DmitriiKoziuk\yii2Base\BaseModule;

/**
 * @var $this yii\web\View
 * @var $pageInputForm \DmitriiKoziuk\yii2Pages\forms\PageInputForm
 * @var $pageEntity \DmitriiKoziuk\yii2Pages\entities\PageEntity
 */

$this->title = Yii::t(PagesModule::TRANSLATE, 'Update Page: {name}', [
    'name' => $pageInputForm->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(PagesModule::TRANSLATE, 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $pageInputForm->name, 'url' => ['view', 'id' => $pageEntity->getId()]];
$this->params['breadcrumbs'][] = Yii::t(BaseModule::TRANSLATE, 'Update');
?>
<div class="page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'pageInputForm' => $pageInputForm,
    ]) ?>

</div>
