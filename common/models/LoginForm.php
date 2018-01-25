<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\Cookie;
use linslin\yii2\curl;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $mobile;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            //['username', 'required','message' => '用户名不能为空'],
            ['mobile', 'required','message' => '手机号不能为空'],
            ['password', 'required', 'message' => '密码不能为空'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '输入的信息不准确,请核对后重新输入');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $ret = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

            try {
                $curl = new curl\Curl();
                $url = Yii::$app->params['exam_index_url']."/index.php?yii2Sync-".Yii::$app->user->identity->username.'-'.session_id();
                $rsp = $curl->get($url);
                Yii::error($url);
                Yii::error($rsp);
                $data = json_decode($rsp, true);
                if (isset($data['ret']) && $data['ret'] == 'ok') {
                    $se1 = isset($data['c1']) ? $data['c1'] : [];
                    $se2 = isset($data['c2']) ? $data['c2'] : [];

                    if (!empty($se2)) {
                        //写入其他session
                        $cookie = new Cookie(['name' => $se2['c_name'], 'httpOnly' => false]);
                        $cookie->value = $se2['c_v'];
                        $cookie->domain = Yii::$app->params['cookie_domain'];
                        $cookie->path = '/';
                        $cookie->expire = time() + 3600 * 24 * 30;

                        setCookie($cookie->name,$cookie->value,$cookie->expire,Yii::$app->params['cookie_path'],Yii::$app->params['cookie_domain'],false,false);
                    }
                    if (!empty($se1)) {
                        //写入其他session
                        $cookie = new Cookie(['name' => $se1['c_name'], 'httpOnly' => false]);
                        $cookie->value = $se1['c_v'];
                        $cookie->domain = Yii::$app->params['cookie_domain'];
                        $cookie->path = '/';
                        $cookie->expire = time() + 3600 * 24 * 30;

                        setCookie($cookie->name,$cookie->value,$cookie->expire,Yii::$app->params['cookie_path'],Yii::$app->params['cookie_domain'],false,false);
                    }
                }
            } catch (\Exception $e) {
                $err = [
                    'line' => $e->getLine(),
                    'msg'  => $e->getMessage(),
                ];

                Yii::error(json_encode($err));
            }

            // 写入login_info

            return $ret;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
            if ($this->_user == null) {
                $this->_user = User::findByMobile($this->mobile);
            }
        }

        return $this->_user;
    }

    public static function signup(array $users)
    {
        foreach($users as $v) {
            if (!isset($v['username'])
                || !isset($v['mobile'])
                || !isset($v['password'])) {
                continue;
            }
            $user = User::findByMobile($v['mobile']);
            if (empty($user)) $user = new User();
            $user->username = $v['username'];
            $user->mobile = $v['mobile'];
            $user->setPassword($v['password']);
            $user->generateAuthKey();
            $user->save(false);
        }


        return true;
    }
}
