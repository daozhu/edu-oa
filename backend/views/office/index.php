<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OfficeSeach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '课件管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="office-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('上传课件', ['export'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'file',
            'name',
            [
                'attribute' => 'type',
                'label'     => '文件类型',
                'filter'    => $searchModel::$type
            ],
            //'op_user',
            //'status',
            //'created_at',
            //'updated_at',
            [
                'class'  => 'yii\grid\ActionColumn',
                'header' => '预览',
                'template' => '{share}',
                'buttons' => [
                    'share' => function ($url, $model, $key) {
                        $option = [
                            'title'     => '预览',
                            'data_plax' => 0,
                            'target'    => '_blank',
                        ];
                        $view_url = !empty(Yii::$app->params['frontend_host']) ? Yii::$app->params['frontend_host'].'/office/view-online?id='.$model->id : '';
                        $view_url = urlencode($view_url);
                        $url = Yii::$app->params['mffice'].$view_url;

                        return Html::a("预览", $url, $option);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
