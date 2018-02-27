<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Config;
use app\models\search\ConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
 use Imagine\Image\Box;


/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends AdminController
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
     * Updates an existing Config model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteimage() {
        $model = Config::findOne(['id'=>1]);
        if(is_file("upload/logo/".$model->logo)){
        unlink("upload/logo/".$model->logo);
        }
        $model->logo = "";
        $model->save();
        return $this->redirect(Yii::$app->request->referrer);
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $uploadedFile = UploadedFile::getInstance($model,'logo');
        $model->file = UploadedFile::getInstance($model,'file');

        $uploadedFile = UploadedFile::getInstance($model,'watermark');
        $model->water = UploadedFile::getInstance($model,'water');

        if (!empty($model->file)) {
            $model->file->saveAs('upload/logo/'.$model->file);
            $model->logo = 'upload/logo/'.$model->file;
        }
        if (!empty($model->water)) {
            $model->water->saveAs('upload/logo/'.$model->water);
            $model->watermark = 'upload/logo/'.$model->water;
                    Image::thumbnail('upload/logo/' . $model->water, 800, 600)
                            ->resize(new Box(800,600))
                            ->save('upload/logo/' . $model->water,
                                    ['quality' => 70]);
                    // unlink('upload/logo/' . $model->water);
            // \Yii::$app->imageresize->getUrl($model->watermark, 800, 600, 'inset', 1, $model->water);
            // $model->water->saveAs('upload/logo/'.$model->water);
            // $newimage = $model->watermark;
            // $img_width = 800 ;
            // $img_height = 600 ;
            // $tmp_large=imagecreatetruecolor($img_width, $img_height);
            // // $transparency = imagecolorallocatealpha($tmp_large, 127);
            // // imagefill($tmp_large, 0, 0, $transparency);
            //
            // list($width,$height) = getimagesize($newimage);
            //
            // $new_width = 800;
            // $new_height = 600;
            //
            // if (explode(".", $newimage)[1] == "png") {
            //     $im = imagecreatefrompng($newimage);
            //     // $black = imagecolorallocate($im, 255, 255, 255);
            //     // $im= imagecolortransparent($im, $black);
            //     imagecopyresampled($tmp_large,$im,0,0, 0, 0,$new_width, $new_height, $width, $height);
            //     if (imagepng($tmp_large, $newimage)) {
            //     }
            // } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
            //     $im = imagecreatefromjpeg($newimage);
            //     // $black = imagecolorallocate($im, 255, 255, 255);
            //     // $im=imagecolortransparent($im, $black);
            //     imagecopyresampled($tmp_large,$im,0,0, 0, 0,$new_width, $new_height, $width, $height);
            //     if (imagejpeg($tmp_large, $newimage)) {
            //     }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
            return $this->redirect(['config/update/'.$model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
