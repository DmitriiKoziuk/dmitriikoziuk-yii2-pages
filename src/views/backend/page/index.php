<?php

use yii\helpers\Html;
use yii\grid\GridView;
use DmitriiKoziuk\yii2Pages\entities\PageEntity;

/* @var $this yii\web\View */
/* @var $searchModel DmitriiKoziuk\yii2Pages\entities\PageEntitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Page', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'url',
                'content' => function ($model) {
                    /** @var PageEntity $model */
                    return $model->url->url;
                },
            ],
            'is_active',
            'meta_title',
            'meta_description',
            //'content:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
