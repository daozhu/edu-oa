<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\StuScoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stu Scores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stu-score-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Stu Score', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'stu_name',
            'mobile',
            'age',
            'sex',
            // 'grade',
            // 'school',
            // 'subject',
            // 'batch',
            // 'batch_name',
            // 'score',
            // 'export_file',
            // 'type',
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
