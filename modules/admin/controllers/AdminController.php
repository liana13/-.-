<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

/**
 * Class AdminController
 * @package app\models\admin\controllers
 */
class AdminController extends Controller
{
    public $layout = "main";

    public function beforeAction($action)
    {
       if (Yii::$app->user->isGuest || User::findOne(['id'=>Yii::$app->user->getId()])->type!=1) {
           return $this->redirect(['/admin/login']);
       } else {
           return true;
       }
    }

    public function init() {
        parent::init();
        return true;
    }
}
