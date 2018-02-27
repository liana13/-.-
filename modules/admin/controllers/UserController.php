<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Review;
use app\models\Booking;
use app\models\Catroom;
use app\models\Image;
use app\models\Servis;
use app\models\Properties;
use app\models\Field;
use app\models\Coefficient;
use app\models\Object;
use app\models\Person;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminController
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
            if ($id == 1) {
                return $this->redirect(['user/update/'.$id]);
            } else{
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 10;
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $objects = Object::find()->where(['user_id'=>$id])->all();
        $persons = Person::find()->where(['user_id'=>$id])->all();
        if (count($objects) != 0) {
            foreach ($objects as $object) {
                $images = Image::find()->where(['object_id'=>$object->id])->all();
                $bookings = Booking::find()->where(['object_id'=>$object->id])->all();
                $coefficients = Coefficient::find()->where(['object_id'=>$object->id])->all();
                $properties = Properties::find()->where(['object_id'=>$object->id])->all();
                $catrooms = Catroom::find()->where(['object_id'=>$object->id])->all();
                $reviews = Review::find()->where(['object_id'=>$object->id])->all();
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
                $object->delete();
            }
        }
        if (count($persons)!=0) {
            foreach ($persons as $person) {
                $person->delete();
            }
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
