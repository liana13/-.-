<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
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
        if (!Yii::$app->user->isGuest && User::findOne(['id'=>Yii::$app->user->getId()])->type==1) {
            $this->layout = "main";
            return $this->render('index');
        } else {
            $this->layout = "main-login";
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            }
            return $this->redirect(['/admin/login']);
        }
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest && User::findOne(['id'=>Yii::$app->user->getId()])->type==1) {
            $this->layout = "main";
            return $this->render('index');
        } else {
            $this->layout = "main-login";
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->redirect(['/admin/index']);
            }
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

        return $this->redirect(['/admin/login']);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        if (!Yii::$app->user->isGuest && User::findOne(['id'=>Yii::$app->user->getId()])->type==1) {
            $this->layout = "main";
            return $this->render('index');
        } else {
            $this->layout = "main-login";
            $model = new PasswordResetRequestForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->session->setFlash('success', 'Check your email.');

                    return $this->redirect(["/admin/index"]);
                } else {
                    Yii::$app->session->setFlash('error', 'Sorry, something was wrong.');
                }
            }

            return $this->render('requestPasswordResetToken', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        if (!Yii::$app->user->isGuest && User::findOne(['id'=>Yii::$app->user->getId()])->type==1) {
            $this->layout = "main";
            return $this->render('index');
        } else {
            $this->layout = "main-login";
            try {
                $model = new ResetPasswordForm($token);
            } catch (InvalidParamException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                Yii::$app->session->setFlash('success', 'New password was saved.');

                return $this->redirect(["/admin/index"]);
            }

            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }
    }
}
