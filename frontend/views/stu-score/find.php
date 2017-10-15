<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '成绩查询';
$this->params['breadcrumbs'][] = $this->title;
?>

<form class="number2" action="" method="POST">
    <h2>成绩查询</h2>
    <div class="logo">
        <img src="/imgs/hrjt2.png" alt="">
    </div>
    <div class="form-group">
        <input type="text" value="<?= $req['tel']??'' ?>" class="form-control" name="tel" placeholder="请输入您的手机号">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" value="<?= $req['name'] ?? '' ?>" name="name" placeholder="请输入您的姓名">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" value="<?= $req['grade'] ?? '' ?>" name="grade" placeholder="请输入您所在的年级">
    </div>
    <p class="tips">友情提示</p>
    <p class="tips">忘记手机号请联系校区工作人员</p>
    <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
    <button type="submit" class="btn"><i class="fa fa-search"></i>查询</button>
    <?php if ($ret['code'] == 900) { ?>
    <p class="" style="color: red"> 无法查询到您的成绩,请您核对输入的信息是否有误,或者联系校区工作人员</p>
    <?php } ?>
</form>
