<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use linslin\yii2\curl;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $mobile;
    public $password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required','message' => '用户名不能为空'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '用户名已被使用'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['mobile', 'required', 'message' => '手机号不能为空'],
            ['mobile', 'filter', 'filter' => 'trim'],
            ['mobile','match','pattern'=>'/^[1][34578][0-9]{9}$/'],
            ['mobile', 'unique', 'targetClass' => '\common\models\User', 'message' => '手机号已被使用'],

            /*
            ['email', 'trim'],
            ['email', 'required', 'message' => '邮箱不能为空'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            */

            ['password', 'required', 'message' => '密码不能为空'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $trans = \Yii::$app->db->beginTransaction();
        try{
            $user = new User();
            $user->username = $this->username;
            //$user->email = $this->email;
            $user->mobile = $this->mobile;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            //curl
            $curl = new curl\Curl();
            $url = \Yii::$app->params['exam_index_url']."/index.php?yii2SignSync-".$this->username.'hrjt'.$this->mobile.'-'.'888';
            $rsp = $curl->get($url);
            //Yii::error($url);
            \Yii::error([
                'name' => $this->username,
                'tel' => $this->mobile
            ]);
            \Yii::error($rsp);
            $data = json_decode($rsp, true);
            $ret  = false;
            if (isset($data['ret']) && $data['ret'] == 'ok') {
                $ret  =true;
            }

            if ($ret && $user->save()) {
                $trans->commit();
                return $user;
            }
            $trans->rollBack();
            return null;
        } catch (\ErrorException $e) {
            \Yii::error($e->getMessage());
            \Yii::error($e->getLine());
            $trans->rollBack();
            return null;
        }
    }
}
