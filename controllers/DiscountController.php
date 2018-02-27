<?php

namespace app\controllers;

use Yii;
use app\models\Discount;
use app\models\DiscountSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DiscountController implements the CRUD actions for Discount model.
 */
class DiscountController extends Controller
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
     * Creates a new Discount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Discount();
        if ($model->load(Yii::$app->request->post())) {
            $discs = Discount::find()->where(['catroom_id'=>$model->catroom_id])->all();
            foreach ($discs as $disc) {
                $disc->delete();
            }
            for ($j=1; $j <= 10 ; $j++) {
                $age = 'age'.$j;
                $fromage = 'fromage'.$j;
                $percent = 'percent'.$j;
                if (!empty($model->$age) && !empty($model->$percent)) {
                    $ages = new Discount();
                    $ages->object_id = $model->object_id;
                    $ages->catroom_id = $model->catroom_id;
                    $ages->age = $model->$age;
                    $ages->fromage = $model->$fromage;
                    $ages->percent = $model->$percent;
                    $ages->save();

                }
            }
            Yii::$app->session->setFlash('catroomid', $model->catroom_id);
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Updates an existing Discount model.
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
     * Deletes an existing Discount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->session->setFlash('catroomid', $model->catroom_id);
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Discount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Discount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Discount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
