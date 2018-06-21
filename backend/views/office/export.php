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
<div class="stu-score-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'up_file')->label('上传课件')->fileInput() ?>

    <button>提交</button>

    <?php ActiveForm::end() ?>

</div>
