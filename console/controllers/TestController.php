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
use yii\helpers\ArrayHelper;

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
        date_default_timezone_set('UTC');
        $dos_config = [
            'ak' => 'c2a4167a495e4636b454d73533130523',
            'sk' => "3bfd1bfad60048908f7dc4e712d04c0c",
        ];
        $bos_config = [
            'credentials' => array(
                'accessKeyId'     => 'c2a4167a495e4636b454d73533130523',
                'secretAccessKey' => '3bfd1bfad60048908f7dc4e712d04c0c',
            )
        ];

        $model = new \common\models\BaiDuDoc($dos_config);

        $data = [
            'title' => 'myfirstdoc',
            'format' => 'docx',
        ];
        //$ret = $model->register($data);

        /*
         *
         *  [documentId] => doc-iegrqase8ukmfdn
            [bucket] => bktmid8dgurf4z4pz74a
            [object] => upload/doc-iegrqase8ukmfdn.doc
            [bosEndpoint] => http://bj.bcebos.com
         *
         * */


        //$ret = $model->status("status=PUBLISHED");
        //print_r(ArrayHelper::toArray(json_decode($ret)));
        //return;

        $query = "source=bos";
        $data = [
            //'source' => 'bos',
            'bucket' => 'office',//
            'object' => 'test/doc-iegrqase8ukmfdn.doc',
            'title'  => 'testttttttting2',
            'format' => 'doc'
        ];
        //$ret = $model->source($query, $data);

        $data = [
            'documentId' => 'doc-iegrqase8ukmfdn',
        ];
        //$ret = $model->publish($data, $data['documentId']);


        $uri = 'doc-iegrqase8ukmfdn';
        $query = "https=false";
        $data = [
            'documentId' => $uri,
            'https' => false
        ];
        $ret = $model->search($data, $uri);

        var_dump($model->getCanonicalRequest());

        var_dump($ret);
        print_r(ArrayHelper::toArray(json_decode($ret)));
    }
    /**
     * 对象存储
     */
    public function actionBos()
    {
        $config = [
            'ak' => 'c2a4167a495e4636b454d73533130523',
            'sk' => "3bfd1bfad60048908f7dc4e712d04c0c",
        ];
        $config = [
            'credentials' => array(
                'accessKeyId' => 'c2a4167a495e4636b454d73533130523',
                'secretAccessKey' => '3bfd1bfad60048908f7dc4e712d04c0c',

            ),
        ];
        $bucket = "bktmid8dgurf4z4pz74a";
        $obj_key = "upload/doc-iegrqase8ukmfdn.doc";

        $file_path = dirname(Yii::$app->basePath)."/backend/upload/office/操作文档.docx";
        $this->redTip($file_path);

        $model = new \common\models\BaiBosClient($config);
        $ret   = $model->upload($bucket, $obj_key, $file_path);

        print_r(($ret));

    }
}