<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Object;
use app\models\search\ObjectSearch;
use yii\web\Controller;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Changetarif;
use app\models\Tarifend;
use app\models\Image;
use app\models\Booking;
use app\models\Coefficient;
use app\models\Properties;
use app\models\Catroom;
use app\models\Review;
use app\models\Rate;

/**
 * ObjectController implements the CRUD actions for Object model.
 */
class ObjectController extends AdminController
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
     * Lists all Object models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ObjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewobject($id)
    {
        $model = $this->findModel($id);
        if (explode('edit%5D=', Yii::$app->request->referrer)[1] ==1) {
            $model->edit = 0;
            $model->save();
            return $this->redirect(['/'.$model->alias]);
        } else {
            return $this->redirect(['/'.$model->alias]);
        }
    }
    /**
     * Deletes an existing Object model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     public function actionDelete($id)
     {
         $images = Image::find()->where(['object_id'=>$id])->all();
         $bookings = Booking::find()->where(['object_id'=>$id])->all();
         $coefficients = Coefficient::find()->where(['object_id'=>$id])->all();
         $properties = Properties::find()->where(['object_id'=>$id])->all();
         $catrooms = Catroom::find()->where(['object_id'=>$id])->all();
         $reviews = Review::find()->where(['object_id'=>$id])->all();
         $rate = Rate::find()->where(['object_id'=>$id])->one();
         if ($rate) {
             $rate->delete();
         }
         foreach ($images as $image) {
             $image->delete();
         }
         foreach ($bookings as $booking) {
             $booking->delete();
         }
         foreach ($coefficients as $coefficient) {
             $coefficient->delete();
         }
         foreach ($properties as $propertie) {
             $propertie->delete();
         }
         foreach ($catrooms as $catroom) {
             $catroom->delete();
         }
         foreach ($reviews as $review) {
             $review->delete();
         }
        $folder = "upload/images/".$id;
        $files = glob($folder.'/*');
        foreach($files as $file){
            // if(is_file($file)){
            unlink($file);
            // }
            if (is_dir($folder)) {
            rmdir($folder);
            }
        }
         $this->findModel($id)->delete();
         return $this->redirect(['index']);
     }

     public function actionActivate($id)
     {
         $model = $this->findModel($id);
         $model->active = 1;
         $model->updated_at = new Expression('NOW()');
         $model->save();
         return $this->redirect(Yii::$app->request->referrer);
     }

     public function actionDeactivate($id)
     {
         $model = $this->findModel($id);
         $model->active = 0;
         if ($model->save()) {
             return $this->redirect(Yii::$app->request->referrer);
         } else {
             return $this->redirect(['index']);
         }
     }

    public function actionChangetarif()
    {
        $tarif = new Changetarif();
        if ($tarif->load(Yii::$app->request->post()) && $tarif->change()) {
            return $this->redirect(Yii::$app->request->referrer);
        }  else {
            return $this->redirect(['index']);
        }
    }

    public function actionAddend()
    {
        $end = new Tarifend();
        if ($end->load(Yii::$app->request->post()) && $end->change()) {
            return $this->redirect(Yii::$app->request->referrer);
        }  else {
            return $this->redirect(['index']);
        }
    }

     public function actionChangeend($id)
     {
         $model = $this->findModel($id);
         $oldend = date("Y-m-d");
         $end = strtotime($oldend);
         $newend = strtotime('+ 1 year', $end);
         $newend = date("Y-m-d", $newend);
         if ($model->new_tarif == 0 || $model->new_tarif == 4) {
             $model->end_date = Null;
             $model->act_oplata = $oldend;
             $model->updated_at = new Expression('NOW()');
             if ($model->new_tarif == 4) {
                 $model->active_online = $oldend;
             }
             $model->tarif_id = $model->new_tarif;
         } else {
             $model->end_date = explode(" ", $newend)[0];
             $model->act_oplata = $oldend;
             $model->updated_at = new Expression('NOW()');
             $model->tarif_id = $model->new_tarif;
         }
         if ($model->new_tarif) {
             $model->tarif_id = $model->new_tarif;
         }

         if ($model->save()) {
             if ($model->tarif_id == 4) {
                 $reviews = Review::find()->where(['object_id'=>$model->id])->andWhere(['status'=>1])->all();
                 $rateint = 0;
                 if (count($reviews) != 0) {
                     foreach ($reviews as $review) {
                         $rateint += $review->rate;
                     }
                     $rateint = round($rateint/count($reviews), '1');
                 }
                 if (Rate::findOne(['object_id'=>$model->id])) {
                     $rate = Rate::findOne(['object_id'=>$model->id]);
                     $rate->rate = $rateint;
                     $rate->save();
                 } else {
                     $rate = new Rate();
                     $rate->object_id = $model->id;
                     $rate->rate = $rateint;
                     $rate->save();
                 }
             } else {
                 if (Rate::findOne(['object_id'=>$model->id])) {
                     $rate = Rate::findOne(['object_id'=>$model->id]);
                     $rate->delete();
                 }
                 $reviews = Review::find()->where(['object_id'=>$model->id])->all();
                 if (count($reviews) != 0) {
                     foreach ($reviews as $review) {
                         $review->delete();
                     }
                 }
             }
             return $this->redirect(Yii::$app->request->referrer);
         } else {
             return $this->redirect(['index']);
         }
     }

    /**
     * Finds the Object model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Object the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Object::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
