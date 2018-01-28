<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '欢迎注册';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-location">
    <h2><?= Html::encode($this->title) ?></h2>
    <div class="line"><span></span></div>
    <div class="location"><img src="/imgs/0000.png" class="img-responsive" alt="" /></div>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

    <?= $form->field($model, 'username')->textInput([
        'autofocus' => true,
        'placeholder' => '用户名'
    ])->label(false) ?>

    <?= $form->field($model, 'mobile')->textInput([
        'placeholder' => '手机号'
    ])->label(false) ?>
    <?= $form->field($model, 'password')->passwordInput([
        'placeholder' => '密码'
    ])->label(false) ?>

    <div class="submit">
        <input type="submit" onClick="myFunction()" value="注册" >
    </div>
    <div class="clear"></div>
    <div class="new">
        <h3><a href="#"></a></h3>
        <h4><a href="<?= Url::to(['site/login']) ?>">已有账号? 登录</a></h4>
        <div class="clear"></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

