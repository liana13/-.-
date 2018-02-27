<?php

namespace app\modules\owner\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\cabinet\models\PasswordForm;
use yii\web\UploadedFile;
use app\models\User;
use app\models\Finance;
use yii\web\NotFoundHttpException;
use app\models\Object;
use app\models\Booking;

/**
 * Default controller for the `cabinet` module
 */
class DefaultController extends Controller
{
    public $layout = 'main';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest && User::findOne(['id'=>Yii::$app->user->getId()])->type==3) {
            $model = User::findOne(['id'=>Yii::$app->user->getId()]);
            return $this->render('index', [
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash("notify", "<a href='#' class='login-btn'>Авторизуйтесь</a>, чтобы войти в аккаунт.");
            return $this->redirect(['/']);
        }
    }
    public function actionVaucher($id)
    {
        if (($model= Booking::findOne(['id'=>$id])) !== null) {
            $this->layout = 'print';
            $object= Object::findOne(['id'=>$model->object_id]);
            return $this->render('vaucher', [
                'model' => $model,
                'object' => $object,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = User::findOne(['id'=>Yii::$app->user->getId()]);

        $uploadedFile = UploadedFile::getInstance($model,'avatar');
        $model->file = UploadedFile::getInstance($model,'file');

        if (!empty($model->file)) {
           $model->file->saveAs('upload/avatar/'.$model->file);
           $model->avatar = 'upload/avatar/'.$model->file;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionChangepassword()
    {
       $model = new PasswordForm;
       $modeluser = User::find()->where(['id'=>Yii::$app->user->getId()])->one();

       if($model->load(Yii::$app->request->post()) && $model->validate()) {
           $modeluser->setPassword($_POST['PasswordForm']['newPassword']);
           if($modeluser->save()){
               Yii::$app->getSession()->setFlash('success','Ваш новый пароль успешно сохранен.');
               return $this->redirect(['/owner/default/update/'.Yii::$app->user->getId()]);
           } else {
               Yii::$app->getSession()->setFlash('error','Пароль не изменен.');
               return false;
           }
       } else {
           return $this->redirect(['/owner/default/update/'.Yii::$app->user->getId()]);
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

        return $this->redirect(['/']);
    }


    public function actionCategory()
    {
        $this->layout = 'main-object';
        return $this->render('category');
    }

    public function actionFinance($id)
    {
        $model= Finance::findOne(['object_id'=>$id]);
        $this->layout = 'main-object';
        return $this->render('finance', [
            'model' => $model,
            'objectid'=>$id,
        ]);
    }

    public function actionCalendar()
    {
        $this->layout = 'main-object';
        return $this->render('calendar');
    }

    public function actionPrice()
    {
        $this->layout = 'main-object';
        return $this->render('price');
    }
}
