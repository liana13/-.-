<?php
namespace app\controllers;

use Yii;
use app\models\Object;
use app\models\ObjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Properties;
use app\models\Person;
use app\models\Country;
use app\models\Region;
use app\models\Locality;
use app\models\Servis;
use app\models\Image;
use yii\web\UploadedFile;
use app\models\Field;
use app\models\Catroom;
use app\models\Childage;
use app\models\PriceSearch;
use app\models\Price;
use app\models\PersonSearch;
use app\models\CalendarSearch;
use app\models\Calendar;
use app\models\Tarif;
use app\models\Finance;
use app\models\Rate;
use app\models\Review;
use app\models\Weekdays;
use app\models\Config;
use yii\imagine\Image as Imagesize;
 use Imagine\Image\Box;
class UpdateController extends Controller
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
     * Updates an existing Object model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex($alias)
    {
        if (($model = Object::findOne(['alias'=>$alias])) !== null) {
            Yii::$app->session->set('tarifid', Tarif::findOne(['tarifid'=>$model->tarif_id])->id);
            if (!Yii::$app->user->isGuest && (Yii::$app->user->getId() == $model->user_id || User::findOne(['id'=>Yii::$app->user->getId()])->type == 1)) {
                $model = Object::findOne(['alias'=>$alias]);
                $id = $model->id;
                if ($model->load(Yii::$app->request->post())) {
                    $service = implode(" ", explode('-',Servis::findOne(['id'=>$model->service])->aliastwo));
                    $model->full_title = $service ." ". $model->title;
                    if (!empty($model->weekdays)) {
                        if ($wds = Weekdays::findOne(['object_id'=>$model->id])) {
                            $wds->week_days = implode(',', $model->weekdays);
                            $wds->save();
                        } else {
                            $wh = new Weekdays();
                            $wh->user_id = $model->user_id;
                            $wh->object_id = $model->id;
                            $wh->week_days = implode(',', $model->weekdays);
                            $wh->save();
                        }
                    } elseif ($wds = Weekdays::findOne(['object_id'=>$model->id])) {
                        $wds->delete();
                    }
                    $reviews = Review::find()->where(['object_id'=>$model->id])->andWhere(['status'=>1])->all();
                    if ($model->allow_review == 1) {
                        $rateint = 0;
                        if (count($reviews)!=0) {
                            foreach ($reviews as $review) {
                                $rateint += $review->rate;
                            }
                            $rateint = round($rateint/count($reviews), '1');
                            if (Rate::findOne(['object_id'=>$model->id])) {
                                $rate = Rate::findOne(['object_id'=>$model->id]);
                                $rate->rate = $rateint;
                                $rate->save();
                            } else {
                                $rate = new Rate();
                                $rate->object_id = $id;
                                $rate->rate = $rateint;
                                $rate->save();
                            }
                        }
                    } else {
                        if (Rate::findOne(['object_id'=>$model->id])) {
                            $rate = Rate::findOne(['object_id'=>$model->id]);
                            $rate->rate = 0;
                            $rate->save();
                        }
                    }
                    $model->edit =1;
                    $title = implode("-", explode(' ',$model->title));
                    $locality = Config::findOne(['id'=>1])->title;
                    $model->alias = Servis::findOne(['id'=>$model->service])->aliastwo ."-". $title."-". implode('-',explode(' ',$locality));

                    $field7 = Properties::findOne(['field_id' => 7, 'object_id'=>$model->id]);
                    if ($field7) {
                        $field7->field_value = $model->phone;
                        $field7->save();
                    }

                    $field5 = Properties::findOne(['field_id' => 5, 'object_id'=>$model->id]);
                    if ($field5) {
                        $field5->field_value = $model->address;
                        $field5->save();
                    }

                    $fieldpice = Properties::findOne(['field_id' => 12, 'object_id'=>$model->id]);
                    if ($fieldpice) {
                        $fieldpice->field_value = $model->price;
                        $fieldpice->save();
                    } else {
                        $fieldpice= new Properties();
                        $fieldpice->object_id = $model->id;
                        if ($model->price) {
                            $fieldpice->field_id = 12;
                            $fieldpice->field_value = $model->price;
                            $fieldpice->save();
                        }
                    }

                    $zaezdfield = Properties::findOne(['field_id' => 49, 'object_id'=>$model->id]);
                    if ($zaezdfield) {
                        if ($model->zaezdto) {
                            $zaezdfield->field_value = $model->zaezdto;
                            $zaezdfield->save();
                        } else {
                            $zaezdfield->delete();
                        }
                    } else {
                        $zaezdfield= new Properties();
                        $zaezdfield->object_id = $model->id;
                        if ($model->zaezdto) {
                            $zaezdfield->field_id = 49;
                            $zaezdfield->field_value = $model->zaezdto;
                            $zaezdfield->save();
                        }
                    }
                    $viezdfield = Properties::findOne(['field_id' => 50, 'object_id'=>$model->id]);
                    if ($viezdfield) {
                        if ($model->viezd) {
                            $viezdfield->field_value = $model->viezd;
                            $viezdfield->save();
                        } else {
                            $viezdfield->delete();
                        }
                    } else {
                        $viezdfield= new Properties();
                        $viezdfield->object_id = $model->id;
                        if ($model->viezd) {
                            $viezdfield->field_id = 50;
                            $viezdfield->field_value = $model->viezd;
                            $viezdfield->save();
                        }
                    }
                    $websitefield = Properties::findOne(['field_id' => 9, 'object_id'=>$model->id]);
                    if ($websitefield) {
                        if ($model->website) {
                            $websitefield->field_value = $model->website;
                            $websitefield->save();
                        } else {
                            $websitefield->delete();
                        }
                    } else {
                        $websitefield= new Properties();
                        $websitefield->object_id = $model->id;
                        if ($model->website) {
                            $websitefield->field_id = 9;
                            $websitefield->field_value = $model->website;
                            $websitefield->save();
                        }
                    }
                    $price_propertys = Properties::findOne(['field_id' => 31, 'object_id'=>$model->id]);
                    if ($price_propertys) {
                        if ($model->price_property) {
                            $price_propertys->field_value = $model->price_property;
                            $price_propertys->save();
                        } else {
                            $price_propertys->delete();
                        }
                    } else {
                        $price_propertys= new Properties();
                        $price_propertys->object_id = $model->id;
                        $price_propertys->field_id = 31;
                        if ($model->price_property) {
                            $price_propertys->field_value = $model->price_property;
                            $price_propertys->save();
                        }
                    }
                    $fromcenter = Properties::findOne(['field_id' => 2, 'object_id'=>$model->id]);
                    if ($fromcenter) {
                        if ($model->field2) {
                            $fromcenter->field_value = $model->field2;
                            $fromcenter->save();
                        } else {
                            $fromcenter->delete();
                        }
                    } else {
                        $fromcenter= new Properties();
                        $fromcenter->object_id = $model->id;
                        $fromcenter->field_id = 2;
                        if ($model->field2) {
                            $fromcenter->field_value = $model->field2;
                            $fromcenter->save();
                        }
                    }
                    $field11 = Properties::findOne(['field_id' => 11, 'object_id'=>$model->id]);
                    if ($field11) {
                        if ($model->field11) {
                            $field11->field_value = $model->field11;
                            $field11->save();
                        } else {
                            $field11->delete();
                        }
                    } else {
                        $field11= new Properties();
                        $field11->object_id = $model->id;
                        $field11->field_id = 11;
                        if ($model->field11) {
                            $field11->field_value = $model->field11;
                            $field11->save();
                        }
                    }
                    $field6 = Properties::findOne(['field_id' => 6, 'object_id'=>$model->id]);
                    if ($field6) {
                        if ($model->field6) {
                            $field6->field_value = $model->field6;
                            $field6->save();
                        } else {
                            $field6->delete();
                        }
                    } else {
                        $field6= new Properties();
                        $field6->object_id = $model->id;
                        $field6->field_id = 6;
                        if ($model->field6) {
                            $field6->field_value = $model->field6;
                            $field6->save();
                        }
                    }
                    $fromsea = Properties::findOne(['field_id' => 1, 'object_id'=>$model->id]);
                    if ($fromsea) {
                        if ($model->field1) {
                            $fromsea->field_value = $model->field1;
                            $fromsea->save();
                        } else {
                            $fromsea->delete();
                        }
                    } else {
                        $fromsea= new Properties();
                        $fromsea->object_id = $model->id;
                        $fromsea->field_id = 1;
                        if ($model->field1) {
                            $fromsea->field_value = $model->field1;
                            $fromsea->save();
                        }
                    }
                    $hightsea = Properties::findOne(['field_id' => 34, 'object_id'=>$model->id]);
                    if ($hightsea) {
                        if ($model->field34) {
                            $hightsea->field_value = $model->field34;
                            $hightsea->save();
                        } else {
                            $hightsea->delete();
                        }
                    } else {
                        $hightsea= new Properties();
                        $hightsea->object_id = $model->id;
                        $hightsea->field_id = 34;
                        if ($model->field34) {
                            $hightsea->field_value = $model->field34;
                            $hightsea->save();
                        }
                    }
                    $func = Properties::findOne(['field_id' => 32, 'object_id'=>$model->id]);
                    if ($func) {
                        if ($model->field32) {
                            $func->field_value = $model->field32;
                            $func->save();
                        } else {
                            $func->delete();
                        }
                    } else {
                        $func= new Properties();
                        $func->object_id = $model->id;
                        $func->field_id = 32;
                        if ($model->field32) {
                            $func->field_value = $model->field32;
                            $func->save();
                        }
                    }
                    if (Properties::findOne(['field_id'=>36, 'object_id'=>$model->id])) {
                        $phone_booking = Properties::findOne(['field_id'=>36, 'object_id'=>$model->id]);
                        if ($model->phone_booking) {
                            $phone_booking->field_value = $model->phone_booking;
                            $phone_booking->save();
                        } else {
                            $phone_booking->delete();
                        }
                    } else {
                        $phone_booking = new Properties();
                        $phone_booking->field_id = 36;
                        $phone_booking->object_id = $model->id;
                        $phone_booking->field_value = $model->phone_booking;
                        $phone_booking->save();
                    }
                    if (Properties::findOne(['field_id'=>39, 'object_id'=>$model->id])) {
                        $emailsms = Properties::findOne(['field_id'=>39, 'object_id'=>$model->id]);
                        if ($model->emailsms) {
                            $emailsms->field_value = $model->emailsms;
                            $emailsms->save();
                        } else {
                            $emailsms->delete();
                        }
                    } else {
                        $emailsms = new Properties();
                        $emailsms->field_id = 39;
                        $emailsms->object_id = $model->id;
                        $emailsms->field_value = $model->emailsms;
                        $emailsms->save();
                    }
                    if (Properties::findOne(['field_id'=>4, 'object_id'=>$model->id])) {
                        $propertygprsp = Properties::findOne(['field_id'=>4, 'object_id'=>$model->id]);
                        if ($model->propertygprs) {
                            $propertygprsp->field_value = $model->propertygprs;
                            $propertygprsp->save();
                        } else {
                            $propertygprsp->delete();
                        }
                    } else {
                        $propertygprsp = new Properties();
                        $propertygprsp->field_id = 4;
                        $propertygprsp->object_id = $model->id;
                        $propertygprsp->field_value = $model->propertygprs;
                        $propertygprsp->save();
                    }
                    if (Properties::findOne(['field_id'=>38, 'object_id'=>$model->id])) {
                        $mails = Properties::findOne(['field_id'=>38, 'object_id'=>$model->id]);
                        if ($model->email_booking) {
                            $mails->field_value = $model->email_booking;
                            $mails->save();
                        } else {
                            $mails->delete();
                        }
                    } else {
                        $mails = new Properties();
                        $mails->field_id = 38;
                        $mails->object_id = $model->id;
                        $mails->field_value = $model->email_booking;
                        $mails->save();
                    }
                    $properties = Field::find()->where(['!=', 'class', ""])->all();
                    foreach ($properties as $property) {
                        if (Properties::findOne(['field_id'=>$property->id, 'object_id'=>$model->id])) {
                            $property_field = Properties::findOne(['field_id'=>$property->id, 'object_id'=>$model->id]);
                            $x= 'field'.$property->id;
                            if ($model->$x) {
                                $property_field->field_value = $model->$x;
                                $property_field->save();
                            } else {
                                $property_field->delete();
                            }
                        } else {
                            $x= 'field'.$property->id;
                            $property_field = new Properties();
                            $property_field->field_id = $property->id;
                            $property_field->object_id = $model->id;
                            $property_field->field_value = $model->$x;
                            $property_field->save();
                        }
                    }
                    $model->save();
                    Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
                    return $this->redirect(['/update/'.$model->alias]);
                } else {
                    return $this->renderPartial('index', [
                        'model' => $model,
                    ]);
                }
            } else {
                throw new NotFoundHttpException('У вас нету прав на редактирование этого объекта.');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionCatroom($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            if (!Yii::$app->user->isGuest && (Yii::$app->user->getId() == $model->user_id || User::findOne(['id'=>Yii::$app->user->getId()])->type == 1)) {
                return $this->renderPartial('catroom', [
                    'model' => $model,
                ]);
            } else {
                throw new NotFoundHttpException('У вас нету прав на редактирование этого объекта.');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionCatroomcreate($id)
    {
        $object = Object::findOne(['id'=>$id]);
        if ($object) {
            if (!Yii::$app->user->isGuest && (Yii::$app->user->getId() == $object->user_id || User::findOne(['id'=>Yii::$app->user->getId()])->type == 1)) {
                $model = new Catroom();
                $model->file = UploadedFile::getInstances($model, 'file');
                $model->object_id = $id;
                if ($maxcat = Catroom::find()->orderBy('id DESC')->limit(1)->all()) {
                    $maxid = $maxcat[0]->id+1;
                } else {
                    $maxcat = 1;
                }
                if(!empty($model->file)) {
                    $mphoto = ''; $files = $model->file;
                    for ($i=0; $i < count($files) ; $i++) {
                        $filename = $id.'-'.$maxid.'-'.rand(10000, 50000);
                        if ($i!=count($files)-1) {
                            $mphoto .= $filename. '.' . $files[$i]->extension.',';
                        } else {
                            $mphoto .= $filename. '.' . $files[$i]->extension;
                        }
                        $files[$i]->saveAs('upload/catroom/'.$filename . '.' . $files[$i]->extension);
                        $model->photo = "upload/catroom/".$filename . '.' . $files[$i]->extension;
                            $img_width = 800 ;
                            $img_height = 600 ;
                            $tmp_large=imagecreatetruecolor($img_width, $img_height);
                             $transparency = imagecolorallocatealpha($tmp_large, 255, 255, 255, 127);
                             imagefill($tmp_large, 0, 0, $transparency);
                            list($width,$height) = getimagesize($model->photo);
                            $percent = $width/$height;
                            if ($width>=$height) {
                                 $new_width = 800;
                                 $new_height = 800/$percent;
                            } else {
                                 $new_width = 600*$percent;
                                 $new_height = 600;
                            }
                            if (explode(".", $model->photo)[1] == "png") {
                                $im = imagecreatefrompng($model->photo);
                                imagecopyresampled($tmp_large,$im,(800-$new_width)/2, (600-$new_height)/2, 0, 0,$new_width, $new_height, $width, $height);
                                if (imagepng($tmp_large, $model->photo)) {
                                }
                            } elseif (explode(".", $model->photo)[1] == "jpg" || explode(".", $model->photo)[1] == "jpeg") {
                                $im = imagecreatefromjpeg($model->photo);
                                imagecopyresampled($tmp_large,$im,(800-$new_width)/2, (600-$new_height)/2, 0, 0,$new_width, $new_height, $width, $height);
                                if (imagejpeg($tmp_large, $model->photo)) {
                                }
                            }
                        $newimage = $model->photo;
                        if ($wimg = Config::findOne(['id'=>1])->watermark) {
                            $watermark = $wimg;
                            if (is_file($newimage)) {
                                if (explode(".", $newimage)[1] == "png") {
                                    $im = imagecreatefrompng($newimage);
                                } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                                    $im = imagecreatefromjpeg($newimage);
                                }
                                if (explode(".", $newimage)[1] == "png") {
                                    $stamp = imagecreatefrompng($newimage);
                                } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                                    $stamp = imagecreatefromjpeg($newimage);
                                }
                                $stamp = imagecreatefrompng($watermark);
                                $marge_right = 0;
                                $marge_bottom = 0;
                                $sx = imagesx($stamp);
                                $sy = imagesy($stamp);
                                imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
                                if (explode(".", $newimage)[1] == "png") {
                                    if (imagepng($im,$newimage)) {
                                        imagedestroy($im);
                                    }
                                } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                                    if (imagejpeg($im,$newimage)) {
                                        imagedestroy($im);
                                    }
                                }
                            }
                        }
                    }
                   $model->photo = $mphoto;
                }
                $model->user_id = $object->user_id;
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    if ($model->child_count == 1) {
                        $childage = new Childage();
                        $childage->catroom_id = $model->id;
                        $childage->child_count = $model->child_count;
                        $childage->child_age = $model->child_age1+1;
                        $childage->save();
                    } elseif ($model->child_count == 2) {
                        $childage1 = new Childage();
                        $childage1->catroom_id = $model->id;
                        $childage1->child_count = $model->child_count;
                        $childage1->child_age = $model->child_age1+1;
                        $childage1->save();
                        $childage2 = new Childage();
                        $childage2->catroom_id = $model->id;
                        $childage2->child_count = $model->child_count;
                        $childage2->child_age = $model->child_age2+1;
                        $childage2->save();
                    } elseif ($model->child_count == 3) {
                        $childage1 = new Childage();
                        $childage1->catroom_id = $model->id;
                        $childage1->child_count = $model->child_count;
                        $childage1->child_age = $model->child_age1+1;
                        $childage1->save();
                        $childage2 = new Childage();
                        $childage2->catroom_id = $model->id;
                        $childage2->child_count = $model->child_count;
                        $childage2->child_age = $model->child_age2+1;
                        $childage2->save();
                        $childage3 = new Childage();
                        $childage3->catroom_id = $model->id;
                        $childage3->child_count = $model->child_count;
                        $childage3->child_age = $model->child_age3+1;
                        $childage3->save();
                    } elseif ($model->child_count == 4) {
                        $childage1 = new Childage();
                        $childage1->catroom_id = $model->id;
                        $childage1->child_count = $model->child_count;
                        $childage1->child_age = $model->child_age1+1;
                        $childage1->save();
                        $childage2 = new Childage();
                        $childage2->catroom_id = $model->id;
                        $childage2->child_count = $model->child_count;
                        $childage2->child_age = $model->child_age2+1;
                        $childage2->save();
                        $childage3 = new Childage();
                        $childage3->catroom_id = $model->id;
                        $childage3->child_count = $model->child_count;
                        $childage3->child_age = $model->child_age3+1;
                        $childage3->save();
                        $childage4 = new Childage();
                        $childage4->catroom_id = $model->id;
                        $childage4->child_count = $model->child_count;
                        $childage4->child_age = $model->child_age4+1;
                        $childage4->save();
                    }
                    return $this->redirect(['update/catroom/'.$id]);
                } else {
                    $object = $this->findModel($id);
                    return $this->renderPartial('catroomcreate', [
                        'model' => $model,
                        'object' => $object,
                    ]);
                }
            } else {
                throw new NotFoundHttpException('У вас нету прав на редактирование этого объекта.');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionCatroomupdate($id)
    {
        $model = Catroom::findOne(['id'=>$id]);
        $object = Object::findOne(['id'=>$model->object_id]);
        if ($model) {
            if (!Yii::$app->user->isGuest && ($model->user_id == Yii::$app->user->getId() || User::findOne(['id'=>Yii::$app->user->getId()])->type == 1)) {
                $model->file = UploadedFile::getInstances($model, 'file');
                $modelfiles = [];
                if(!empty($model->file)) {
                   foreach ($model->file as $file) {
                       $filename = $model->object_id.'-'.$model->id.'-'.rand(10000, 50000);
                       $file->saveAs('upload/catroom/'.$filename . '.' . $file->extension);
                       $newimage = 'upload/catroom/'.$filename . '.' . $file->extension;
                       $img_width = 800 ;
                       $img_height = 600 ;
                       $tmp_large=imagecreatetruecolor($img_width, $img_height);
                        $transparency = imagecolorallocatealpha($tmp_large, 255, 255, 255, 127);
                        imagefill($tmp_large, 0, 0, $transparency);
                       list($width,$height) = getimagesize($newimage);
                       $percent = $width/$height;
                       if ($width>=$height) {
                            $new_width = 800;
                            $new_height = 800/$percent;
                       } else {
                            $new_width = 600*$percent;
                            $new_height = 600;
                       }
                       if (explode(".", $newimage)[1] == "png") {
                           $im = imagecreatefrompng($newimage);
                           imagecopyresampled($tmp_large,$im,(800-$new_width)/2, (600-$new_height)/2, 0, 0,$new_width, $new_height, $width, $height);
                           if (imagepng($tmp_large, $newimage)) {
                           }
                       } elseif (explode(".", $newimage)[1] == "jpg" || explode(".",$newimage)[1] == "jpeg") {
                           $im = imagecreatefromjpeg($newimage);
                           imagecopyresampled($tmp_large,$im,(800-$new_width)/2, (600-$new_height)/2, 0, 0,$new_width, $new_height, $width, $height);
                           if (imagejpeg($tmp_large, $newimage)) {
                           }
                       }
                       if ($wimg = Config::findOne(['id'=>1])->watermark) {
                           $watermark = $wimg;
                           if (is_file($newimage)) {
                               if (explode(".", $newimage)[1] == "png") {
                                   $im = imagecreatefrompng($newimage);
                               } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                                   $im = imagecreatefromjpeg($newimage);
                               }
                               if (explode(".", $newimage)[1] == "png") {
                                   $stamp = imagecreatefrompng($newimage);
                               } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                                   $stamp = imagecreatefromjpeg($newimage);
                               }
                               $stamp = imagecreatefrompng($watermark);
                               $marge_right = 0;
                               $marge_bottom = 0;
                               $sx = imagesx($stamp);
                               $sy = imagesy($stamp);
                               imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
                               if (explode(".", $newimage)[1] == "png") {
                                   if (imagepng($im,$newimage)) {
                                       imagedestroy($im);
                                   }
                               } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                                   if (imagejpeg($im,$newimage)) {
                                       imagedestroy($im);
                                   }
                               }
                           }
                       }
                       array_push($modelfiles, $filename . '.' . $file->extension);
                   }
                   $photo = explode(",", $model->photo);
                   if ($model->photo && count($photo)!=0) {
                       $result = array_merge($photo, $modelfiles);
                   } else {
                       $result = $modelfiles;
                   }
                   $mphoto = '';
                   for ($i=0; $i < count($result) ; $i++) {
                       if ($i!=count($result)-1) {
                           $mphoto .= $result[$i].',';
                       } else {
                           $mphoto .= $result[$i];
                       }
                   }
                   $model->photo = $mphoto;
                }
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    if ($model->child_count == 1) {
                        $childs = Childage::find()->where(['catroom_id'=>$id])->all();
                        foreach ($childs as $child) {
                            $child->delete();
                        }
                        $childage = new Childage();
                        $childage->catroom_id = $model->id;
                        $childage->child_count = $model->child_count;
                        $childage->child_age = $model->child_age1+1;
                        $childage->save();
                    } elseif ($model->child_count == 2) {
                        $childs = Childage::find()->where(['catroom_id'=>$id])->all();
                        foreach ($childs as $child) {
                            $child->delete();
                        }
                        $childage1 = new Childage();
                        $childage1->catroom_id = $model->id;
                        $childage1->child_count = $model->child_count;
                        $childage1->child_age = $model->child_age1+1;
                        $childage1->save();
                        $childage2 = new Childage();
                        $childage2->catroom_id = $model->id;
                        $childage2->child_count = $model->child_count;
                        $childage2->child_age = $model->child_age2+1;
                        $childage2->save();
                    } elseif ($model->child_count == 3) {
                        $childs = Childage::find()->where(['catroom_id'=>$id])->all();
                        foreach ($childs as $child) {
                            $child->delete();
                        }
                        $childage1 = new Childage();
                        $childage1->catroom_id = $model->id;
                        $childage1->child_count = $model->child_count;
                        $childage1->child_age = $model->child_age1+1;
                        $childage1->save();
                        $childage2 = new Childage();
                        $childage2->catroom_id = $model->id;
                        $childage2->child_count = $model->child_count;
                        $childage2->child_age = $model->child_age2+1;
                        $childage2->save();
                        $childage3 = new Childage();
                        $childage3->catroom_id = $model->id;
                        $childage3->child_count = $model->child_count;
                        $childage3->child_age = $model->child_age3+1;
                        $childage3->save();
                    } elseif ($model->child_count == 4) {
                        $childs = Childage::find()->where(['catroom_id'=>$id])->all();
                        foreach ($childs as $child) {
                            $child->delete();
                        }
                        $childage1 = new Childage();
                        $childage1->catroom_id = $model->id;
                        $childage1->child_count = $model->child_count;
                        $childage1->child_age = $model->child_age1+1;
                        $childage1->save();
                        $childage2 = new Childage();
                        $childage2->catroom_id = $model->id;
                        $childage2->child_count = $model->child_count;
                        $childage2->child_age = $model->child_age2+1;
                        $childage2->save();
                        $childage3 = new Childage();
                        $childage3->catroom_id = $model->id;
                        $childage3->child_count = $model->child_count;
                        $childage3->child_age = $model->child_age3+1;
                        $childage3->save();
                        $childage4 = new Childage();
                        $childage4->catroom_id = $model->id;
                        $childage4->child_count = $model->child_count;
                        $childage4->child_age = $model->child_age4+1;
                        $childage4->save();
                    }
                    return $this->redirect(['update/catroom/'.Catroom::findOne(['id'=>$id])->object_id]);
                } else {
                    return $this->renderPartial('catroomupdate', [
                        'model' => $model,
                        'object' => $object,
                    ]);
                }
            } else {
                throw new NotFoundHttpException('У вас нету прав на редактирование этого объекта.');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionChangestatus($id)
    {
        $catroom = Catroom::findOne(['id'=>$id]);
        if ($catroom->status == 1) {
            $catroom->status =0;
            Yii::$app->session->setFlash('success', 'Категория отключена.');
        } else {
            $catroom->status =1;
            Yii::$app->session->setFlash('success', 'Категория включена.');
        }
        $catroom->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionCreateprice($id)
    {
        $object = Object::findOne(['id'=>$id]);
        if ($object) {
            if (!Yii::$app->user->isGuest && (Yii::$app->user->getId() == $object->user_id || User::findOne(['id'=>Yii::$app->user->getId()])->type == 1)) {
                $model = new Price();
                $model->object_id = $id;
                $model->user_id = $object->user_id;
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['update/availability/'.$id]);
                } else {
                    $object = $this->findModel($id);
                    return $this->renderPartial('createprice', [
                        'model' => $model,
                        'object' => $object,
                    ]);
                }
            } else {
                throw new NotFoundHttpException('У вас нету прав на редактирование этого объекта.');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionUpdateprice($id)
    {
        $model = Price::findOne(['id'=>$id]);
        $object = Object::findOne(['id'=>$model->object_id]);
        if ($model) {
            if (!Yii::$app->user->isGuest && ($model->user_id == Yii::$app->user->getId() || User::findOne(['id'=>Yii::$app->user->getId()])->type == 1)) {
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['update/availability/'.Price::findOne(['id'=>$id])->object_id]);
                } else {
                    return $this->renderPartial('updateprice', [
                        'model' => $model,
                        'object' => $object,
                    ]);
                }
            } else {
                throw new NotFoundHttpException('У вас нету прав на редактирование этого объекта.');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionAvailability($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            if (!Yii::$app->user->isGuest && ($model->user_id == Yii::$app->user->getId() || User::findOne(['id'=>Yii::$app->user->getId()])->type == 1)) {
                $calendar = new Calendar();
                if ($calendar->load(Yii::$app->request->post()) && $calendar->save()) {
                    return $this->renderPartial('availability', [
                        'calendar' => $calendar,
                        'model' => $model,
                    ]);
                } else {
                    return $this->renderPartial('availability', [
                        'calendar' => $calendar,
                        'model' => $model,
                    ]);
                }
            } else {
                throw new NotFoundHttpException('У вас нету прав на редактирование этого объекта.');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionFinance($id)
    {
        $model= Finance::findOne(['object_id'=>$id]);
        $object = Object::findOne(['id'=>$id]);
        if ($object) {
            if (!Yii::$app->user->isGuest && ($object->user_id == Yii::$app->user->getId() || User::findOne(['id'=>Yii::$app->user->getId()])->type == 1)) {
                return $this->renderPartial('finance', [
                    'model' => $model,
                    'objectid'=>$id,
                ]);
            } else {
                throw new NotFoundHttpException('У вас нету прав на редактирование этого объекта.');
            }
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }

    public function actionRegion($id)
    {
        $countregions = Region::find()
                ->where(['country_id' => $id])
                ->count();
        $regions = Region::find()
                ->where(['country_id' => $id])
                ->orderBy('title')
                ->all();
        if($countregions>0){
            foreach($regions as $region){
                echo "<option value='".$region->id."'>".$region->title."</option>";
            }
        } else {
                echo "<option>-</option>";
        }
    }

    public function actionLocalityrayon($id)
    {
        $countlocality = Locality::find()
                ->where(['parent_id' => $id])
                ->count();

        $localitys = Locality::find()
                ->where(['parent_id' => $id])
                ->orderBy('title')
                ->all();

        if($countlocality>0){
            $loc = "<option value>Выберите нас. пункт</option>";
            foreach($localitys as $locality){
                $loc.="<option value='".$locality->id."'>".$locality->title."</option>";
            }
            echo $loc;
        } else {
                echo "<option value>-</option>";
        }
    }

    public function actionRayon($id)
    {
        $countlocality = Locality::find()
                ->where(['region_id' => $id])
                ->andWhere(['parent_id'=>0])
                ->count();

        $localitys = Locality::find()
                ->where(['region_id' => $id])
                ->andWhere(['parent_id'=>0])
                ->orderBy('title')
                ->all();

        if($countlocality>0){
            $loc = "<option value>Выберите район</option>";
            foreach($localitys as $locality){
                $loc.="<option value='".$locality->id."'>".$locality->title."</option>";
            }
            echo $loc;
        } else {
                echo "<option value>-</option>";
        }
    }

    public function actionLocality($id)
    {
        $countlocality = Locality::find()
                ->where(['region_id' => $id])
                ->count();
        $localitys = Locality::find()
                ->where(['region_id' => $id])
                ->orderBy('title')
                ->all();
        if($countlocality>0){
            foreach($localitys as $locality){
                echo "<option value='".$locality->id."'>".$locality->title."</option>";
            }
        } else {
                echo "<option>-</option>";
        }
    }

    public function actionAddresscountry($id)
    {
        $address = Country::findOne(['id'=>$id])->title;
        echo $address;
    }

    public function actionAddressregion($id)
    {
        $address1 = Region::findOne(['id'=>$id]);
        $address = Country::findOne(['id'=>$address1->country_id])->title;
        echo $address.", ".$address1->title;
    }

    public function actionAddressrayon($id)
    {
        $locality = Locality::findOne(['id'=>$id]);
        $address1 = Region::findOne(['id'=>$locality->region_id]);
        $address = Country::findOne(['id'=>$address1->country_id])->title;
        echo $address.", ".$address1->title.", ".$locality->title;
    }

    public function actionAddresslocality($id)
    {
        $locality = Locality::findOne(['id'=>$id]);
        $rayon = Locality::findOne(['id'=>$locality->parent_id]);
        $address1 = Region::findOne(['id'=>$locality->region_id]);
        $address = Country::findOne(['id'=>$address1->country_id])->title;
        if ($rayon) {
            echo $address.", ".$address1->title.", ".$rayon->title.", ".$locality->title;
        } else {
            echo $address.", ".$address1->title.", ".$locality->title;
        }
    }

    public function actionDeletecatroom($id)
    {
        $model = Object::findOne(['id'=>$id]);
        if ($model->load(Yii::$app->request->post())) {
            $catroom = Catroom::findOne(['id'=>$model->deletecatroom]);
            $childages = Childage::find()->where(['catroom_id'=>$catroom->id])->all();
            foreach ($childages as $childage) {
                $childage->delete();
            }
            $catroom->delete();
            Yii::$app->session->setFlash('success', 'Категория номеров удалена.');
            return $this->redirect(['/update/catroom/'.$id]);
        }
    }

    public function actionDeleteimage($id)
    {
        $model = Object::findOne(['id'=>$id]);
        if ($model->load(Yii::$app->request->post())) {
            $image = Image::findOne(['id'=>$model->img]);
            $image->delete();
            $folder = "upload/images/".$id;
            $files = glob($folder.'/*');
            foreach($files as $file){
                if(is_file($file) && $file==$image->image){
                unlink($file);
                }
            }
            return $this->redirect(['/update/'.$model->alias]);
        }
    }

    public function actionRemoveimg($cat, $src)
    {
        $model = Catroom::findOne(['id'=>$cat]);
        $images = explode(",", $model->photo);
        $imgs = array_diff($images, [$src]);
        $photo = "";
        if (count($imgs)!=0) {
            if (count($imgs)==1) {
                foreach ($imgs as $img) {
                $photo = $img;
                }
            } else {
                $photo = implode(",", $imgs);
            }
        }
        if ($photo == "") {
             $model->photo = null;
        } else {
             $model->photo = $photo;
        }
        $file ="upload/catroom/".$src;
        unlink($file);
        $model->save();
        return $this->redirect(['/update/catroomupdate/'.$cat]);
    }

    public function actionHeaderimage($id)
    {
        $model = Object::findOne(['id'=>$id]);
        if ($model->load(Yii::$app->request->post())) {
            $images = Image::find()->where(['object_id'=>$id])->all();
            foreach ($images as $img) {
                $img->main = 0;
                $img->save();
            }
            $image = Image::findOne(['id'=>$model->img]);
            $image->main = 1;
            $image->save();
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены.');
            return $this->redirect(['/update/'.$model->alias]);
        }
    }

    public function actionAddimage($id)
    {
        $model = Object::findOne(['id'=>$id]);
        $image = new Image();
        if ($image->load(Yii::$app->request->post())) {
            $image->file = UploadedFile::getInstance($image, 'file');
           if(!empty($image->file)) {
               if (!file_exists('upload/images/'.$id)) {
                   mkdir('upload/images/'.$id, 0777, true);
               }
               $images = Image::find()->where(['object_id'=>$id])->all();
               $newimage = new Image();
               $newimage->object_id = $id;
               $newimage->main = $image->main;
               if ($image->main == 1) {
                   foreach ($images as $img) {
                      $img->main = 0;
                      $img->save();
                   }
               } else {
                   if (count($images) == 0) {
                       $newimage->main = 1;
                   }
               }
               $newimage->value = $image->value;
               $filename = $id.'-'.rand(10000, 50000);
               $image->file->saveAs('upload/images/'.$id."/".$filename . '.' . $image->file->extension);
               $newimage->image = "upload/images/".$id."/".$filename . '.' . $image->file->extension;
               $img_width = 800 ;
               $img_height = 600 ;
               $tmp_large=imagecreatetruecolor($img_width, $img_height);
                $transparency = imagecolorallocatealpha($tmp_large, 255, 255, 255, 127);
                imagefill($tmp_large, 0, 0, $transparency);
               list($width,$height) = getimagesize($newimage->image);
               $percent = $width/$height;
               if ($width>=$height) {
                    $new_width = 800;
                    $new_height = 800/$percent;
               } else {
                    $new_width = 600*$percent;
                    $new_height = 600;
               }
               if (explode(".", $newimage->image)[1] == "png") {
                   $im = imagecreatefrompng($newimage->image);
                   imagecopyresampled($tmp_large,$im,(800-$new_width)/2, (600-$new_height)/2, 0, 0,$new_width, $new_height, $width, $height);
                   if (imagepng($tmp_large, $newimage->image)) {
                   }
               } elseif (explode(".", $newimage->image)[1] == "jpg" || explode(".",$newimage->image)[1] == "jpeg") {
                   $im = imagecreatefromjpeg($newimage->image);
                   imagecopyresampled($tmp_large,$im,(800-$new_width)/2, (600-$new_height)/2, 0, 0,$new_width, $new_height, $width, $height);
                   if (imagejpeg($tmp_large, $newimage->image)) {
                   }
               }
               $newimage->save();
               $imgs = $newimage->image;
               if (is_file($imgs)) {
                   $newimage = $newimage->image;
                   if ($wimg = Config::findOne(['id'=>1])->watermark) {
                       $watermark = $wimg;
                       if (is_file($newimage)) {
                           if (explode(".", $newimage)[1] == "png") {
                               $im = imagecreatefrompng($newimage);
                           } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                               $im = imagecreatefromjpeg($newimage);
                           }
                           if (explode(".", $newimage)[1] == "png") {
                               $stamp = imagecreatefrompng($newimage);
                           } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                               $stamp = imagecreatefromjpeg($newimage);
                           }
                           $stamp = imagecreatefrompng($watermark);
                           $marge_right = 0;
                           $marge_bottom = 0;
                           $sx = imagesx($stamp);
                           $sy = imagesy($stamp);
                           imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
                           if (explode(".", $newimage)[1] == "png") {
                               if (imagepng($im,$newimage)) {
                                   imagedestroy($im);
                               }
                           } elseif (explode(".", $newimage)[1] == "jpg" || explode(".", $newimage)[1] == "jpeg") {
                               if (imagejpeg($im,$newimage)) {
                                   imagedestroy($im);
                               }
                           }
                       }
                   }
               }
           }
            return $this->redirect(['/update/'.$model->alias]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Object::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Страница не найдена.');
        }
    }
}
