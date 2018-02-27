<?php

namespace app\modules\cabinet\controllers;

use Yii;
use app\models\Booking;
use app\modules\cabinet\models\futured;
use app\modules\cabinet\models\past;
use app\modules\cabinet\models\canceled;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Message;
use app\models\Object;
use app\models\Servis;
use app\models\Calendar;
use app\models\Properties;
use app\models\Freeroom;
use app\models\Catroom;

/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends CabinetController
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionMessage($id)
   {
       $model = new Message();
       $this->layout = "main";
       if ($model->load(Yii::$app->request->post()) && $model->save()) {
           $messageurl = Yii::$app->urlManager->createAbsoluteUrl(['/owner/message']);
           if ($useremial = Properties::findOne(['object_id'=>$model->object_id, 'field_id'=>39])->field_value) {
               Yii::$app->mailer->compose()
                   ->setTo($useremial)
                   ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                   ->setSubject('Новое сообщение в  ' .Yii::$app->name)
                   ->setHtmlBody('Здравствуйте. Вам поступило сообщение от клиента портала ' . Yii::$app->name .  '. Его можно увидеть в разделе <a href="'. $messageurl.'">«Сообщения»</a> в Вашем кабинете на портале '. Yii::$app->name . '.')
                   ->send();
           }
           Yii::$app->session->setFlash('contact', 'Спасибо за Ваше сообщение. Как только администратор объекта даст ответ, он отобразится в Вашем кабинете в разделе «Сообщения». Мы уведомим Вас о поступлении ответа на Вашу электронную почту.');
           return $this->redirect(['/cabinet/message']);
       } else {
           return $this->redirect(['index']);
       }
   }
    /**
     * Lists all Booking models.
     * @return mixed
     */
    public function actionFuture()
    {
        $searchModel = new futured();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('future', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPast()
    {
        $searchModel = new past();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('past', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCancel()
    {
        $searchModel = new canceled();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('cancel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCancelbron($id)
    {
        $model = Booking::findOne(['id'=>$id]);
        $model->cancel = 1;
        $object = Object::findOne(['id'=>$model->object_id]);
        $service = implode(' ', explode('-', Servis::findOne(['id'=>$object->service])->aliastwo));
        $object_title = $service." ".$object->title;

        $cancelurl = Yii::$app->urlManager->createAbsoluteUrl(['/owner/booking/cancel/' . $model->object_id]);
        if ($useremial = Properties::findOne(['object_id'=>$object->id, 'field_id'=>38])->field_value) {
            Yii::$app->mailer->compose()
                ->setTo($useremial)
                ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                ->setSubject('Отмена брони в  ' .Yii::$app->name)
                ->setHtmlBody('Рай.рф. Сообщаем, что пользователь отменил бронь объекта '.$object_title.' №'.$object->id .'-'. $model->id.' Подробнее Вы сможете увидеть в своем личном кабинете в разделе  <a href="'. $cancelurl.'">«Бронирования»-«Отмененные поездки»</a>')
                ->send();
        }
        if ($model->save()) {
            $date = $model->from;
            while ($date < $model->to) {
                 if ($freeroom = Freeroom::findOne(['catroom_id'=>$model->catroom_id, 'check_date'=>$date])) {
                    if ($freeroom->room_count != Catroom::findOne(['id'=>$model->catroom_id])->count_rooms && $freeroom->room_count != Catroom::findOne(['id'=>$model->catroom_id])->count_rooms-1) {
                        $freeroom->room_count +=1;
                        $freeroom->save();
                    } else {
                        $freeroom->delete();
                    }
                 }
                 $date = date('Y-m-d',strtotime($date . "+1 days"));
             }
        }
        if (Properties::findOne(['object_id'=>$object->id, 'field_id'=>36])->field_value) {
            $phone = Properties::findOne(['object_id'=>$object->id, 'field_id'=>36])->field_value;
            $text = Yii::$app->name.'. Отмена брони '.$object->title.'. Подробнее в «Отмененные бронирования»';
            file_get_contents("https://smsc.ru/sys/send.php?login=marfelev&psw=rhv6x29k3go7&phones=".urlencode("$phone")."&mes=".urlencode("$text")."&sender=RAY.RF&charset=utf-8");
        }
        return $this->redirect(['cancel']);
    }

    public function actionInfo()
    {
        $model = User::findOne(['id'=>Yii::$app->user->getId()]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        } else {
            return $this->render('info', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
