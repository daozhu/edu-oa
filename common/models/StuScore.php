<?php

namespace common\models;

use Yii;
use yii\helpers\HtmlPurifier;

require_once (Yii::$app->basePath . "/../common/extensions/PHPExcel/PHPExcel.php");
/**
 * This is the model class for table "{{%stu_score}}".
 *
 * @property integer $id
 * @property string $stu_name
 * @property string $mobile
 * @property integer $age
 * @property string $sex
 * @property string $grade
 * @property string $subject
 * @property integer $batch
 * @property string $batch_name
 * @property string $score
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class StuScore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stu_score}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['age', 'status','batch', 'created_at', 'updated_at'], 'integer'],
            [['score'], 'number'],
            [['created_at', 'updated_at'], 'required'],
            [['stu_name', 'batch_name', 'school'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 15],
            [['sex'], 'string', 'max' => 6],
            [['export_file'], 'string', 'max' => 500],
            [['type'], 'integer'],
            [['grade', 'subject'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stu_name' => '姓名',
            'mobile' => '手机',
            'age' => '年龄',
            'sex' => '性别',
            'grade' => '年级',
            'school' => '学校',
            'subject' => '科目',
            'batch' => '考试批次',
            'batch_name' => '考试批次',
            'score' => '分数',
            'export_file' => '文件',
            'type' => '类型',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return StuScoreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StuScoreQuery(get_called_class());
    }

    //..export
    public static function export($file)
    {
        $filePath = $file;
        $PHPReader = new \PHPExcel_Reader_Excel2007 ();
        if (! $PHPReader->canRead ( $filePath )) {
            $PHPReader = new \PHPExcel_Reader_Excel5 ();
            if (! $PHPReader->canRead ( $filePath )) {
                return array (
                    'code' => - 1
                );
            }
        }
        $i = 1;
        $PHPExcel = $PHPReader->load ( $filePath );
        $currentSheet = $PHPExcel->getSheet ( 0 );
        $allColumn = $currentSheet->getHighestColumn ();
        $allColumn = \PHPExcel_Cell::columnIndexFromString($allColumn);
        $allRow = $currentSheet->getHighestRow ();
        $title = '';
        $batch='';
        $header = array();
        $data = array();
        for($rowIndex = 1; $rowIndex <= $allRow; $rowIndex ++) {

            $cell_data = array();
            for($colIndex = 0; $colIndex < $allColumn; $colIndex ++) {
                if ($rowIndex == 2 && $colIndex > 5) break;
                $addr = \PHPExcel_Cell::stringFromColumnIndex($colIndex) . $rowIndex;
                $cell = $currentSheet->getCell ( $addr )->getValue ();
                if ($cell instanceof \PHPExcel_RichText) //富文本转换字符串
                    $cell = $cell->__toString ();

                if ($rowIndex == 1) {
                    $title = str_ireplace(['（', '）'], ['(', ')'], HtmlPurifier::process(trim($cell)));
                    $batch = explode('(', $title);
                    if (!empty($batch) || !is_array($batch)) {
                        $batch = trim($batch[1], ')');
                    }
                    break;
                }
                $cell_data[] = HtmlPurifier::process($cell);
            }
            if ($rowIndex > 3) {
                $cell_data = array_slice($cell_data, 0, count($header));
            } else {
                $cell_data = array_filter($cell_data);
            }

            if(empty(array_filter($cell_data))) continue;

            if ($rowIndex == 3 || $rowIndex == 2) {
                $header = array_merge($header, $cell_data);
            } else if ($rowIndex > 3) {
                $data[] = $cell_data;
            }
        }

        $all_data = [
            'title' => $title,
            'batch' => $batch,
            'header'=> $header,
            'data'  => $data,
        ];

        return $all_data;
    }

    public static function saveData ($file)
    {
        $tran = Yii::$app->db->beginTransaction();
        try {
            $all_data = self::export($file);

            $insert_header = [
                'stu_name',
                'sex',
                'age',
                'mobile',
                'grade',
                'school',
                'subject',
                'score',
                'type',
                'batch_name',
                'batch',
                'export_file',
                'created_at',
                'updated_at',
            ];
            $insert_column_default_v = [
                'stu_name' => '-',
                'sex' => '-',
                'age' => 0,
                'mobile' => '1',
                'grade' => '-',
                'school' => '-',
                'subject' => '-',
                'score' => '-',
                'type' => 1,
                'batch_name' => '-',
                'batch' => '0',
                'export_file' => '-',
                'created_at' => time(),
                'updated_at' => time(),
            ];
            $ex_data = [1, HTMLPurifier::process($all_data['title']), $all_data['batch'], str_ireplace(Yii::$app->basePath, '', $file), time(), time()];

            $insert_data = [];
            foreach( $all_data['data'] as $k => $v ) {
                $subs = array_slice($v, 6);
                $info = array_slice($v, 0, 6);
                foreach ($info as $kk => &$vv) {
                    if (empty($vv)) {
                        $vv = $insert_column_default_v[$insert_header[$kk]] ?? '0';
                    }
                }
                foreach ($subs as $sub_key => $score) {
                    $tmp            = $info;
                    $t_sub          = $all_data['header'][(int)(6 + $sub_key)] ?? '';
                    if (empty($t_sub)) continue;
                    $tmp[]          = $t_sub;
                    $tmp[]          = number_format((int)$score,1);
                    $tmp            = array_merge($tmp, $ex_data);
                    $insert_data[]  = $tmp;
                }
            }

            //$ret = 0;
            $ret = Yii::$app->db->createCommand()->batchInsert(static::tableName(), $insert_header, $insert_data)->execute();
            Yii::error([
                'insert' => $insert_data,
                'all' => $all_data,
                'ret' => $ret,
            ]);

            if ($ret>=0) {
                $tran->commit();
                return ['code' => 200, 'msg' => "保存成功 : 共有". $ret.'条数据保存成功' ];
            } else {
                return ['code' => 500, 'msg' => "保存失败" ];
            }
        } catch (\Exception $e) {
            $tran->rollBack();
            $msg = $e->getMessage();
            return ['code' => 500, 'msg' => $msg];
        }
    }
}
