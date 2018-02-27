<?php

namespace app\controllers;

use Yii;
use app\models\Price;
use app\models\Addprice;
use app\models\PriceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Catroom;
use app\models\Weekdays;
use yii\helpers\Date;

/**
 * PriceController implements the CRUD actions for Price model.
 */
class PriceController extends Controller
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
    public function actionPrice($objectid,$catid, $check_date, $price)
    {
        if (Weekdays::findOne(['object_id'=>$objectid])) {
            $weekdays = Weekdays::findOne(['object_id'=>$objectid])->week_days;
        } else {
            $weekdays = "";
        }
        $days = new \DateTime($check_date);
        $checkweek = $days->format('w');
        if ($pricecreate = Price::findOne(['catroom_id'=>$catid, 'check_date'=>$check_date])) {
            if (!empty($price)) {
                if (preg_match('/'.$checkweek.'/',$weekdays)) {
                    $pricecreate->work_day = Catroom::findOne(['id'=>$catid])->work_day;
                    $pricecreate->weekend = $price;
                    if ($pricecreate->save()) {
                        return true;
                    }
                } else {
                    $pricecreate->weekend = Catroom::findOne(['id'=>$catid])->weekend;
                    $pricecreate->work_day = $price;
                    if ($pricecreate->save()) {
                        return true;
                    }
                }
            } else {
                $pricecreate->delete();
            }
        } else {
            if (preg_match('/'.$checkweek.'/',$weekdays)) {
                $pricecreate = new Price();
                $pricecreate->weekend = $price;
                $pricecreate->work_day = Catroom::findOne(['id'=>$catid])->work_day;
                $pricecreate->object_id = $objectid;
                $pricecreate->catroom_id = $catid;
                $pricecreate->check_date = $check_date;
                if ($pricecreate->save()) {
                    return true;
                }
                echo "string";

            } else {
                $pricecreate = new Price();
                $pricecreate->work_day = $price;
                $pricecreate->weekend = Catroom::findOne(['id'=>$catid])->weekend;
                $pricecreate->object_id = $objectid;
                $pricecreate->catroom_id = $catid;
                $pricecreate->check_date = $check_date;
                if ($pricecreate->save()) {
                    return true;
                }
            }
        }
    }

    public function actionPriceperiod()
    {
        $price = new Price();
        if ($price->load(Yii::$app->request->post())) {
            $date = $price->from;
            while ($date < $price->to) {
                if ($pricecreate = Price::findOne(['catroom_id'=>$price->catroom_id, 'check_date'=>$date])) {
                    $pricecreate->work_day = $price->work_day;
                    $pricecreate->weekend = $price->weekend;
                    $pricecreate->save();
                } else {
                    $pricecreate = new Price();
                    if (!empty($price->work_day)) {
                        $pricecreate->work_day = $price->work_day;
                    } else {
                        $pricecreate->work_day = Catroom::findOne(['object_id'=>$price->object_id])->work_day;
                    }
                    if (!empty($price->weekend)) {
                        $pricecreate->weekend = $price->weekend;
                    } else {
                        $pricecreate->weekend = Catroom::findOne(['object_id'=>$price->object_id])->weekend;
                    }
                    $pricecreate->object_id = $price->object_id;
                    $pricecreate->catroom_id = $price->catroom_id;
                    $pricecreate->check_date = $date;
                    $pricecreate->save();
                }
                $date = date('Y-m-d',strtotime($date . "+1 days"));
            }
            Yii::$app->session->setFlash('catroomid', $price->catroom_id);
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionGetprice($id, $checkdate, $i)
    {
        $data = [];
        $objectid = Catroom::findOne(['id'=>$id])->object_id;
        if (Weekdays::findOne(['object_id'=>$objectid])) {
            $weekdays = Weekdays::findOne(['object_id'=>$objectid])->week_days;
        } else {
            $weekdays = "";
        }
        $days = new \DateTime($checkdate);
        $checkweek = $days->format('w');
        if ($price = Price::findOne(['catroom_id'=>$id, 'check_date'=>$checkdate])) {
            if (preg_match('/'.$checkweek.'/',$weekdays)) {
                $pricework_day = $price->weekend;
            } else {
                $pricework_day = $price->work_day;
            }
        } else {
            if (preg_match('/'.$checkweek.'/',$weekdays)) {
                $pricework_day = Catroom::findOne(['id'=>$id])->weekend;
            } else {
                $pricework_day = Catroom::findOne(['id'=>$id])->work_day;
            }
        }
        $data[] =['i' => $i, 'date' => $pricework_day];

        return json_encode($data);
    }

    // *****ADD Price

    public function actionAddprice($objectid,$catid, $check_date, $price)
    {
        if (Weekdays::findOne(['object_id'=>$objectid])) {
            $weekdays = Weekdays::findOne(['object_id'=>$objectid])->week_days;
        } else {
            $weekdays = "";
        }
        $days = new \DateTime($check_date);
        $checkweek = $days->format('w');
        if ($pricecreate = Addprice::findOne(['catroom_id'=>$catid, 'check_date'=>$check_date])) {
            if (!empty($price)) {
                if (preg_match('/'.$checkweek.'/',$weekdays)) {
                    $pricecreate->work_day = Catroom::findOne(['id'=>$catid])->work_day;
                    $pricecreate->weekend = $price;
                    if ($pricecreate->save()) {
                        return true;
                    }
                } else {
                    $pricecreate->weekend = Catroom::findOne(['id'=>$catid])->weekend;
                    $pricecreate->work_day = $price;
                    if ($pricecreate->save()) {
                        return true;
                    }
                }
            } else {
                $pricecreate->delete();
            }
        } else {
            if (!empty($price)) {
                if (preg_match('/'.$checkweek.'/',$weekdays)) {
                    $pricecreate = new Addprice();
                    $pricecreate->weekend = $price;
                    $pricecreate->work_day = Catroom::findOne(['id'=>$catid])->work_day;
                    $pricecreate->object_id = $objectid;
                    $pricecreate->catroom_id = $catid;
                    $pricecreate->check_date = $check_date;
                    if ($pricecreate->save()) {
                        return true;
                    }
                } else {
                    $pricecreate = new Addprice();
                    $pricecreate->work_day = $price;
                    $pricecreate->weekend = Catroom::findOne(['id'=>$catid])->weekend;
                    $pricecreate->object_id = $objectid;
                    $pricecreate->catroom_id = $catid;
                    $pricecreate->check_date = $check_date;
                    if ($pricecreate->save()) {
                        return true;
                    }
                }
            }
        }
    }

    public function actionAddpriceperiod()
    {
        $price = new Addprice();
        if ($price->load(Yii::$app->request->post())) {
            $date = $price->from;
            while ($date < $price->to) {
                if ($pricecreate = Addprice::findOne(['catroom_id'=>$price->catroom_id, 'check_date'=>$date])) {
                    $pricecreate->work_day = $price->work_day;
                    $pricecreate->weekend = $price->weekend;
                    $pricecreate->save();
                } else {
                    $pricecreate = new Addprice();
                    if (!empty($price->work_day)) {
                        $pricecreate->work_day = $price->work_day;
                    } else {
                        $pricecreate->work_day = Catroom::findOne(['object_id'=>$price->object_id])->work_day;
                    }
                    if (!empty($price->weekend)) {
                        $pricecreate->weekend = $price->weekend;
                    } else {
                        $pricecreate->weekend = Catroom::findOne(['object_id'=>$price->object_id])->weekend;
                    }
                    $pricecreate->object_id = $price->object_id;
                    $pricecreate->catroom_id = $price->catroom_id;
                    $pricecreate->check_date = $date;
                    $pricecreate->save();
                }
                $date = date('Y-m-d',strtotime($date . "+1 days"));
            }
            Yii::$app->session->setFlash('catroomid', $price->catroom_id);
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionGetaddprice($id, $checkdate, $i)
    {
        $data = [];
        $objectid = Catroom::findOne(['id'=>$id])->object_id;
        if (Weekdays::findOne(['object_id'=>$objectid])) {
            $weekdays = Weekdays::findOne(['object_id'=>$objectid])->week_days;
        } else {
            $weekdays = "";
        }
        $days = new \DateTime($checkdate);
        $checkweek = $days->format('w');
        if ($price = Addprice::findOne(['catroom_id'=>$id, 'check_date'=>$checkdate])) {
            if (preg_match('/'.$checkweek.'/',$weekdays)) {
                $pricework_day = $price->weekend;
            } else {
                $pricework_day = $price->work_day;
            }
        } else {
            if (preg_match('/'.$checkweek.'/',$weekdays)) {
                $pricework_day = Catroom::findOne(['id'=>$id])->weekend_add;
            } else {
                $pricework_day = Catroom::findOne(['id'=>$id])->work_add;
            }
        }
        $data[] =['i' => $i, 'date' => $pricework_day];

        return json_encode($data);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Price model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Price the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Price::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
