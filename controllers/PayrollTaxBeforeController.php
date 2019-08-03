<?php

namespace app\controllers;

use Yii;
use app\models\MsPayrollTaxBefore;
use app\models\MsPayrollTaxBeforeDetail;
use app\models\MsPersonnelHead;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\components\AccessRule;
use app\models\Location;
use kartik\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
use app\components\ControllerUAC;
use yii\helpers\Json;

/**
 * PayrollTaxBeforeController implements the CRUD actions for MsPayrollTaxBefore model.
 */
class PayrollTaxBeforeController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all MsPayrollTaxBefore models.
     * @return mixed
     */
    public function actionIndex()
    {
		$model = new MsPayrollTaxBefore();
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPayrollTaxBefore model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPayrollTaxBefore model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MsPayrollTaxBefore();
		$personnelModel = new MsPersonnelHead();
		$model->joinPayrollTaxIncomeDetail = [];
		$model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->saveModel($model, true)) {

                AppHelper::insertTransactionLog('Create Working Schedule', $model->period);
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'personnelModel' => $personnelModel,
            ]);
        }
    }

    /**
     * Updates an existing MsPayrollTaxBefore model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$personnelModel = MsPersonnelHead::findOne($model->nik);

         if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');

            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Working Calendar', $model->id);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    /**
     * Deletes an existing MsPayrollTaxBefore model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();

        MsPayrollTaxBeforeDetail::deleteAll('id = :id', [':id' => $model->id]);
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Payroll Tax Before Detail```````````````', $model->id);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the MsPayrollTaxBefore model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsPayrollTaxBefore the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsPayrollTaxBefore::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	 public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $id = $data['id'];

            $connection = Yii::$app->db;
            $sql = "SELECT id
			FROM ms_payrolltaxbefore
			WHERE id = '" . $id . "' ";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
        }

        return \yii\helpers\Json::encode($flagExists);
    }
	
	
   protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $model->id = $model->year . '-' . $model->nik;
        if (!$model->save()) {
			print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }


        MsPayrollTaxBeforeDetail::deleteAll('id = :id', [":id" => $model->id]);
		
        if (empty($model->joinPayrollTaxIncomeDetail) || !is_array($model->joinPayrollTaxIncomeDetail) || count($model->joinPayrollTaxIncomeDetail) < 1) {
            $transaction->rollBack();
            return false;
        }

        
        foreach ($model->joinPayrollTaxIncomeDetail as $PayrollTaxIncomeDetail) {
            $PayrollTaxIncomeDetailModel = new MsPayrollTaxBeforeDetail();
            $PayrollTaxIncomeDetailModel->id = $model->year . '-' . $model->nik;
            $PayrollTaxIncomeDetailModel->nomor = $PayrollTaxIncomeDetail['actionNumber'];
            $PayrollTaxIncomeDetailModel->periodStart = AppHelper::convertDateTimeFormat($PayrollTaxIncomeDetail['actionStartDate'], 'd-m-Y', 'Y-m-d');
			$PayrollTaxIncomeDetailModel->periodEnd = AppHelper::convertDateTimeFormat($PayrollTaxIncomeDetail['actionEndDate'], 'd-m-Y', 'Y-m-d');
			$PayrollTaxIncomeDetailModel->npwpCompany = $PayrollTaxIncomeDetail['actionNPWPCompany'];
			$PayrollTaxIncomeDetailModel->company =$PayrollTaxIncomeDetail['actionCompany']; 
			$PayrollTaxIncomeDetailModel->netto = str_replace(",", ".", str_replace(".", "", $PayrollTaxIncomeDetail['actionNetto']));
			$PayrollTaxIncomeDetailModel->taxPaid = str_replace(",", ".", str_replace(".", "", $PayrollTaxIncomeDetail['actionTaxPaid'])); 

            
            if (!$PayrollTaxIncomeDetailModel->save()) {
                print_r($PayrollTaxIncomeDetailModel->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }
	
}
