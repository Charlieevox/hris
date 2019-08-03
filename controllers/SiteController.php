<?php

namespace app\controllers;

use app\models\ChangePasswordForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\_Index;
use yii\data\SqlDataProvider;
use app\models\TrJob;
use app\models\MsPersonnelHead;
use yii\helpers\Url;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
		$count = Yii::$app->db->createCommand("Select count(*) from ms_personnelhead
					where id not in (select nik from ms_payrollincomedetail)")->queryScalar();

			$sql = "Select id,
					fullname,
					'Member Not Register In Payroll Income' as 'Remarks',
					'A01' as 'allertId'
					from ms_personnelhead
					where id not in (select nik from ms_payrollincomedetail)";

			$model = new SqlDataProvider([
				'sql' => $sql,
				'totalCount' => $count,
				'key' => 'id',
			]);

			return $this->render('index', [
						'model' => $model
			]);
    }

    public function actionCheck($id, $filter) {
        if ($filter == 'A01') {
            $url = Url::to(['payroll-income/index']);
        } else {
            $url = Url::to(['personnel-head/view', 'id' => $id]);
        }

        return "<script>
                    window.location.href = '$url';
                </script>";
    }

    protected function findModel($id, $id) {
        if (($model = TrJob::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTimeline() {
        $resources = '';
        $connection = Yii::$app->db;
        $sql = "SELECT username, fullName 
                FROM ms_user 
                ORDER BY fullName ASC ";
        $model = $connection->createCommand($sql);
        $result = $model->queryAll();
        foreach ($result as $row) {
            $resources .= '{ name: "' . $row['fullName'] . '", id: "' . $row['username'] . '" },';
        }

        $sql = "SELECT a.timesheetScheduleFromDate 'startDate', a.timesheetScheduleToDate 'endDate', a.username,
                b.fullName, c.projectName, a.timesheetScheduleNum 
                FROM tr_timesheetschedule a 
                JOIN ms_user b on a.username = b.username 
                JOIN tr_job c on a.jobID = c.jobID ";
        $model = $connection->createCommand($sql);
        $events = $model->queryAll();

        return $this->render('timeline', [
                    'resources' => $resources,
                    'events' => $events
        ]);
    }

    public function actionLogin() {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'mainLogin';
            $model = new LoginForm();

            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                //date_default_timezone_set(Yii::$app->user->identity->timeZone);
                //Yii::$app->setTimeZone(Yii::$app->user->identity->timeZone);
                //print_r(Yii::$app->getTimeZone());
                $this->layout = 'main';
                return $this->goBack(['site/index']);
            } else {
                return $this->render('login', [
                            'model' => $model,
                ]);
            }
        } else {
            $model = new _Index();
            $model->load(Yii::$app->request->queryParams);
            return $this->render('index', [
                        'model' => $model
            ]);
        }
    }

    public function actionLogout() {
        if (!\Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        return $this->goHome();
    }

    public function actionChangePassword() {
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('changepassword', [
                        'model' => $model,
            ]);
        }
    }

}
