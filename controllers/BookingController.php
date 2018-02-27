<?php

namespace app\controllers;

use Yii;
use app\models\Booking;
use app\models\BookingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Object;
use app\models\User;
use app\models\Calendar;
use app\models\Catroom;
use app\models\Servis;
use app\models\Properties;
use app\models\Freeroom;

/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends Controller
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

    /**
     * Creates a new Booking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Booking();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->name = $model->surname.' '.$model->username;
            if ($model->save()) {
                $object = Object::findOne(['id'=>$model->object_id]);
                $ownerurl = Yii::$app->urlManager->createAbsoluteUrl(['/owner/booking/future/'.$object->id]);
                $object->unread = 0;
                $object->save();
                $service = implode(' ', explode('-', Servis::findOne(['id'=>$object->service])->aliastwo));
                $objtitle = $service." ".$object->title;
                $bookurl = Yii::$app->urlManager->createAbsoluteUrl(['/cabinet/booking/future']);
                $url = Yii::$app->request->baseUrl.'/cabinet/booking/future';
                $useremail = User::findOne(['id'=>$model->user_id])->email;
                Yii::$app->mailer->compose()
                    ->setTo($useremail)
                    ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                    ->setSubject('Бронирование в ' ." " .Yii::$app->name)
                    ->setHtmlBody('Спасибо за Ваше бронирование. Вы можете распечатать или сфотографировать ваучер на заселение в личном кабинете в разделе <a href="'. $bookurl.'">«Бронирования-Будущие поездки»</a>. Ваучер на заселение нужно показать администратору объекта при заселении. Для подробной информации перейдите в раздел «Бронирования» в Вашем кабинете на портале '
                    . Yii::$app->name.'.')
                    ->send();
                if ($user_object = Properties::findOne(['object_id'=>$object->id, 'field_id'=>38])->field_value) {
                    Yii::$app->mailer->compose()
                        ->setTo($user_object)
                        ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                        ->setSubject('Бронирование в ' ." " .Yii::$app->name)
                        ->setHtmlBody(' Здравствуйте, от пользователя портала '.Yii::$app->name.' поступило бронирование Вашего объекта '.$objtitle.' №'.$object->id .'-'. $model->id.'. Для просмотра перейдите в Ваш аккаунт в раздел <a href="'. $ownerurl.'">«Бронирования»</a>')
                        ->send();
                }
                $catroom = Catroom::findOne(['id'=>$model->catroom_id]);
                $date = $model->from;
                while ($date < $model->to) {
                    if ($freeroom = Freeroom::findOne(['catroom_id'=>$model->catroom_id, 'check_date'=>$date])) {
                         if ($freeroom->room_count != 0) {
                             $freeroom->room_count -= 1;
                            $freeroom->save();
                         }
                    } else {
                        $freeroomcreate = new Freeroom();
                        $freeroomcreate->room_count = $catroom->count_rooms-1;
                        $freeroomcreate->object_id = $model->object_id;
                        $freeroomcreate->catroom_id = $model->catroom_id;
                        $freeroomcreate->check_date = $date;
                        $freeroomcreate->save();
                    }
                    $date = date('Y-m-d',strtotime($date . "+1 days"));
                }
                //phone_message
                if (Properties::findOne(['object_id'=>$object->id, 'field_id'=>36])) {
                    $phone = Properties::findOne(['object_id'=>$object->id, 'field_id'=>36])->field_value;
                    $text = Yii::$app->name.'. Бронь '.$object->title.'. Подробнее в «Бронирования»';
                    file_get_contents("https://smsc.ru/sys/send.php?login=marfelev&psw=rhv6x29k3go7&phones=".urlencode("$phone")."&mes=".urlencode("$text")."&sender=RAY.RF&charset=utf-8");
                }

            }
            Yii::$app->session->setFlash("bron", "Спасибо за Ваше бронирование. Вы можете распечатать или сфотографировать ваучер на заселение в личном кабинете в разделе <a href='". $bookurl."'>«Бронирования-Будущие поездки»</a>. Ваучер на заселение нужно показать администратору объекта при заселении.");
            return $this->redirect(Yii::$app->request->baseUrl.'/'.$object->alias);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
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
