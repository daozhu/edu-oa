<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class HrjtController extends Controller
{

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->userRole;
            if (!in_array($role, [1,9])) {
                Yii::$app->response->redirect(Yii::$app->params['exam_index_url']);
                Yii::$app->end();
            }
        } else {
            //Yii::$app->response->redirect(['site/login']);
            //Yii::$app->end();
        }

        return true;
    }
}
