<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\StuScore */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stu Scores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stu-score-view">

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
            'stu_name',
            'mobile',
            'age',
            'sex',
            'grade',
            'school',
            'subject',
            'batch',
            'batch_name',
            'score',
            'export_file',
            'type',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
