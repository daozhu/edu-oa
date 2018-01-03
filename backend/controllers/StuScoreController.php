<?php

namespace backend\controllers;

use Yii;
use common\models\StuScore;
use backend\models\StuScoreSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\models\StuScoreExport;
use yii\filters\AccessControl;

/**
 * StuScoreController implements the CRUD actions for StuScore model.
 */
class StuScoreController extends HrjtController
{
    public function beforeAction($action)
    {
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
                'only' => $this->actions(),
                'rules' => [
                    [
                        'actions' => $this->actions(),
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all StuScore models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StuScoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StuScore model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StuScore model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StuScore();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StuScore model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StuScore model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StuScore model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StuScore the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StuScore::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionExport()
    {
        $model = new StuScoreExport();
        $ret = '';
        if (Yii::$app->request->isPost) {
            $model->up_file = UploadedFile::getInstance($model, 'up_file');
            if ($model->upload()) {
                // 文件上传成功
                $ret = StuScore::saveData($model->getFilePath());
                if ($ret['code'] == 200) {
                    Yii::$app->getSession()->setFlash('success', $ret['msg']);
                } else {
                    Yii::$app->getSession()->setFlash('warning', $ret['msg']);
                }
            }
        }

        return $this->render('export', ['model' => $model, 'ret' => $ret]);
    }

}
