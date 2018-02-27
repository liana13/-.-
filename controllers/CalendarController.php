<?php

namespace app\controllers;

use Yii;
use app\models\Calendar;
use app\models\Catroom;
use app\models\Freeroom;
use app\models\Price;
use app\models\CalendarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Object;
use app\models\Weekdays;
use app\models\Addprice;

/**
 * CalendarController implements the CRUD actions for Calendar model.
 */
class CalendarController extends Controller
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

    public function actionFreeroom($objectid, $catid, $check_date, $roomcount)
    {
        if ($freeroom = Freeroom::findOne(['catroom_id'=>$catid, 'check_date'=>$check_date])) {
            if ($catid != "") {
                if ($roomcount == Catroom::findOne(['id'=>$catid])->count_rooms) {
                    $freeroom->delete();
                } else {
                    $freeroom->room_count = $roomcount;
                    $freeroom->save();
                }
            } else {
                $freeroom->delete();
            }
            return true;
        } else {
            if ($roomcount != "") {
                $freeroom = new Freeroom();
                $freeroom->room_count = $roomcount;
                $freeroom->object_id = $objectid;
                $freeroom->catroom_id = $catid;
                $freeroom->check_date = $check_date;
                $freeroom->save();
            }
            return true;
        }
    }
    public function actionGetcolor($catroomid, $datefrom)
    {
        if (Calendar::findOne(['catroom_id'=>$catroomid, 'check_date'=>$datefrom])) {
            return true;
        } else {
            return false;
        }
    }

    public function actionRoomsperiod()
    {
        $model = new Freeroom();
        if (Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $date = $model->from;
            while ($date < $model->to) {
                if ($freeroom = Freeroom::findOne(['catroom_id'=>$model->catroom_id, 'check_date'=>$date])) {
                    $freeroom->room_count = $model->room_count;
                    $freeroom->save();
                } else {
                    $freeroom = new Freeroom();
                    $freeroom->room_count = $model->room_count;
                    $freeroom->object_id = $model->object_id;
                    $freeroom->catroom_id = $model->catroom_id;
                    $freeroom->check_date = $date;
                    $freeroom->save();
                }
                $date = date('Y-m-d',strtotime($date . "+1 days"));
            }
            Yii::$app->session->setFlash('catroomid', $model->catroom_id);
            Yii::$app->session->setFlash('success', 'Количество свободных номеров успешно сохраненo.');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('roomsperiod', [
                'model' => $model,
            ]);
        }
    }

    public function actionGetfreeroom($id, $checkdate, $i)
    {
        $data = [];

        if ($freeroom = Freeroom::findOne(['catroom_id'=>$id, 'check_date'=>$checkdate])) {
            $freeroomcount = $freeroom->room_count;
            $data[] =['i' => $i, 'date' => $freeroomcount];
            return json_encode($data);
        } else {
            return false;
        }
    }
    public function actionGetyandmfreerooms($y, $m, $catid)
    {
        $data = [];
        $d=cal_days_in_month(CAL_GREGORIAN,$m,$y);
        $catroom = Catroom::findOne(['id'=>$catid]);
        for ($i=1; $i <= $d; $i++) {
            if ($free_value=Freeroom::findOne(['check_date'=>date($y."-".$m."-".$i), 'catroom_id'=>$catid])) {
                $free_value1 = $free_value->room_count;
            } else {
                $free_value1 = $catroom->count_rooms;
            }
            $checkweek = date('w', strtotime(date($y."-".$m."-".$i)));
            if (Weekdays::findOne(['object_id'=>$catroom->object_id])) {
                $weekdays = Weekdays::findOne(['object_id'=>$catroom->object_id])->week_days;
            } else {
                $weekdays = "";
            }
             if ($price_value=Price::findOne(['check_date'=>date($y."-".$m."-".$i), 'catroom_id'=>$catid])){
                if (preg_match('/'.$checkweek.'/',$weekdays)){
                    $pricevalue = $price_value->weekend;
                } else {
                    $pricevalue = $price_value->work_day;
                }
            } else {
                if (preg_match('/'.$checkweek.'/',$weekdays)){
                    $pricevalue = $catroom->weekend;
                } else {
                    $pricevalue = $catroom->work_day;
                }
            }

            if ($addprice_value=Addprice::findOne(['check_date'=>date($y."-".$m."-".$i), 'catroom_id'=>$catid])){
               if (preg_match('/'.$checkweek.'/',$weekdays)){
                   $add_pricevalue = $addprice_value->weekend;
               } else {
                   $add_pricevalue = $addprice_value->work_day;
               }
           } else {
               if (preg_match('/'.$checkweek.'/',$weekdays)){
                   $add_pricevalue = $catroom->weekend_add;
               } else {
                   $add_pricevalue = $catroom->work_add;
               }
           }
            $data[] =['y' => $y, 'm' => $m,'cat'=>$catid, 'free_value'=>$free_value1,'price_value'=>$pricevalue,'addprice_value'=>$add_pricevalue, 'i'=>$i, 'd'=>$d];
        }
        return json_encode($data);
    }
    public function actionIsengaged($catroomid,$datefrom)
    {
        if (strtotime($datefrom) >= strtotime(date('Y-m-d'))) {
            $objid = Catroom::findOne(['id'=>$catroomid])->object_id;
            $freedates = Calendar::find()->where(['catroom_id'=>$catroomid, 'check_date'=>$datefrom])->one();
            if ($freedates) {
                $freedates->delete();
                return false;
            } else {
                $freedate = new Calendar;
                $freedate->status=1;
                $freedate->catroom_id=$catroomid;
                $freedate->check_date=$datefrom;
                $freedate->object_id=$objid;
                $freedate->save();
                return true;
            }
        }
    }

    /**
     * Updates an existing Calendar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Calendar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Calendar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Calendar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Calendar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
