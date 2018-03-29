<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
/**
 * This is the model class for table "{{%office}}".
 *
 * @property int $id
 * @property string $file 文件path
 * @property string $name 文件名
 * @property string $type 文件类型 doc txt xls pdf ppt
 * @property int $op_user 操作人
 * @property int $status 状态
 * @property int $created_at
 * @property int $updated_at
 */
class Office extends \yii\db\ActiveRecord
{
    // docx = doc  pptx=ppt
    public static $type = [
        'doc' => 'doc',
        'ppt' => 'ppt',
        'xls' => 'xls',
        'pdf' => 'pdf',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%office}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['op_user'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['op_user'],
                ],
                'value' => function ($event) {
                    return Yii::$app->user->identity->getId();
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['op_user', 'status', 'created_at', 'updated_at'], 'integer'],
            //[['created_at', 'updated_at'], 'required'],
            [['file', 'name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => '文件path',
            'name' => '文件名',
            'type' => '文件类型 doc txt xls pdf ppt',
            'op_user' => '操作人',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return OfficeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OfficeQuery(get_called_class());
    }

    public static function upData(array $data = array())
    {
        $tran = Yii::$app->db->beginTransaction();
        try {
            $data   = $data[0];
            $model  = new self();
            $model->file = $data['file'];
            $model->type = $data['type'];
            $model->name = $data['name'];
            if ($model->save()) {
                $tran->commit();
                return ['code' => 200, 'msg' => '上传成功'];
            }
            return ['code' => 500, 'msg' => json_encode($model->getErrors(), JSON_UNESCAPED_SLASHES)];
        } catch (\Exception $e) {
            $tran->rollBack();
            $msg = $e->getMessage();
            return ['code' => 500, 'msg' => $msg];
        }
    }

    public function delete()
    {
        $transaction = static::getDb()->beginTransaction();
        try {
            $this->status = 0;
            if($this->save()) {
                $transaction->commit();
                return true;
            }
            $transaction->rollBack();
            return false;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
