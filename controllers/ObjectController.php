<?php

namespace app\controllers;

use Yii;
use app\models\Object;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use yii\web\UploadedFile;
use app\models\Bookmark;

class ObjectController extends Controller
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

    /**
     * Displays a single Object model.
     * @param integer $id
     * @return mixed
      */
    public function actionView($alias)
    {
        if (($model = Object::findOne(['alias'=>$alias])) !== null) {
            return $this->render('view', [
                'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionBookmark($id)
    {
        if ($bookmark = Bookmark::findOne(['user_id'=>Yii::$app->user->getId(), 'object_id'=>$id])) {
            $bookmark->delete();
        } else {
            $bookmark = new Bookmark();
            $bookmark->user_id = Yii::$app->user->getId();
            $bookmark->object_id = $id;
            $bookmark->save();
        }
        return $this->redirect(['/'.Object::findOne(['id'=>$id])->alias]);
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
