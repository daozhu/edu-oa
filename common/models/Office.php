<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use linslin\yii2\curl;
use yii\helpers\ArrayHelper;

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
        'doc'  => 'doc',
        'docx' => 'docx',
        'ppt' => 'ppt',
        'pptx' => 'pptx',
        'xls' => 'xls',
        'xlsx' => 'xlsx',
        'pdf' => 'pdf',
    ];
    // docx = doc  pptx=ppt
    public static $sys = [
        0 => '内部',
        1 => '百度',
    ];

    const BAIDU_DOC_HOST = "doc.bj.baidubce.com";
    const BAIDU_DOC_INFO_URI = "/v2/document/";


    private $share_data = '';
    private $doc_id = '';

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
            [['op_user', 'status', 'created_at', 'updated_at','sys'], 'integer'],
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
            'sys' => '来源',
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
            $model->sys  = 1;
            if ($model->save()) {
                $tran->commit();
                return ['code' => 200, 'msg' => '上传成功', 'last_id' => $model->id];
            }
            $tran->rollBack();
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

    //..获取预览url
    public function getViewUrl()
    {
        return !empty(Yii::$app->params['frontend_host']) ? Yii::$app->params['frontend_host'].'/office/view-online?id='.$this->id : '';
    }

    //url
    public function getBaiduDocInfo()
    {
        if ($this->sys != 1 || empty($this->file)) return null;

        $url   = self::BAIDU_DOC_HOST.self::BAIDU_DOC_INFO_URI.$this->file;

    }

    // 发布文档
    public static function pub_doc($file_id)
    {
        try{
            $err = [];
            $office  = self::findOne(['id' => $file_id]);
            if (empty($office)) {
                $err[] = "文档不存在";
                return [
                    'err'       => $err,
                    'status'    => '',
                    'doc_id'    => '',
                    'oper_data' => [],
                ];
            }

            $status = BaiDuDoc::$doc_status_arr[0];
            $oper_arr = [
                'dos' => [],
                'bos' => [],
            ];

            $config = [
                'ak' => Yii::$app->params['baidu_ak'],
                'sk' => Yii::$app->params['baidu_sk'],
            ];
            $dos = new BaiDuDoc($config);
            $register = [
                'title'  => $office->name,
                'format' => $office->type
            ];
            $register_ret = $dos->register($register);
            $register_ret = ArrayHelper::toArray(json_decode($register_ret));
            $oper_arr['dos'][] = $register_ret;

            $doc_id = '';
            if (!empty($register_ret['documentId'])) {
                $status = BaiDuDoc::$doc_status_arr[1];
                $doc_id = $register_ret['documentId'];
                $bos_conf = [
                    'bucket'     => $register_ret['bucket'],
                    'object'     => $register_ret['object'],
                    'file_path'  => $office->file,
                ];
                $bos_ret = $dos->upBos($bos_conf);
                $bos_ret = ArrayHelper::toArray(($bos_ret));
                $oper_arr['bos'][] = $bos_ret;
            } else {
                $err[] = "注册失败";
            }

            if (!empty($doc_id)) {
                $pub_ret = $dos->publish($doc_id);
                $pub_ret = ArrayHelper::toArray(json_decode($pub_ret));
                $oper_arr['dos'][] = $pub_ret;

                if (!empty($pub_ret['documentId'])) {
                    if ($pub_ret['status'] == "FAILED") {
                        $status = BaiDuDoc::$doc_status_arr[3];
                        $err[]  = json_encode($pub_ret['error']);
                    } else {
                        $status = BaiDuDoc::$doc_status_arr[2];
                    }
                }
            }

            return [
                'err'       => $err,
                'status'    => $status,
                'doc_id'    => $doc_id,
                'oper_data' => $oper_arr,
            ];
        } catch (\Exception $e){
            return [
                'err'       => [$e->getMessage()],
                'status'    => $e->getLine(),
                'doc_id'    => '',
                'oper_data' => [],
            ];
        }
    }

    public static function share($file_id, $share_flag = true)
    {
        $share = (new \yii\db\Query())->from('hrjt_office_share')->where(['file_id' => $file_id])->one();

        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($share)) {
                $pub_data = self::pub_doc($file_id);
                if (isset($pub_data['err']) && empty(array_filter($pub_data['err']))) {
                    $insert_column = ['file_id', 'op_user', 'status', 'doc_id', 'doc_status', 'doc_status_msg', 'created_at', 'updated_at', 'encrypt'];
                    $insert_data[] = [$file_id, Yii::$app->user->id,intval($share_flag), $pub_data['doc_id'], $pub_data['status'], json_encode($pub_data['oper_data'],JSON_UNESCAPED_UNICODE), time(), time(),self::getShareCode()];
                    $ret = Yii::$app->db->createCommand()->batchInsert('hrjt_office_share', $insert_column, $insert_data)->execute();
                } else {
                    $trans->rollBack();
                    return ['code' => 500, 'msg' =>  "操作失败-->" .json_encode($pub_data, JSON_UNESCAPED_UNICODE)];
                }
            } else {
                Yii::$app->db->createCommand()->update('hrjt_office_share', ['status' => intval($share_flag),'updated_at' => time()], [
                    'file_id' => $file_id
                ])->execute();
            }

            $trans->commit();
            return ['code' => 200, 'msg' => '操作成功'];
        } catch (\Exception $e){
            $trans->rollBack();
            $err = $e->getMessage();
            return ['code' => 500, 'msg' => "操作失败-->" .json_encode(['line'=> $e->getLine(),'msg' => $e->getMessage()]). "-->". $file_id];
        }
    }

    //..获取预览url
    public function getShare()
    {
        if (!empty($this->share_data)) {
            return $this->share_data;
        }

        return $this->share_data = (new \yii\db\Query())->from('hrjt_office_share')->where(['file_id' => $this->id])->one();
    }

    public function getDocId()
    {
        if (!empty($this->doc_id)) {
            return $this->doc_id;
        }

        $share = $this->getShare();
        return $this->doc_id = isset($share['doc_id'])?$share['doc_id'] : '';
    }

    public static function toggleShare($file_id)
    {
        $share = (new \yii\db\Query())->from('hrjt_office_share')->where(['file_id' => $file_id])->one();
        $flag = false;
        if (!isset($share['status']) || $share['status'] != 1) {
            $flag = true;
        }
        return self::share($file_id, $flag);
    }

    public function BaiDuDocStats()
    {
        $config = [
            'ak' => Yii::$app->params['baidu_ak'],
            'sk' => Yii::$app->params['baidu_sk'],
        ];
        $dos = new BaiDuDoc($config);

        $status = $dos->search($this->docId);

        //return $this->docId;
        return $status;
    }

    public static function getShareCode()
    {
        return mt_rand(10000,99999);
    }
}
