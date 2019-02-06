<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use DmitriiKoziuk\yii2Base\BaseModule;

/**
 * @var $this yii\web\View
 * @var $pageInputForm \DmitriiKoziuk\yii2Pages\forms\PageInputForm
 */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($pageInputForm, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pageInputForm, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pageInputForm, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pageInputForm, 'is_active')->dropDownList([
        'Not active',
        'Active',
    ]) ?>

    <?= $form->field($pageInputForm, 'meta_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pageInputForm, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($pageInputForm, 'content')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t(BaseModule::TRANSLATE, 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
