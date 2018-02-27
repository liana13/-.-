<?php

namespace app\modules\owner\controllers;

use Yii;
use app\models\Booking;
use app\modules\owner\models\futured;
use app\modules\owner\models\past;
use app\modules\owner\models\canceled;
use app\models\User;
use app\models\Object;
use app\models\Calendar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Message;
use app\models\Servis;
use app\models\Freeroom;
use app\models\Catroom;

class BookingController extends OwnerController
{
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPrintbron()
    {
        $model = new Booking();
        $this->layout = 'print';
        if (Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            // if ($model->type=='future') {
                // $type = "Будущие";
                $booking = Booking::find()->where(['>=','from',$model->from_date])
                            // ->andWhere(['>=', 'from', date("Y-m-d")])->andWhere(['cancel' => 0])
                            ->andWhere(['object_id'=>$model->objid])
                            ->orderBy('from ASC')->all();
            // } elseif ($model->type=='past') {
                // $type = "Прошедшие";
                // $booking = Booking::find()->where(['>=','from',$model->from_date])
                //             ->andWhere(['<', 'from', date("Y-m-d")])->andWhere(['cancel' => 0])
                //             ->andWhere(['object_id'=>$model->objid])
                //             ->orderBy('from ASC')->all();
            // } elseif ($model->type=='cancel') {
                // $type = "Отмененные";
                // $booking = Booking::find()->where(['>=','from',$model->from_date])->andWhere(['cancel' => 1])->andWhere(['object_id'=>$model->objid])
                //             ->orWhere(['cancel' => 2])->andWhere(['object_id'=>$model->objid])->andwhere(['>=','from',$model->from_date])
                //             ->orderBy('from ASC')->all();
            // }
            $object = Object::findOne(['id'=>$model->objid]);
            $servicetitle = "";
            foreach (explode('-', Servis::findOne(['id'=>$object->service])->aliastwo) as $stitle) {
                $servicetitle .= $stitle . " ";
            }
            $objecttitle = $servicetitle . $object->title;
            if (count($booking) != 0) {
                return $this->render('print', [
                    'booking' => $booking,
                    // 'type' => $type,
                    'objecttitle'=>$objecttitle,
                    'date'=>$model->from_date,
                ]);
            } else {
                Yii::$app->session->setFlash("danger", "Бронирований с выбранной датой нету.");
                return $this->redirect(Yii::$app->request->referrer);
            }
        }  else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }
    public function actionFuture($id)
    {
        $searchModel = new futured();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $objectid = Object::findOne(['id'=>$id]);
        $objectid->unread = 1;
        $objectid->save();
        return $this->render('future', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionPast($id)
    {
        $searchModel = new past();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('past', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCancel($id)
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
        $object = Object::findOne(['id'=>$model->object_id]);
        $service = implode(' ', explode('-', Servis::findOne(['id'=>$object->service])->aliastwo));
        $object_title = $service." ".$object->title;
        $model->cancel = 2;
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
        $useremial = User::findOne(['id'=>$model->user_id])->email;
        $cancelurl = Yii::$app->urlManager->createAbsoluteUrl(['/cabinet/booking/cancel/' . $model->object_id]);
        Yii::$app->mailer->compose()
            ->setTo($useremial)
            ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
            ->setSubject('Отмена брони в ' .Yii::$app->name)
            ->setHtmlBody('Рай.рф. Сообщаем, что владелец отменил бронь объекта '.$object_title.' №'.$model->object_id .'-'.$id.' Подробнее Вы сможете увидеть в своем личном кабинете в разделе  <a href="'. $cancelurl.'">«Бронирования»-«Отмененные поездки»</a>')
            ->send();
        return $this->redirect(['booking/cancel/'.$model->object_id]);
    }

    public function actionIncomplete($id)
    {
        $model = Booking::findOne(['id'=>$id]);
        $model->status = 0;
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
        return $this->redirect(Yii::$app->request->referrer);
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

    public function actionMessage($id)
   {
       $model = new Message();
       $this->layout = "main";
       if ($model->load(Yii::$app->request->post()) && $model->save()) {
           $useremial = User::findOne(['id'=>$model->user_two])->email;
           Yii::$app->mailer->compose()
               ->setTo($useremial)
               ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
               ->setSubject('Новое сообщение в  ' .Yii::$app->name)
               ->setHtmlBody('Здравствуйте, Вам поступило сообщение от пользователя портала ' . Yii::$app->name .  '. Чтобы увидеть сообщение и ответить на него пожалуйста, перейдите в свой личный кабинет на портале ' . Yii::$app->name .  ' в раздел "Сообщения".')
               ->send();
           Yii::$app->session->setFlash('contact', 'Спасибо за Ваше сообщение. Как только администратор объекта даст ответ, он отобразится в Вашем кабинете в разделе «Сообщения». Мы уведомим Вас о поступлении ответа на Вашу электронную почту.');
           return $this->redirect(['/owner/message']);
       } else {
           return $this->redirect(['index']);
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
