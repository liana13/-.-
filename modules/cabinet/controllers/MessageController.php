<?php

namespace app\modules\cabinet\controllers;

use Yii;
use app\models\Message;
use app\models\search\MessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Properties;
use app\models\User;

class MessageController extends CabinetController
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
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        $messages = Message::find()->where(['user_one'=>Yii::$app->user->getId()])->orWhere(['user_two'=>Yii::$app->user->getId()])
                    ->orderBy('id DESC')->all();
        if (count($messages)!=0) {
            $dialogue = Message::find()->where(['user_one'=>Yii::$app->user->getId()])->andWhere(['statuscabinet' => null])
                        ->orWhere(['user_two'=>Yii::$app->user->getId()])->andWhere(['statuscabinet' => null])
                        ->groupBy('dialogue_id')->orderBy('id DESC')->all();
            $unread = Message::find()->where(['user_two'=>Yii::$app->user->getId()])->andWhere(['status' => 0])->andWhere(['statuscabinet' => null])
                        ->groupBy('dialogue_id')->orderBy('id DESC')->all();
        } else {
            $dialogue = [];
            $unread = [];
        }
        return $this->render('index', [
            'dialogue' => $dialogue,
            'unread' => $unread,
        ]);
    }

    // actionDialog

    public function actionDialog($id)
    {
        $messages = Message::find()->where(['user_one'=>Yii::$app->user->getId()])
            ->orWhere(['user_two'=>Yii::$app->user->getId()])->andWhere(['dialogue_id'=>$id])->all();

        if (count($messages) !== 0) {
            foreach ($messages as $message) {
                if ($message->user_two == Yii::$app->user->getId()) {
                    $message->status = 1;
                    $message->save();
                }
            }
            return $this->render('dialog', [
                'messages' => $messages,
            ]);
        } else {
            throw new NotFoundHttpException('Такой переписки нет.');
        }
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Message();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($useremial = Properties::findOne(['object_id'=>$model->object_id, 'field_id'=>39])->field_value) {
                $messageurl = Yii::$app->urlManager->createAbsoluteUrl(['/owner/message']);
                Yii::$app->mailer->compose()
                    ->setTo($useremial)
                    ->setFrom(['noreply@'.Yii::$app->request->serverName=>Yii::$app->name])
                    ->setSubject('Новое сообщение в  ' .Yii::$app->name)
                    ->setHtmlBody('Здравствуйте. Вам поступило сообщение от клиента портала ' . Yii::$app->name .  '. Его можно увидеть в разделе <a href="'. $messageurl.'">«Сообщения»</a> в Вашем кабинете на портале '. Yii::$app->name . '.')
                    ->send();
            }

            return $this->redirect(['message/dialog/'.$model->dialogue_id]);
        } else {
            return $this->redirect(['message/index']);
        }
    }

    public function actionDelete($id)
    {
        $dialogue = Message::find()->where(['dialogue_id'=>$id])->all();
        foreach ($dialogue as $item) {
            $item->statuscabinet = 1;
            $item->save();
        }
        Yii::$app->session->setFlash('success', 'Переписка успешно удалена.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
