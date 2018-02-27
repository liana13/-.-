<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Booking;
use app\models\Calendar;
use app\models\BookingSearch;
use app\models\search\CanceledSearch;
use app\models\IncompletedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends AdminController
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
     * Lists all Booking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCanceled()
    {
        $searchModel = new CanceledSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('canceled', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIncompleted()
    {
        $searchModel = new IncompletedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('incompleted', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        if ($cals = Calendar::findOne(['book_id'=>$id])) {
            $cals->delete();
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
