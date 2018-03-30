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
                'template' => '{share} {delete}',
                'buttons' => [
                    'share' => function ($url, $model, $key) {
                        $url = $model->viewUrl;

                        $option = [
                            'title'     => '预览',
                            'data_plax' => 0,
                            'data-url'  => $url,
                            'class'     => 'btn btn-success show_file',
                            //'onclick'   => 'show_file()',
                            //'target'    => '_blank',
                        ];

                        return Html::button("预览", $option);
                        //return Html::a("预览", $url, $option);
                    },
                    'delete' => function($url, $model, $key) {
                        $option = [
                            'title'     => '删除',
                            'class'     => 'btn btn-success show_file',
                        ];
                        return Html::a("删除", $url, $option);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<script>
    <? $this->beginBlock('JS_APPLAY'); ?>
    $('.show_file').on('click', function(){
        var da = $(this).data();
        var index= layer.open({
            type: 2,
            title: '预览',
            closeBtn: 1,
            shade: [0],
            area: ['780px', '468px'],
            offset: 'rb',
            time: 0,
            anim: 2,
            maxmin: true,
            content: [da.url, 'yes'],
            end: function(){
            },
            success: function(layero, index){
                console.log(layero, index);
            }
        });

        layer.full(index);
    });
    <?
    $this->endBlock();
    $this->registerJs($this->blocks['JS_APPLAY'], \yii\web\view::POS_END)
    ?>
</script> 