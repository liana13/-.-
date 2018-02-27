<?php
namespace app\modules\cabinet\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

/**
 * Class CabinetController
 * @package app\models\cabinet\controllers
 */
class CabinetController extends Controller
{
    public $layout = "main";

    public function beforeAction($action)
    {
       if (Yii::$app->user->isGuest) {
           Yii::$app->session->setFlash("notify", "Авторизуйтесь, чтобы войти в аккаунт.");
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
