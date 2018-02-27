<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\data\Sort;
use yii\data\Pagination;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\AdminForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\User;
use app\models\Regform;
use app\models\Review;
use app\models\Regformadmin;
use app\models\search\ObjectSiteSearch;
use app\models\search\ObjectSearch;
use app\models\search\ObjectsallSearch;
use app\models\Object;
use app\models\Person;
use app\models\Message;
use app\models\Post;
use app\models\Rp;
use app\models\Properties;
use app\models\Finance;
use app\models\filter;
use app\models\Booking;
use app\models\Config;
use app\models\Servis;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
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

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // change layout for error action
            if ($action->id=='error')
                 $this->layout ='error';
            return true;
        } else {
            return false;
        }
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ObjectSiteSearch();
        $objects = Object::find()->where(['tarif_id' => 4, 'active'=>1])->orderBy(new Expression('rand()'))->all();
        $advert = Object::find()->where(['!=', 'tarif_id', 0])->andWhere(['active'=>1])->orderBy(new Expression('rand()'))->limit(6)->all();
        $rp = Rp::find()->where(['page'=>'/'])->andWhere('date >= NOW()')->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'objects' => $objects,
            'advert' => $advert,
            'rp' => $rp,
        ]);
    }

    public function actionContactadmin()
    {
        $model = new AdminForm();
        $adminemail = User::findOne(['type'=>1])->email;
        if ($model->load(Yii::$app->request->post()) && $model->contact($adminemail)) {
            Yii::$app->session->setFlash('notify', 'Сообщение отправлено.');
            return $this->redirect(['/'.Object::findOne(['id'=>$model->objectid])->alias]);
        }
        Yii::$app->session->setFlash('notify', 'Сообщение не отправлено.');
        return $this->redirect(['/'.Object::findOne(['id'=>$model->objectid])->alias]);
    }

    /**
     * Displays text page.
     *
     * @return string
     */
    public function actionView($url)
    {
        $query = Object::find()->where(['active'=>1]);
        $query->joinWith(['rate', 'price', 'center', 'fromsea', 'highsea']);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $post = Post::findOne(['id'=>1])->description;

        $sort = new Sort([
            'attributes' => [
                'tarif_id',
                'updated_at',
                'act_oplata',
                'price' => [
                    'asc' => ['CASE WHEN CAST(price.field_value AS UNSIGNED) IS NULL THEN 1 ELSE 0 END, CAST(price.field_value AS UNSIGNED)' => SORT_ASC],
                    'desc' => ['CAST(price.field_value AS UNSIGNED)' => SORT_DESC],
                    'label' => 'Сортировать по цене',
                ],
                'rate' => [
                    'asc' => ['CASE WHEN (rate.rate) IS NULL THEN 1 ELSE 0 END, (rate.rate)' => SORT_ASC],
                    'desc' => ['rate.rate' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'По рейтингу на основе голосов',
                ],
                'center' => [
                    'asc' => ['CASE WHEN CAST(center.field_value AS UNSIGNED) IS NULL THEN 1 ELSE 0 END, CAST(center.field_value AS UNSIGNED)' => SORT_ASC],
                    'desc' => ['CAST(center.field_value AS UNSIGNED)' => SORT_DESC],
                    'label' => 'Удаленности от центра',
                ],
                'fromsea' => [
                    'asc' => ['CASE WHEN CAST(fromsea.field_value AS UNSIGNED) IS NULL THEN 1 ELSE 0 END, CAST(fromsea.field_value AS UNSIGNED)' => SORT_ASC],
                    'desc' => ['CAST(fromsea.field_value AS UNSIGNED)' => SORT_DESC],
                    'label' => 'Удаленности от моря',
                ],
                'highsea' => [
                    'asc' => ['CASE WHEN CAST(highsea.field_value AS UNSIGNED) IS NULL THEN 1 ELSE 0 END, CAST(highsea.field_value AS UNSIGNED)' => SORT_ASC],
                    'desc' => ['CAST(highsea.field_value AS UNSIGNED)' => SORT_DESC],
                    'label' => 'По высоте над уровнем моря',
                ],
            ],
            'defaultOrder' => ['tarif_id' => SORT_DESC, 'updated_at' => SORT_ASC, 'act_oplata' => SORT_ASC],
        ]);

        $searchModel = new ObjectSiteSearch();
        $rp = Rp::find()->where(['page'=>'/'.$url])->andWhere('date >= NOW()')->all();
        $advcount = 6;
        $advert = Object::find()->where(['!=', 'tarif_id', 0])->andWhere(['active'=>1])->orderBy(new Expression('rand()'))->limit($advcount)->all();

        $locality = Config::findOne(['id'=>1]);
        $alias = $locality->alias_two;
        $aliastwo = mb_convert_case(explode("-", $alias)[0], MB_CASE_TITLE, "UTF-8");
        if (count(explode("-",$alias))>1) {
            $aliasall = explode("-",$alias)[1];
            $aliasend = implode(' ', explode('-',$aliasall));
            if (strpos($locality->title, '-') !== false) {
                $divaider = " -";
            } else {
                $divaider = " ";
            }
        } else {
            $divaider = $aliasend = '';
        }
        $titlepart =$aliastwo. $divaider .$aliasend;

        if ($serv = Servis::findOne(['alias'=>explode('-'.$locality->alias_two, $url)])) {
            $title = implode(' ', explode('-',$serv->alias)). " " . $titlepart;
            $description = $serv->description;
            if ($serv->parent_id == 0) {
                $query = Object::find()->where(['active'=>1])->andwhere(['in','service', (new Query())->select(['id'])->from('servis')->where(['parent_id'=>$serv->id])]);
            } else{
                $query = Object::find()->where(['active'=>1])->andwhere(['service'=>$serv->id]);
            }
            $query->joinWith(['rate', 'price', 'center', 'fromsea', 'highsea']);
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $objects = $query->offset($pages->offset)
                ->limit($pages->limit)->orderBy($sort->orders)->all();
            return $this->render('objectsearch', [
                'title' => $title,
                'searchModel' => $searchModel,
                'objects' => $objects,
                'sort' => $sort,
                'pages' => $pages,
                'description' => $description
            ]);
        } elseif (($model = Object::findOne(['alias'=>$url])) !== null) {
            $searchCat = new filter();
            $dataProvider = $searchCat->search(Yii::$app->request->queryParams);
            return $this->render('/object/view', [
                'searchModel' => $searchModel,
                'searchCat' => $searchCat,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        } elseif (($model = Post::findOne(['url'=>$url])) !== null) {
            $this->layout = 'main-post';
            return $this->render('view', [
                'searchModel' => $searchModel,
                'advert' => $advert,
                'rp' => $rp,
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionGetdialog($object_id, $uid)
    {
        $mess_user1 = Message::find()->where(['user_one'=>Yii::$app->user->getId()])->andWhere(['user_two'=>$uid])
                ->andWhere(['object_id'=>$object_id])->one();
        $mess_user2 = Message::find()->where(['user_two'=>Yii::$app->user->getId()])->andWhere(['user_one'=>$uid])
                ->andWhere(['object_id'=>$object_id])->one();
        if (count(Message::find()->all())==0) {
            $dialog = 1;
        } elseif ($mess_user1) {
            $dialog = $mess_user1->dialogue_id;
        } elseif ($mess_user2) {
            $dialog = $mess_user2->dialogue_id;
        } else {
            $mess_userall = Message::find()->orderBy('dialogue_id DESC')->all();
            $dialog = $mess_userall[0]->dialogue_id+1;
        }
        return $dialog;
    }

    public function actionMessage($id)
    {
        $model = new Message();
        $object = Object::findOne(['id'=>$id]);
        $userprop = Properties::findOne(['object_id'=>$id, 'field_id'=>39]);
        if ($model->load(Yii::$app->request->post())) {
            $bookurl = Yii::$app->urlManager->createAbsoluteUrl(['/owner/message']);
            if ($object->tarif_id == 4) {
                $username = User::findOne(['id'=>$model->user_one])->username;
                $fromemail=  User::findOne(['id'=>$model->user_one])->email;
                $model->save();
                if (Properties::findOne(['object_id'=>$id, 'field_id'=>39])) {
                    $useremail = Properties::findOne(['object_id'=>$id, 'field_id'=>39])->field_value;
                    Yii::$app->mailer->compose()
                        ->setTo($useremail)
                        ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                        ->setSubject('Новое сообщение в ' ." " .Yii::$app->name)
                        ->setHtmlBody('Здравствуйте. Вам поступило сообщение от клиента портала ' . Yii::$app->name .  '. Его можно увидеть в разделе «Сообщения» в Вашем кабинете на портале ' . Yii::$app->name . '.<br><a href="'. $bookurl.'"> Посмотреть сообщение </a>')
                        ->send();
                }
                Yii::$app->session->setFlash('contact', 'Сообщение отправлено администратору объекта. Когда поступит ответ, Вы сможете его увидеть в своем кабинете, в разделе «Сообщения».');
            } else {
                $useremail = Properties::findOne(['object_id'=>$id, 'field_id'=>39])->field_value;
                Yii::$app->mailer->compose()
                    ->setTo($useremail)
                    ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                    ->setSubject('Новое сообщение в ' ." " .Yii::$app->name)
                    ->setHtmlBody('Здравствуйте. Вам поступило сообщение от клиента портала ' . Yii::$app->name .  ' в Вашем объекте <a href ="'.$model->url.'">'.$model->objecttitle.'</a> <br>Имя пользователя: '.$model->username.'<br>Контактная информация: '.$model->contact.'<br> Сообщение: ' . $model->text)
                    ->send();
                Yii::$app->session->setFlash('contact', 'Ваше сообщение отправлено администратору объекта.');
            }
            return $this->redirect(['/'.$object->alias]);
        } else {
            return $this->redirect(['/'.$object->alias]);
        }
   }

   public function actionObjects()
   {
        $searchModel = new ObjectsallSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('objects', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact()) {
            if (Properties::findOne(['object_id'=>$model->obj, 'field_id'=>36])->field_value) {
                $phone = Properties::findOne(['object_id'=>$model->obj, 'field_id'=>36])->field_value;
                $text = Yii::$app->name.'. Бронь '.Object::findOne(['id'=>$model->obj])->title.', '.$model->phone.', c'.$model->from.'по'.$model->to.','.$model->count.'  чел.';
                file_get_contents("https://smsc.ru/sys/send.php?login=marfelev&psw=rhv6x29k3go7&phones=".urlencode("$phone")."&mes=".urlencode("$text")."&sender=RAY.RF&charset=utf-8");
            }
            Yii::$app->session->setFlash('contact', 'Заявка отправлeна напрямую в данный объект, но не гарантирует бронирования.<br>Если в течении суток с Вами не свяжется представитель данного объекта, то пожалуйста свяжитесь с объектом сами.');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if (Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->renderAjax('login', [
            'model' => $model,
        ]);
    }

    public function actionUsercab($id)
    {
        if (!Yii::$app->user->isGuest) {
            $model = User::findOne(['id'=>$id]);
            if ($model->type==1) {
                return $this->redirect(['/admin']);
            } elseif ($model->type==2) {
                return $this->redirect(['/cabinet']);
            } else {
                return $this->redirect(['/owner']);
            }
        } else {
            Yii::$app->session->setFlash("notify", "<a href='#' class='login-btn'>Авторизуйтесь</a>, чтобы войти в аккаунт.");
            return $this->redirect(['/site/index']);
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

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('index');
        } else {
            $model = new PasswordResetRequestForm();
            if (Yii::$app->request->isAjax && $model->load($_POST)) {
                Yii::$app->response->format = 'json';
                return \yii\widgets\ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->session->setFlash('notify', 'Перейдите ваш почтовый ящик, чтобы восстановить пароль.');
                    return $this->redirect(["/site/index"]);
                }
            }

            return $this->renderAjax('requestPasswordResetToken', [
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
        if (!Yii::$app->user->isGuest) {
            return $this->render('index');
        } else {
            try {
                $model = new ResetPasswordForm($token);
            } catch (InvalidParamException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                Yii::$app->session->setFlash('notify', 'Новый пароль успешно изменен.');
                return $this->redirect(['index']);
            }

            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegistration()
    {
        $model = new Regform();

        if (Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $succesurl = Yii::$app->urlManager->createAbsoluteUrl(['/site/confirm/'.$model->id]);
            Yii::$app->session->setFlash('successreg', "Вы успешно зарегистрировались как пользователь портала ".Yii::$app->name.".");
            Yii::$app->mailer->compose()
                ->setTo($model->email)
                ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                ->setSubject('Регистрация на '.Yii::$app->name)
                ->setHtmlBody('Вы успешно зарегистрировались как пользователь портала '.Yii::$app->name.".<br> Ваш ник: ". $model->username."<br> Эл. почта: ". $model->email."<br> Пароль: ". $model->passwordconfirmadmin.".<br> Чтобы активировать профиль, перейдите по ссылке. <br> <a href='". $succesurl."'>". $succesurl."</a>")
                ->send();
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('registration', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegistrationadmin()
    {
        $model = new Regformadmin();

        if (Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $person = new Person();
            $person->email = $model->email;
            $person->user_id = $model->id;
            $person->login = $model->username;
            $person->save();
            $succesurl = Yii::$app->urlManager->createAbsoluteUrl(['/site/confirm/'.$model->id]);
            Yii::$app->session->setFlash('successreg', "Вы успешно зарегистрировались как aдминистратор объекта.");
            Yii::$app->mailer->compose()
                ->setTo($model->email)
                ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                ->setSubject('Регистрация на '.Yii::$app->name)
                ->setHtmlBody('Вы успешно зарегистрировались как aдминистратор объекта на '.Yii::$app->name.".<br> Ваш логин: ". $model->username."<br> Эл. почта: ". $model->email."<br> Пароль: ". $model->passwordconfirm.".<br> Чтобы активировать профиль, перейдите по ссылке. <br> <a href='". $succesurl."'>". $succesurl."</a>")
                ->send();
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('registrationadmin', [
                'model' => $model,
            ]);
        }
    }

    public function actionConfirm($id)
    {
        if (Yii::$app->user->isGuest) {
            $model = User::findOne(['id'=>$id]);
            $model->status = 10;
            $model->lastvisited_at = new Expression('NOW()');
            $model->save();
            Yii::$app->user->login($model);
            return $this->redirect(['/site/usercab', 'id' => $id]);
        } elseif ($id != Yii::$app->user->getId()) {
            Yii::$app->session->setFlash("notify", "Выходите с другого аккаунта, чтобы активировать аккаунт.");
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash("notify", "Вы уже активировали аккаунт. переходите в личный кабинет.");
            return $this->redirect(['index']);
        }
    }

    public function actionDogovor($id)
    {
        if (($model= Object::findOne(['id'=>$id])) !== null) {
            $this->layout = 'print';
            return $this->render('dogovor', [
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionActonline($id)
    {
        if (($finance= Finance::findOne(['id'=>$id])) !== null) {
            $model= Object::findOne(['id'=>$finance->object_id]);
            $this->layout = 'print';
            return $this->render('act', [
                'model' => $model,
                'finance' => $finance,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionQvintaciaonline($id)
    {
        if (($finance= Finance::findOne(['id'=>$id])) !== null) {
            $model= Object::findOne(['id'=>$finance->object_id]);
            $this->layout = 'print';
            return $this->render('qvintacia', [
                'model' => $model,
                'finance' => $finance,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionSchetonline($id)
    {
        if (($finance= Finance::findOne(['id'=>$id])) !== null) {
            $model= Object::findOne(['id'=>$finance->object_id]);
            $this->layout = 'print';
            return $this->render('schet', [
                'model' => $model,
                'finance' => $finance,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionAct($id)
    {
        if (($model= Object::findOne(['id'=>$id])) !== null) {
            $this->layout = 'print';
            return $this->render('act', [
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionQvintacia($id)
    {
        if (($model= Object::findOne(['id'=>$id])) !== null) {
            $this->layout = 'print';
            return $this->render('qvintacia', [
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionSchet($id)
    {
        if (($model= Object::findOne(['id'=>$id])) !== null) {
            $this->layout = 'print';
            return $this->render('schet', [
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }
}
