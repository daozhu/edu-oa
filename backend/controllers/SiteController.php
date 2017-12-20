<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function beforeAction($action)
    {
        if ($action->id == 'sync') {
            $action->controller->enableCsrfValidation = false;
        }

        parent::beforeAction($action);
        return true;
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
                        'actions' => ['login', 'error', 'sync'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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


    // login status login
    public function actionSync ()
    {
        if (Yii::$app->request->isPost && isset($_POST['data'])) {
            $data = base64_decode(Yii::$app->request->post('data'));
            $data = json_decode($data, true);
            if (isset($data['ret']) && $data['ret'] == 'ok') {
                $se1 = isset($data['c1']) ? $data['c1'] : [];
                $se2 = isset($data['c2']) ? $data['c2'] : [];

                if (!empty($se1) && !empty($se2)) {
                    Yii::$app->session->set($se1['c_name'], $se1['c_v']);
                    Yii::$app->session->set($se2['c_name'], $se2['c_v']);
                    Yii::$app->session->set('hahahha', $se2['c_v']);
                    Yii::$app->session->set('hahahha3333', $se1['c_v']);
                }
            }

            Yii::error($data);
        }


        return;
    }
}
