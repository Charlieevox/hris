<?php

namespace app\controllers;

use Yii;
use app\models\ReportTax;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\ControllerUAC;

/**
 * ReportPphController implements the CRUD actions for ReportPph model.
 */
class ReportTaxController extends ControllerUAC {

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

    /**
     * Lists all ReportPph models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new ReportTax();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $_report = Yii::$app->request->post('ReportTax');

            $periode = $_report['period'];
            $id = $_report['id'];

            if (isset($_POST['btnPrint_PDF'])) {
                $url = Url::to(['report-tax/print', 'periode' => $periode, 'id' => $id]);
                $redirectTo = Url::to(['report-tax/index']);
                return "<script>
                            var newWindow = window.open('$url','_blank');
                            if (window.focus) {
                                newWindow.focus();
                            }
                            window.location.href = '$redirectTo';
                      </script>";
            }
        } else {
            return $this->render('index', [
                        'model' => $model
            ]);
        }
    }

    protected function findModel($id) {
        if (($model = ReportPph::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPrint($periode, $id) {
        $this->layout = false;
        $content = $this->render('_reportView', [
            'periode' => $periode,
            'id' => $id,
        ]);

        $pdf = Yii::$app->pdfPayrollLandscape;
        $pdf->content = $content;
		$pdf->options = ['title' => 'Tax Monthly'];
        return $pdf->render();
//        return $this->render('_reportView', [
//            'periode' => $periode,
//            'fullname' => $fullname,
//        ]);
    }

}
