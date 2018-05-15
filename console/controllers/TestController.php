<?php
/**
 * Created by PhpStorm.
 * User: jingzhiheng
 * Date: 2017/10/18
 * Time: PM8:15
 */

namespace console\controllers;

use Codeception\Subscriber\Console;
use yii\console\Controller;
use \Yii;
use yii\helpers\BaseConsole;

use common\models\Docx2Txt;
/**
 * 测试台
 *
 */
class TestController extends Controller
{

    /*
     * index
     * */
    public function actionIndex()
    {
        $this->stdout('ha ha hha', BaseConsole::BG_BLUE);
    }

    public function beforeAction($action)
    {
        $ret = "      begin ...\r\n\r\n ";
        $this->stdout($ret, BaseConsole::FG_GREEN);
        return parent::beforeAction($action); //
    }
    public function afterAction($action, $result)
    {
        $ret = "\r\n\r\n      end ! \r\n ";
        $this->stdout($ret, BaseConsole::FG_GREEN);
        return parent::afterAction($action, $result); //
    }

    private function greenTip($tip)
    {
        $this->stdout($tip, BaseConsole::FG_GREEN);
        echo "\r\n";
    }
    private function blueTip($tip)
    {
        $this->stdout($tip, BaseConsole::BG_BLUE);
        echo "\r\n";
    }

    private function redTip($tip)
    {
        $this->stdout($tip, BaseConsole::BG_RED);
        echo "\r\n";
    }

    private function yellowTip($tip)
    {
        $this->stdout($tip, BaseConsole::FG_YELLOW);
        echo "\r\n";
    }


    /*
     * read word
     * */
    public function actionReadWord()
    {
        //$path = Yii::getAlias('@backend').'/upload/paper/test-21.docx';
        $path = '/Users/jingzhiheng/Desktop/test-22.docx';

        // 实例化
        $text = new Docx2Txt();
        // 加载docx文件
        $text->setDocx($path);
        // 将内容存入$docx变量中
        //$docx = $text->extract();
        $docx = $text->resolve();
        // 调试输出
        //var_dump($docx);

        //require_once Yii::$app->basePath.'/../vendor/phpoffice/phpword/samples/Sample_Header.php';
        //$path = Yii::getAlias('@backend').'/upload/paper/test.docx';
        //$phpWord = \PhpOffice\PhpWord\IOFactory::load($path);
        //print_r($phpWord);
        //echo write($phpWord, basename(Yii::getAlias('@backend').'/upload/paper/ee', '.php'), $writers);
        //$this->stdout($path, BaseConsole::FG_RED);

    }

    /**
     * 百度文档
     */
    public function actionBaidu()
    {
        $this->redTip(" baidu doc ....");
        $config = [
            'ak' => 'c2a4167a495e4636b454d73533130523',
            'sk' => "3bfd1bfad60048908f7dc4e712d04c0c",
        ];

        $date_str = date(DATE_ATOM,time());
        //$this->greenTip($date_str);
        //$this->yellowTip(strpos($date_str,'+'));
        $date_str = substr(date(DATE_ATOM,time()),0,strpos($date_str,"+"));
        $this->redTip($date_str."Z");



        //$model = new \common\models\BaiDuDoc($config);


    }
}