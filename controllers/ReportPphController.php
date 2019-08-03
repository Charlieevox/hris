<?php

namespace app\controllers;

use Yii;
use app\models\ReportPph;
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
class ReportPphController extends ControllerUAC {

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
        $model = new ReportPph();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $_report = Yii::$app->request->post('ReportPph');

            $periode = $_report['period'];
            $fullname = $_report['fullName'];

            if (isset($_POST['btnPrint_PDF'])) {
                $url = Url::to(['report-pph/print', 'periode' => $periode, 'fullname' => $fullname]);
                $redirectTo = Url::to(['report-pph/index']);
                return "<script>
                            var newWindow = window.open('$url','name','height=600,width=1024');
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

    public function actionPrint($periode, $fullname) {
        $this->layout = false;
        $content = $this->render('_reportView', [
            'periode' => $periode,
            'fullname' => $fullname,
        ]);

        $pdf = Yii::$app->pdfPayroll;
        $pdf->content = $content;
        return $pdf->render();
//        return $this->render('_reportView', [
//            'periode' => $periode,
//            'fullname' => $fullname,
//        ]);
    }

}
