<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

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
            'name',
            [
                'attribute' => 'type',
                'label'     => '文件类型',
                'filter'    => $searchModel::$type
            ],

            [
                'class'  => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{cancel} {share} {delete}',
                'buttons' => [
                    'share' => function ($url, $model, $key) {
                        $url = $model->viewUrl;
                        $share_data = $model->share;
                        $option = [
                            'title'     => '预览',
                            'data_plax' => 0,
                            'data-url'  => $url,
                            'class'     => 'btn btn-success',
                            //'onclick'   => 'show_file()',
                            'target'    => '_blank',
                        ];

                        if (!isset($share_data['status']) || $share_data['status'] != 1) {
                            return '';
                        }
                        return Html::a("预览", $url."&code=".$share_data['encrypt'], $option);

                    },
                    'delete' => function($url, $model, $key) {
                        $option = [
                            'title'     => '删除',
                            'class'     => 'btn btn-success show_file',
                        ];
                        return Html::a("删除", $url, $option);
                    },
                    'cancel' => function($url, $model, $key) {
                        $share_data = $model->share;
                        $option = [
                            'title'     => '分享操作',
                            'class'     => 'btn btn-success',
                        ];
                        if (!isset($share_data['status']) || $share_data['status'] != 1) {
                            return Html::a("分享", $url, $option);
                        }
                        return Html::a("取消分享", $url, $option);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
