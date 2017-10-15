<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\StuScoreSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stu-score-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'stu_name') ?>

    <?= $form->field($model, 'mobile') ?>

    <?= $form->field($model, 'age') ?>

    <?= $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'grade') ?>

    <?php // echo $form->field($model, 'school') ?>

    <?php // echo $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'batch') ?>

    <?php // echo $form->field($model, 'batch_name') ?>

    <?php // echo $form->field($model, 'score') ?>

    <?php // echo $form->field($model, 'export_file') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
