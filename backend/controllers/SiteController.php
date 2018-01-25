<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends HrjtController
{

    public function beforeAction($action)
    {
        if (in_array($action->id,['sync','power','logout'])) {
            $action->controller->enableCsrfValidation = false;
        }

        if(parent::beforeAction($action)) return true;
        return false;
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout','login', 'error', 'sync', 'power'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [ 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
                    'sync' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(['stu-score/index']);
        Yii::$app->end();
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->userRole;
            $uri = '';
            if ($role == 1) {
                $uri = "/index.php?exam-master";
            } else if($role == 9) {
                $uri = "/index.php?exam-teach";
            }
            $this->redirect(Yii::$app->params['exam_index_url'].$uri);
            Yii::$app->end();
            return $this->goHome();
        } else {
            $this->redirect(Yii::$app->params['frontend_login_page']);
            Yii::$app->end();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $role = Yii::$app->user->identity->userRole;
            $uri = '';
            if ($role == 1) {
                $uri = "/index.php?exam-master";
            } else if($role == 9) {
                $uri = "/index.php?exam-teach";
            }
            $this->redirect(Yii::$app->params['exam_index_url'].$uri);
            Yii::$app->end();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionPower()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('data');
            if (!empty($data)) {
                $data = is_array($data) ? $data : json_decode($data, true);
                return LoginForm::signup($data);
            }
        }
        return 'ok';
    }

}
