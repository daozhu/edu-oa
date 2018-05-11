<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="office-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'file')->label('百度文件id')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
