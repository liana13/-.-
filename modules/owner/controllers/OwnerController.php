<?php
namespace app\modules\owner\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

/**
 * Class OwnerController
 * @package app\models\owner\controllers
 */
class OwnerController extends Controller
{
    public $layout = "main";

    public function beforeAction($action)
    {
       if (Yii::$app->user->isGuest || User::findOne(['id'=>Yii::$app->user->getId()])->type!=3) {
           Yii::$app->session->setFlash("notify", "<a href='#' class='login-btn'>Авторизуйтесь</a>, чтобы войти в аккаунт.");
           return $this->redirect(['/']);
       } else {
           return true;
       }
    }

    public function init() {
        parent::init();
        return true;
    }
}
