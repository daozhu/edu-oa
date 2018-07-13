<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $model common\models\StuScore */

$this->title = '上传课件';
$this->params['breadcrumbs'][] = ['label' => '课件列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$flash = Yii::$app->getSession()->getAllFlashes();
if (!empty($flash)) {
    Alert::widget();
}
?>
<p>
    <?= Html::a('课件列表', ['index'], ['class' => 'btn btn-success']) ?>
</p>
<style>
    label{
        display:inline-block;
        width:160px;
        height:40px;
        line-height:40px;
        text-align: center;
        background:#1CBE38;
        font-size:18px;
        color:#fff;
        cursor:pointer;
    }
    /*隐藏默认样式*/
    input[type=file]{
        margin-left:-2000px;
        height:0;
    }
</style>

<div class="stu-score-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'up_file')->label('上传课件')->fileInput() ?>
    <div>
        <b style="color: red"> * 原生预览的文件的大小不应大于10M </b>
        <p id="fileName"></p>
        <img src="" id="fileImg">
    </div>
    <button>提交</button>

    <?php ActiveForm::end() ?>

</div>
<script>
    <?php $this->beginBlock('JS_APPLAY'); ?>
    $("input[type='file']").on("change",function(){
        //截取路径，获取上传文件名
        var urlArr = this.value.split("\\");
        if (this && this.files && this.files[0]) {
            document.getElementById("fileName").innerHTML = urlArr[urlArr.length-1];
            var fileUrl = URL.createObjectURL(this.files[0]);
            document.getElementById("fileImg").src = fileUrl;
        }else{
            //兼容IE9以下
            document.getElementById("fileName").innerHTML = urlArr[urlArr.length-1];
            document.getElementById("fileImg").style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
            document.getElementById("fileImg").filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = this.value;
        }
    });
    <?php
        $this->endBlock();
        $this->registerJs($this->blocks['JS_APPLAY'], \yii\web\view::POS_END);
    ?>
</script>
