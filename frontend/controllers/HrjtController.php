<?php
namespace frontend\controllers;

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

            return true;
        }

        return false;
    }
}
