<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StuScore */

$this->title = '修改 ' . $model->stu_name. " 的成绩";
$this->params['breadcrumbs'][] = ['label' => '成绩列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->stu_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="stu-score-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
