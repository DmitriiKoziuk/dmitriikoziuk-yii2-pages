<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;

/* @var $this yii\web\View */
/* @var $model DmitriiKoziuk\yii2Pages\entities\PageEntity */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'label' => 'Url',
                'value' => function ($model) {
                    /** @var PageEntity $model */
                    return $model->url->url;
                },
            ],
            [
                'label' => 'Status',
                'value' => function ($model) {
                    /** @var PageEntity $model */
                    return empty($model->is_active) ? 'Not active' : 'Active';
                },
            ],
            'meta_title',
            'meta_description',
            'content:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
