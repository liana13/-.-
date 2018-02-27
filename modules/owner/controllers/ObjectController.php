<?php

namespace app\modules\owner\controllers;


use Yii;
use app\models\Object;
use app\modules\owner\models\ObjectForm;
use app\modules\owner\models\ObjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\web\UploadedFile;
use app\models\Image;
use app\models\Booking;
use app\models\Coefficient;
use app\models\Properties;
use app\models\Catroom;
use app\models\Review;
use app\models\Rate;
use app\models\Changetarif;
use app\models\Servis;
use app\models\Tarif;
use app\models\Config;

class ObjectController extends OwnerController
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

    /**
     * Creates a new Object model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->render('create');
    }

    public function actionAdd($id)
    {
        if (($model = Tarif::findOne($id)) !== null) {
            Yii::$app->session->set('tarifid', $id);

            $this->layout = 'main-object';
            $model = new ObjectForm();
            if ($model->load(Yii::$app->request->post())) {
                $locality = Config::findOne(['id'=>1])->title;
                if(!empty($model->minprice) && empty($model->curency)) {
                    $model->curency = 4;
                }
                $modelalias = Servis::findOne(['id'=>$model->service])->aliastwo ."-". implode('-',explode(' ',$model->title)) ."-". implode('-',explode(' ',$locality));
                if ($model->create()) {
                    if ($model->new_tarif==4) {
                        $rate = new Rate();
                        $rate->object_id = Object::findOne(['alias'=>$modelalias]);
                        $rate->rate = '0';
                        $rate->save();
                    }
                    Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
                    return $this->redirect(['/update/'.$modelalias]);
                } else {
                    Yii::$app->session->setFlash('danger', 'Объект с таким урл-ом уже существует.');
                    return $this->render('add', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('add', [
                    'model' => $model,
                ]);
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
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
           if(is_file($file))
             unlink($file);
         }
         if (is_dir($folder)) {
             rmdir($folder);
         }
         $this->findModel($id)->delete();
         return $this->redirect(['index']);
     }


   public function actionChangetarif()
   {
       $tarif = new Changetarif();
       if ($tarif->load(Yii::$app->request->post()) && $tarif->changerequest()) {
           $useremail = User::findOne(['type'=>1])->email;
           $username = User::findOne(['id'=>Yii::$app->user->getId()])->name;
           $objectid = $tarif->id;
           Yii::$app->mailer->compose()
               ->setTo($useremail)
               ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
               ->setSubject('Запрос на смену тарифа в ' .Yii::$app->name)
               ->setHtmlBody('Пользователь портала ' . Yii::$app->name .'<b> '. $username.' </b> запросил смену тарифа для объекта <b>'.$objectid.'</b>.')
               ->send();
           Yii::$app->session->setFlash('changemytarif', '');
           return $this->redirect(Yii::$app->request->referrer);
       }  else {
           return $this->redirect(['index']);
       }
   }

    protected function findModel($id)
    {
        if (($model = Object::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
