<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StuScore */

$this->title = 'Create Stu Score';
$this->params['breadcrumbs'][] = ['label' => 'Stu Scores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stu-score-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
