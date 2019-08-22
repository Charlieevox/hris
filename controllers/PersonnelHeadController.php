<?php

namespace app\controllers;

use Yii;
use app\models\MsPersonnelHead;
use app\models\MsPersonnelPosition;
use app\models\MsPersonnelDepartment;
use app\components\AccessRule;
use app\models\Location;
use app\components\ControllerUAC;
use kartik\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
use yii\helpers\Json;
use yii\helpers\Url;
use kartik\mpdf\Pdf;
use app\models\MsPersonnelFamily;
use app\models\MsPersonnelContract;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

/**
 * PersonnelHeadController implements the CRUD actions for MsPersonnelHead model.
 */
class PersonnelHeadController extends ControllerUAC {

    public function init() {
        if (Yii::$app->user->isGuest) {
            $this->goHome();
        }
    }

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex() {
        $model = new MsPersonnelHead(['scenario' => 'search']);
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        $model->bankDetail = $model->bankName . " - " . $model->bankdesc->bankDesc;

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $model = new MsPersonnelHead();
        $model->joinPersonnelfamily = [];
        $model->joinPersonnelContract = [];
		$model->joinPersonnelStatus = [];

        $connection = Yii::$app->db;
        $sql = "select '' as 'id','Select Position' as 'PositionDescription' Union All select id,PositionDescription from ms_personnelposition where flagActive= '1'";
        $temp = $connection->createCommand($sql);
        $positionResult = $temp->queryAll();
		
		$k=0;
		foreach ($positionResult as $joinPersonnelPosition) {
            $model->joinPersonnelPosition[$k]["id"] = $joinPersonnelPosition['id'];
            $model->joinPersonnelPosition[$k]["text"] = $joinPersonnelPosition['PositionDescription'];
            $k += 1;
        }
		
		$connection = Yii::$app->db;
        $sql = "select '' as 'id','Select Position' as 'statusDescription' Union All SELECT value1,key2 FROM ms_setting WHERE Key1 = 'Status'";
        $temp = $connection->createCommand($sql);
        $statusResult = $temp->queryAll();
		
		$l=0;
		foreach ($statusResult as $joinPersonnelStatus) {
            $model->joinPersonnelStatus[$l]["id"] = $joinPersonnelStatus['id'];
            $model->joinPersonnelStatus[$l]["text"] = $joinPersonnelStatus['statusDescription'];
            $l += 1;
        }
		
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $imageKTP = UploadedFile::getInstance($model, 'imageGalleryKTP');
            $imageNPWP = UploadedFile::getInstance($model, 'imageGalleryNPWP');
            $imagePhoto = UploadedFile::getInstance($model, 'imageGalleryPhoto');

            if ($imageKTP == null) {
                $extKTP = '';
            } else {
                $extKTP = end(explode(".", $imageKTP->name));
            }



            if ($imageNPWP == null) {
                $extNPWP = '';
            } else {
                $extNPWP = end(explode(".", $imageNPWP->name));
            }

            if ($imagePhoto == null) {
                $extPhoto = '';
            } else {
                $extPhoto = end(explode(".", $imagePhoto->name));
            }

            $fileNameKTP = Yii::$app->security->generateRandomString() . ".{$extKTP}";
            $fileNameNPWP = Yii::$app->security->generateRandomString() . ".{$extNPWP}";
            $fileNamePhoto = Yii::$app->security->generateRandomString() . ".{$extPhoto}";

            $pathKTP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $fileNameKTP;
            $pathNPWP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $fileNameNPWP;
            $pathPhoto = Yii::$app->basePath . '/assets_b/uploads/profile/' . $fileNamePhoto;

            $oldPathKTP = null;
            $oldPathNPWP = null;
            $oldPathPhoto = null;

            if (!empty($model->imageKTP)) {
                $oldPathKTP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imageKTP;
            }

            if (!empty($model->imageNPWP)) {
                $oldPathNPWP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imageNPWP;
            }

            if (!empty($model->imagePhoto)) {
                $oldPathPhoto = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imagePhoto;
            }

            $imageKTP != null ? $model->imageKTP = $fileNameKTP : null;
            $imageNPWP != null ? $model->imageNPWP = $fileNameNPWP : null;
            $imagePhoto != null ? $model->imagePhoto = $fileNamePhoto : null;

            if ($this->saveModel($model, true)) {
                if ($imageKTP != null) {
                    if (!file_exists(Yii::$app->basePath . '/assets_b/uploads/profile/')) {
                        FileHelper::createDirectory(yii::$app->basePath . '/assets_b/uploads/profile');
                    }
                    $imageKTP->saveAs($pathKTP);
                    if ($oldPathKTP && file_exists($oldPathKTP)) {
                        unlink($oldPathKTP);
                    }
                }

                if ($imageNPWP != null) {
                    if (!file_exists(Yii::$app->basePath . '/assets_b/uploads/profile/')) {
                        FileHelper::createDirectory(yii::$app->basePath . '/assets_b/uploads/profile');
                    }
                    $imageNPWP->saveAs($pathNPWP);
                    if ($oldPathNPWP && file_exists($oldPathNPWP)) {
                        unlink($oldPathNPWP);
                    }
                }

                if ($imagePhoto != null) {
                    if (!file_exists(Yii::$app->basePath . '/assets_b/uploads/profile/')) {
                        FileHelper::createDirectory(yii::$app->basePath . '/assets_b/uploads/profile');
                    }
                    $imagePhoto->saveAs($pathPhoto);
                    if ($oldPathNPWP && file_exists($oldPathPhoto)) {
                        unlink($oldPathPhoto);
                    }
                }

                AppHelper::insertTransactionLog('Create Profile', $model->id);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing MsPersonnelHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);
        $model->bankDetail = $model->bankName . " - " . $model->bankdesc->bankDesc;


        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
				
            $imageKTP = UploadedFile::getInstance($model, 'imageGalleryKTP');
            $imageNPWP = UploadedFile::getInstance($model, 'imageGalleryNPWP');
            $imagePhoto = UploadedFile::getInstance($model, 'imageGalleryPhoto');
			
			// DELETE
			
			$imgFullPathKTP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imageKTP;
			$imgFullPathNPWP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imageNPWP;
			$imgFullPathPhoto = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imagePhoto;

						
			if ($imageKTP == null && $model->imageKTP != null && $model->imageGalleryKTPMode) {
				if (file_exists($imgFullPathKTP)) {
					unlink($imgFullPathKTP);
				}
				$model->imageKTP = null;
			}

			if ($imageNPWP == null && $model->imageNPWP != null && $model->imageGalleryNPWPMode) {
				if (file_exists($imgFullPathNPWP)) {
					unlink($imgFullPathNPWP);
				}
				$model->imageNPWP = null;
			}

			if ($imagePhoto == null && $model->imagePhoto != null && $model->imageGalleryPhotoMode) {
				if (file_exists($imgFullPathPhoto)) {
					unlink($imgFullPathPhoto);
				}
				$model->imagePhoto = null;
			}
			
			
			// SAVE MODE

            if ($imageKTP == null) {
                $extKTP = '';
            } else {
                $extKTP = end(explode(".", $imageKTP->name));
            }

            if ($imageNPWP == null) {
                $extNPWP = '';
            } else {
                $extNPWP = end(explode(".", $imageNPWP->name));
            }

            if ($imagePhoto == null) {
                $extPhoto = '';
            } else {
                $extPhoto = end(explode(".", $imagePhoto->name));
            }

            $fileNameKTP = Yii::$app->security->generateRandomString() . ".{$extKTP}";
            $fileNameNPWP = Yii::$app->security->generateRandomString() . ".{$extNPWP}";
            $fileNamePhoto = Yii::$app->security->generateRandomString() . ".{$extPhoto}";

            $pathKTP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $fileNameKTP;
            $pathNPWP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $fileNameNPWP;
            $pathPhoto = Yii::$app->basePath . '/assets_b/uploads/profile/' . $fileNamePhoto;

            $oldPathKTP = null;
            $oldPathNPWP = null;
            $oldPathPhoto = null;

            if (!empty($model->imageKTP)) {
                $oldPathKTP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imageKTP;
            }

            if (!empty($model->imageNPWP)) {
                $oldPathNPWP = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imageNPWP;
            }

            if (!empty($model->imagePhoto)) {
                $oldPathPhoto = Yii::$app->basePath . '/assets_b/uploads/profile/' . $model->imagePhoto;
            }

            $imageKTP != null ? $model->imageKTP = $fileNameKTP : null;
            $imageNPWP != null ? $model->imageNPWP = $fileNameNPWP : null;
            $imagePhoto != null ? $model->imagePhoto = $fileNamePhoto : null;

            if ($this->saveModel($model, false)) {
                if ($imageKTP != null) {
                    if (!file_exists(Yii::$app->basePath . '/assets_b/uploads/profile/')) {
                        FileHelper::createDirectory(yii::$app->basePath . '/assets_b/uploads/profile');
                    }
                    $imageKTP->saveAs($pathKTP);
                    if ($oldPathKTP && file_exists($oldPathKTP)) {
                        unlink($oldPathKTP);
                    }
                }

                if ($imageNPWP != null) {
                    if (!file_exists(Yii::$app->basePath . '/assets_b/uploads/profile/')) {
                        FileHelper::createDirectory(yii::$app->basePath . '/assets_b/uploads/profile');
                    }
                    $imageNPWP->saveAs($pathNPWP);
                    if ($oldPathNPWP && file_exists($oldPathNPWP)) {
                        unlink($oldPathNPWP);
                    }
                }

                if ($imagePhoto != null) {
                    if (!file_exists(Yii::$app->basePath . '/assets_b/uploads/profile/')) {
                        FileHelper::createDirectory(yii::$app->basePath . '/assets_b/uploads/profile');
                    }
                    $imagePhoto->saveAs($pathPhoto);
                    if ($oldPathNPWP && file_exists($oldPathPhoto)) {
                        unlink($oldPathPhoto);
                    }
                }

                AppHelper::insertTransactionLog('Update Profile', $model->id);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionLists($id) {
		if ($id != '') {
            $count = \app\models\MsPersonnelDepartment::findActive()
                    ->where(['divisionId' => $id])
                    ->count();

            $posts = \app\models\MsPersonnelDepartment::findActive()
                    ->where(['divisionId' => $id])
                    ->orderBy('divisionId ASC')
                    ->all();

            if ($count > 0) {
                echo "<option selected='selected' value=''>Select Department</option>";
                foreach ($posts as $post) {
                    echo "<option value='" . $post->departmentCode . "'>" . $post->departmentDesc . "</option>";
                }
            } else {
                echo "<option value=''>Select Department</option>";
            }
        } else {
            echo "<option selected='selected' value=''>Select Department</option>";
        }
    }

    public function actionBankdescription($id) {
        if ($id != '') {
//            var_dump($id);
            $count = \app\models\MsBank::findActive()
                    ->where(['bankId' => $id])
                    ->count();

            $posts = \app\models\MsBank::findActive()
                    ->where(['bankId' => $id])
                    ->orderBy('bankId ASC')
                    ->all();

            if ($count > 0) {
                foreach ($posts as $post) {
                    echo "$post->bankId  -  $post->bankDesc";
                }
            }
        } else {
            echo "";
        }
    }
	
	public function actionGetPosition()
    {
        $position = [];
		$results = MsPersonnelPosition::findActive()
		->all();
		
        Yii::$app->response->format = Response::FORMAT_JSON;
		for ($i = 0; $i < count($results); $i++) {
            $position[$i]['id'] = $results[$i]->id;
            $position[$i]['text'] = $results[$i]->positionDescription;;
        }
		
        return ($position);
    }
	
	

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        AppHelper::insertTransactionLog('Delete Master Personnel', $model->id);
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionRestore($id) {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Shift', $model->id);
        return $this->redirect(['index']);
    }


    protected function findModel($id) {
        if (($model = MsPersonnelHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBrowse() {
        $this->view->params['browse'] = true;
        $model = new MsPersonnelHead();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
                    'model' => $model
        ]);
    }

    public function actionPrintpage($id) {
        $model = $this->findModel($id);
        $url = Url::to(['personnel-head/print', 'id' => $id]);
        $redirectTo = Url::to(['personnel-head/index']);
        return "<script>
                    var newWindow = window.open('$url','fullscreen');
                    if (window.focus) {
                        newWindow.focus();
                    }
                    window.location.href = '$redirectTo';
                </script>";
    }

    public function actionPrint($id) {
        $model = $this->findModel($id);

        $this->layout = false;
        $content = $this->render('_reportView', [
            'model' => $model,
        ]);

        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        return $pdf->render();
    }

    public function actionRemoveImage($id, $fileName, $docName) {
//        $model = $this->findModel($id);
//        $imgFullPath = Yii::$app->basePath . '/assets_b/uploads/profile/' . $fileName;

//        if (file_exists($imgFullPath)) {
//            unlink($imgFullPath);
//        }

//        if ($docName == "KTP") {
//            $model->imageKTP = null;
//        }

//        if ($docName == "NPWP") {
//            $model->imageNPWP = null;
//        }

//      if ($docName == "PHOTO") {
//            $model->imagePhoto = null;
//        }



//        $model->save();
        return Json::encode("Image successfully deleted");
    }

    public function actionGetImage($fileName) {

        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/jpeg');
        $response->format = Response::FORMAT_RAW;
        $imgFullPath = Yii::$app->basePath . '/assets_b/uploads/profile/' . $fileName;



        if (file_exists($imgFullPath)) {
            if (!is_resource($response->stream = fopen($imgFullPath, 'r'))) {
                throw new ServerErrorHttpException('file access failed: permission deny');
            }
        } else {
            throw new NotFoundHttpException();
        }

        $response->send();
    }

    protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $model->birthDate = AppHelper::convertDateTimeFormat($model->birthDate, 'd-m-Y', 'Y-m-d');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        $model->flagActive = 1;
        // $model->fullName = $model->firstName . ' ' . $model->lastName;

        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }

        MsPersonnelFamily::deleteAll('id = :id', [":id" => $model->id]);
        MsPersonnelContract::deleteAll('nik = :id', [":id" => $model->id]);

//        if (empty($model->joinPersonnelfamily) || !is_array($model->joinPersonnelfamily) || count($model->joinPersonnelfamily) < 1) {
//            $transaction->rollBack();
//            return false;
//        }
//        echo "<pre>";
//        var_dump($model->joinPersonnelContract);
//        echo "</pre>";
//        Yii::$app->end();

        function removeElementWithValue($array, $key, $value) {
            foreach ($array as $subKey => $subArray) {
                if ($subArray[$key] == $value) {
                    unset($array[$subKey]);
                }
            }
            return $array;
        }

        $arrayFamily = removeElementWithValue(($model->joinPersonnelfamily), "firstName", "");
        $arrayContract = removeElementWithValue(($model->joinPersonnelContract), "startContract", "");

		

        foreach ($arrayFamily as $joinPersonnelfamily) {
            $PersonnelfamilyModel = new MsPersonnelFamily();
            $PersonnelfamilyModel->id = $model->id;
            $PersonnelfamilyModel->firstName = $joinPersonnelfamily['firstName'];
            $PersonnelfamilyModel->lastName = $joinPersonnelfamily['lastName'];
            $PersonnelfamilyModel->relationship = $joinPersonnelfamily['relationship'];
            $PersonnelfamilyModel->idNumber = $joinPersonnelfamily['idNumber'];
            $PersonnelfamilyModel->birthPlace = $joinPersonnelfamily['birthPlace'];
            $PersonnelfamilyModel->birthDate = AppHelper::convertDateTimeFormat($joinPersonnelfamily['birthDate'], 'd-m-Y', 'Y-m-d');



            if (!$PersonnelfamilyModel->save()) {
                print_r($PersonnelfamilyModel->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        foreach ($arrayContract as $joinPersonnelContract) {
            $PersonnelContractModel = new MsPersonnelContract();
            $PersonnelContractModel->nik = $model->id;
			$PersonnelContractModel->startWorking = AppHelper::convertDateTimeFormat($joinPersonnelContract['startWorking'], 'd-m-Y', 'Y-m-d');
            $PersonnelContractModel->startDate = AppHelper::convertDateTimeFormat($joinPersonnelContract['startContract'], 'd-m-Y', 'Y-m-d');
            $PersonnelContractModel->endDate = AppHelper::convertDateTimeFormat($joinPersonnelContract['endContract'], 'd-m-Y', 'Y-m-d');
            $PersonnelContractModel->docNo = $joinPersonnelContract['docNo'];


            if (!$PersonnelContractModel->save()) {
                print_r($PersonnelContractModel->getErrors());
                $transaction->rollBack();
                return false;
            }
        }



        $transaction->commit();
        return true;
    }

    public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $fullName = $data['fullName'];

            $connection = Yii::$app->db;
            $sql = "SELECT id
			FROM ms_personnelhead
			WHERE fullName = '" . $fullName . "' ";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
        }

        return \yii\helpers\Json::encode($flagExists);
    }

}
