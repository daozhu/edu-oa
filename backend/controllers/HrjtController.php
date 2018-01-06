<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Hrjt controller
 */
class HrjtController extends Controller
{

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)){
            if ($action->id !== 'logout'
                && !Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->userRole;
                if (!in_array($role, [1, 9])) {
                    Yii::$app->response->redirect(Yii::$app->params['exam_index_url']);
                    Yii::$app->end();
                }
            }
            return true;
        }

        return false;
    }
}
