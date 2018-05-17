<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * StuScoreExport represents the model behind the search form about `common\models\StuScore`.
 */
class OfficeExport extends Model
{
    /**
     * @var UploadedFile
     */
    public $up_file;
    public $save_path = '/upload/office/';
    public $real_path = '';

    public function rules()
    {
        return [
            //[['up_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx'],
            //[['up_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx'],
            [['up_file'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->real_path = Yii::$app->basePath
                . $this->save_path
                . date('YmdHis', time())
                . md5($this->up_file->baseName)
                . '.' . $this->up_file->extension;
            $this->up_file->saveAs($this->real_path);
            return true;
        } else {
            return false;
        }
    }

    // just for single file ..
    public function getFilePath()
    {
        return $this->real_path;
    }
}
