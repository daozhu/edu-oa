<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '欢迎访问';
//$this->params['breadcrumbs'][] = $this->title;
?>



<div class="app-location">
    <h2><?= Html::encode($this->title) ?></h2>
    <div class="line"><span></span></div>
    <div class="location"><img src="/imgs/0000.png" class="img-responsive" alt="" /></div>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <?= $form->field($model, 'mobile')->textInput([
        'autofocus' => true,
        'placeholder' => '手机号'
    ])->label(false) ?>
    <?= $form->field($model, 'password')->passwordInput([
        'placeholder' => '密码'
    ])->label(false) ?>

    <div class="submit">
        <input type="submit" onClick="myFunction()" value="登录" >
    </div>
    <div class="clear"></div>
    <div class="new">
        <h3><a href="#"></a></h3>
        <h4><a href="<?= Url::to(['site/signup']) ?>">没有账号? 注册</a></h4>
        <div class="clear"></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

