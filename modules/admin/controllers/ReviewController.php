<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Review;
use app\models\Rate;
use app\models\search\ReviewSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Object;
use app\models\User;

/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ReviewController extends AdminController
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

    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $object = Object::findOne(['id'=>$model->object_id]);
        $useremail = User::findOne(['id'=>$object->user_id])->email;
        $model->status = 1;
        $rateint = 0;
        if ($model->save()) {
            $reviews = Review::find()->where(['object_id'=>$model->object_id])->andWhere(['status'=>1])->all();
            foreach ($reviews as $review) {
                $rateint += $review->rate;
            }
            $rateint = round($rateint/count($reviews), '1');
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
            $bookurl = Yii::$app->urlManager->createAbsoluteUrl(['/'.$object->alias]);
            Yii::$app->mailer->compose()
                ->setTo($useremail)
                ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                ->setSubject('Новый отзыв в ' ." " .Yii::$app->name)
                ->setHtmlBody('Здравствуйте. О Вашем объекте пользователь портала '.Yii::$app->name.' оставил отзыв. Для просмотра <a href="'. $bookurl.'">перейдите на страницу объекта</a>.')
                ->send();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeactivate($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        if ($model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
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
