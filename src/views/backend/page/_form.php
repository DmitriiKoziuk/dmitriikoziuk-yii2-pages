<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use DmitriiKoziuk\yii2Pages\forms\PageCreateForm;

/* @var $this yii\web\View */
/* @var $model DmitriiKoziuk\yii2Pages\forms\PageCreateForm|DmitriiKoziuk\yii2Pages\forms\PageUpdateForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->dropDownList([
        PageCreateForm::NOT_ACTIVE => 'Not active',
        PageCreateForm::ACTIVE => 'Active',
    ]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
