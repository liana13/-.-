<?php

namespace app\modules\cabinet\controllers;

use Yii;
use app\models\Review;
use app\models\Rate;
use app\models\User;
use app\models\Object;
use app\modules\cabinet\models\ReviewSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ReviewController extends CabinetController
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
     * Lists all Review models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Review model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Review();
        $rateint = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Спасибо за Ваш отзыв, он будет полезен для других туристов. Отзыв станет доступен после прохождения модерации. Увидеть свой отзыв Вы можете в личном аккаунте в разделе «Отзывы» после проверки нашим модератором.');
            $adminemail = User::findOne(['type'=>1])->email;
            $username = User::findOne(['id'=>$model->user_id])->username;
            $object = Object::findOne(['id'=>$model->object_id])->title;
            $reviews = Review::find()->where(['object_id'=>$model->object_id])->andWhere(['status'=>1])->all();
            foreach ($reviews as $review) {
                $rateint += $review->rate;
            }
            if (count($reviews)!=0) {
                $rateint = round($rateint/count($reviews), '1');
            } else {
                $rateint = 0;
            }
            if (Rate::findOne(['object_id'=>$model->object_id])) {
                $rate = Rate::findOne(['object_id'=>$model->object_id]);
                $rate->rate = $rateint;
                $rate->save();
            } else {
                $rate = new Rate();
                $rate->object_id = $model->object_id;
                $rate->rate = $rateint;
                $rate->save();
            }
            Yii::$app->mailer->compose()
                ->setTo($adminemail)
                ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                ->setSubject('Новый отзыв на '.Yii::$app->name)
                ->setHtmlBody("Пользователь ".$username." добавил новый отзыв к объекту ". $object.".")
                ->send();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Review model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $reviews = Review::find()->where(['object_id'=>$model->object_id])->andWhere(['!=', 'id', $id])->andWhere(['status'=>1])->all();
        $rateint = 0;
        if (count($reviews) != 0) {
            foreach ($reviews as $review) {
                $rateint += $review->rate;
            }
            $rateint = round($rateint/count($reviews), '1');
        }
        if (Rate::findOne(['object_id'=>$model->object_id])) {
            $rate = Rate::findOne(['object_id'=>$model->object_id]);
            $rate->rate = $rateint;
            $rate->save();
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Review model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Review the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
