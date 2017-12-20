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
/*
 * console test
 * */
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
        $ret = "\r\n \r\n \r\n begin ... \r\n \r\n";
        $this->stdout($ret, BaseConsole::FG_GREEN);
        return parent::beforeAction($action); //
    }
    public function afterAction($action, $result)
    {
        $ret = "\r\n \r\n \r\n end ! \r\n \r\n";
        $this->stdout($ret, BaseConsole::FG_GREEN);
        return parent::afterAction($action, $result); //
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

}