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
                'label' => '大小',
                'value' => function ($model) {
                    return $model->fileSize;
                },
            ],
            [
                'class'  => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{cancel} {share} {msShare} {safe-share}  {delete}',
                'buttons' => [
                    'share' => function ($url, $model, $key) {
                        if (empty($model->fileSize)) return null;
                        $url = $model->viewUrl;
                        $share_data = $model->share;
                        $option = [
                            'title'     => '安全预览',
                            'data_plax' => 0,
                            'data-url'  => $url,
                            'class'     => 'btn btn-success',
                            //'onclick'   => 'show_file()',
                            'target'    => '_blank',
                        ];

                        if (!isset($share_data['status']) || $share_data['status'] == 0) {
                            return '';
                        }
                        return Html::a("安全预览", $url."&code=".$share_data['encrypt'], $option);

                    },
                    'cancel' => function($url, $model, $key) {
                        if (empty($model->fileSize)) return null;
                        $share_data = $model->share;
                        $option = [
                            'title'     => '分享操作',
                            'class'     => 'btn btn-success',
                        ];
                        if (!isset($share_data['status']) || $share_data['status'] == 0) {
                            return Html::a("允许预览", $url, $option);
                        }
                        return Html::a("取消预览", $url, $option);
                    },
                    'msShare' => function ($url, $model, $key) {
                        $url = $model->msOnlineUrl;
                        $share_data = $model->share;
                        $option = [
                            'title'     => '原生预览',
                            'data_plax' => 0,
                            'data-url'  => $url,
                            'class'     => 'btn btn-success',
                            //'onclick'   => 'show_file()',
                            'target'    => '_blank',
                        ];

                        if (isset($share_data['status']) && $share_data['status'] == 2) {
                            if (!$model->isSafe) {
                                $option['class'] = "btn btn-warning disabled";
                                $option['title'] = "不支持超过10M的文件的原生预览";
                            }

                            return Html::a("原生预览", $url, $option);
                        }
                    },
                    'safe-share' => function ($url, $model, $key) {
                        $share_data = $model->share;
                        $option = [
                            'title'     => '原生预览',
                            'data_plax' => 0,
                            'data-url'  => $url,
                            'class'     => 'btn btn-success',
                            //'onclick'   => 'show_file()',
                            'target'    => '_blank',
                        ];
                        if (empty($model->fileSize)) return null;

                        if (isset($share_data['status']) && $share_data['status'] == 2) {
                            return Html::a("禁止原生预览", $url, $option);
                        }

                        if (isset($share_data['status']) && $share_data['status'] == 1) {
                            if (!$model->isSafe) {
                                $option['class'] = "btn btn-waring disabled";
                                $option['title'] = "不支持超过10M的文件的原生预览";
                            }
                            return Html::a("开放原生预览", $url, $option);
                        }
                        return null;
                    },

                    'delete' => function($url, $model, $key) {
                        $option = [
                            'title'     => '删除',
                            'class'     => 'btn btn-success show_file',
                        ];
                        return Html::a("删除", $url, $option);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
