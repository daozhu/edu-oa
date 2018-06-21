<?php

namespace backend\controllers;

use Yii;
use common\models\Office;
use backend\models\OfficeSeach;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\models\OfficeExport;
use common\models\BaiDuDoc;

/**
 * OfficeController implements the CRUD actions for Office model.
 */
class OfficeController extends HrjtController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Office models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OfficeSeach();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Office model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = 'layer';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Office model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Office();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Office model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Office model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Office model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Office the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Office::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionExport()
    {
        $model = new OfficeExport();
        $ret = '';
        if (Yii::$app->request->isPost) {

            $model->up_file = UploadedFile::getInstance($model, 'up_file');
            if ($model->upload()) {
                // 文件上传成功
                $ret = Office::upData([
                    [
                        'file' => $model->getFilePath(),
                        'type' => $model->up_file->extension,
                        'name' => $model->up_file->baseName,
                    ],
                ]);
                if ($ret['code'] == 200) {
                    //发布 上传的发布的参数控制
                    $pub_ret = Office::share($ret['last_id']);
                    if ($pub_ret['code'] != 200) {
                        Yii::$app->getSession()->setFlash('warning', $pub_ret['msg']);
                    } else {
                        Yii::$app->getSession()->setFlash('success', $ret['msg']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('warning', $ret['msg']);
                }
            }
        }

        return $this->render('export', ['model' => $model, 'ret' => $ret]);
    }

    public function actionCancel($id)
    {
        $pub_ret = Office::toggleShare($id);

        if ($pub_ret['code'] != 200) {
            Yii::$app->getSession()->setFlash('warning', $pub_ret['msg']);
        } else {
            Yii::$app->getSession()->setFlash('success', $pub_ret['msg']);
        }

        $this->redirect(['index']);
        Yii::$app->end();
    }
}
