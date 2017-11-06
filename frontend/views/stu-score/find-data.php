<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '考生的成绩';
$this->params['breadcrumbs'][] = $this->title;
?>

    <form class="number2" action="" method="POST">
        <h2>成绩查询结果</h2>
        <div class="logo">
            <img src="/imgs/hrjt2.png" alt="">
        </div>

        <h4>学生信息</h4>
        <? if ($ret['code'] == 200) {?>
            <table class="table table-bordered">
                <tr>
                    <td class="">姓名</td>
                    <td class=""><?= $ret['info']['stu_name']?></td>
                </tr>
                <tr>
                    <td class="">手机</td>
                    <td class=""><?= $ret['info']['mobile']?></td>
                </tr>
                <tr>
                    <td class="">年级</td>
                    <td class=""><?= $ret['info']['grade']?></td>
                </tr>
                <tr>
                    <td class="">学校</td>
                    <td class=""><?= $ret['info']['school']?></td>
                </tr>
                <tr>
                    <td class="">考试批次</td>
                    <td class=""><?= $ret['info']['batch_name']?></td>
                </tr>
            </table>

        <? } ?>

        <br />
        <h4>学生各科成绩</h4>
        <? if ($ret['code'] == 200) {?>

            <table class="table table-bordered">

                <? foreach($ret['data'] as $v) { ?>
                    <tr>
                        <td class=""><?= $v['subject']?></td>
                        <td class=""><?= $v['score'] ?></td>
                    </tr>
                <? } ?>
            </table>


        <? } ?>
    </form>


