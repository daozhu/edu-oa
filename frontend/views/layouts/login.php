<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\LoginAsset;
use common\widgets\Alert;

LoginAsset::register($this);
$this->title = "慧润阶梯英语学校";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <meta name="keywords" content="<?= Html::encode($this->title) ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<h1><?= Html::encode($this->title) ?></h1>


<?= $content ?>


<div class="copy-right">
    <p>Copyright &copy; <?= date('Y') ?>.<?= Html::encode($this->title) ?> All rights reserved<a href="http://www.jingzhiheng.me" target="_blank" title="桃花岛"></a> - Designed by <a href="http://www.jingzhiheng.me" title="桃花岛" target="_blank"><?= "桃花岛" ?></a></p>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
