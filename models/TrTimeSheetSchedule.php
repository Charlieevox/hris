<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
use app\models\TrJob;
use yii\web\NotFoundHttpException;
/**
 * This is the model class for table "tr_timesheetschedule".
 *
 * @property string $timesheetScheduleNum
 * @property string $timesheetScheduleFromDate
 * @property string $timesheetScheduleToDate
 * @property string $username
 * @property string $timesheetScheduleDesc
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $timesheetScheduleName
 * @property string $timesheetScheduleApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsUser $username0
 */
class TrTimeSheetSchedule extends \yii\db\ActiveRecord
{
    public $projectNames;
    public $fromDates;
    public $endDates;
    public $clientJob;
    public $joinJobs;
    public $number;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_timesheetschedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timesheetScheduleNum', 'timesheetScheduleFromDate', 'timesheetScheduleToDate', 'username', 'locationID', 'timesheetScheduleDesc', 'timesheetScheduleName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['timesheetScheduleFromDate', 'timesheetScheduleToDate', 'createdDate', 'editedDate'], 'safe'],
            [['status','jobID','locationID'], 'integer'],
            [['timesheetScheduleNum', 'username', 'timesheetScheduleName', 'timesheetScheduleApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['timesheetScheduleDesc', 'additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
            ['timesheetScheduleFromDate','validateDates'], 
            [['timesheetScheduleNum','timesheetScheduleFromDate','timesheetScheduleToDate','username','timesheetScheduleDesc','locationID','clientJob'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'timesheetScheduleNum' => 'TimeSheet Schedule Number',
            'timesheetScheduleFromDate' => 'From Date',
            'timesheetScheduleToDate' => 'To Date',
            'username' => 'Employee Name',
            'timesheetScheduleDesc' => 'Description',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'timesheetScheduleName' => 'Timesheet Schedule Name',
            'timesheetScheduleApproval' => 'Timesheet Schedule Approval',
            'status' => 'Status',
			'jobID' => 'Project Name',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'projectNames' => 'Project Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsername0()
    {
        return $this->hasOne(MsUser::className(), ['username' => 'username']);
    }
	
	 public function getJobs()
    {
        return $this->hasOne(TrJob::className(), ['jobID' => 'jobID']);
    }
    
    public function search()
    {
    	$query = self::find()
    	->joinWith('username0')
		->joinWith('jobs')
    	->andFilterWhere(['like', 'tr_timesheetschedule.timesheetScheduleNum', $this->timesheetScheduleNum])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_timesheetschedule.timesheetScheduleFromDate, '%d-%m-%Y')", $this->timesheetScheduleFromDate])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_timesheetschedule.timesheetScheduleToDate, '%d-%m-%Y')", $this->timesheetScheduleToDate])
    	->andFilterWhere(['=', 'tr_timesheetschedule.username', $this->username])
        ->andFilterWhere(['=', 'tr_timesheetschedule.locationID', $this->locationID])
        ->andFilterWhere(['=', 'tr_timesheetschedule.jobID', $this->jobID])
        ->andFilterWhere(['like', 'tr_job.projectName', $this->clientJob])
        ->andFilterWhere(['like', 'tr_timesheetschedule.timesheetScheduleDesc', $this->timesheetScheduleDesc]);
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['timesheetScheduleFromDate' => SORT_DESC],
    					'attributes' => ['timesheetScheduleFromDate']
    			],
    	]);
        
        $dataProvider->sort->attributes['timesheetScheduleToDate'] = [
    			'asc' => [self::tableName() . '.timesheetScheduleToDate' => SORT_ASC],
    			'desc' => [self::tableName() . '.timesheetScheduleToDate' => SORT_DESC],
    	];
        
    	$dataProvider->sort->attributes['timesheetScheduleNum'] = [
    			'asc' => [self::tableName() . '.timesheetScheduleNum' => SORT_ASC],
    			'desc' => [self::tableName() . '.timesheetScheduleNum' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['username'] = [
    			'asc' => ['ms_user.username' => SORT_ASC],
    			'desc' => ['ms_user.username' => SORT_DESC],
    	];
    
        $dataProvider->sort->attributes['timesheetScheduleDesc'] = [
    			'asc' => [self::tableName() . '.timesheetScheduleDesc' => SORT_ASC],
    			'desc' => [self::tableName() . '.timesheetScheduleDesc' => SORT_DESC],
    	];
		
    	return $dataProvider;
    }
    
    public function afterFind(){
    	parent::afterFind();
        $this->fromDates = $this->timesheetScheduleFromDate;
        $this->endDates = $this->timesheetScheduleToDate;
        
        $connection = Yii::$app->db;
        $sql = "SELECT CONCAT(d.fullName, '-',c.clientName, '-' , b.projectName) AS clientJob
        FROM tr_timesheetschedule a
        LEFT JOIN tr_job b on a.jobID = b.jobID
        LEFT JOIN ms_client c on b.clientID = c.clientID
        LEFT JOIN ms_user d on a.username = d.username
        WHERE a.jobID IS NOT NULL AND a.timesheetScheduleNum = '" . $this->timesheetScheduleNum . "'
        ORDER BY projectName ";
		
        $temp = $connection->createCommand($sql);
	$result = $temp->queryAll();
        $result = \yii\helpers\ArrayHelper::getValue($result, 0);
        $this->clientJob = $result['clientJob'];
          
        $this->timesheetScheduleFromDate = AppHelper::convertDateTimeFormat($this->timesheetScheduleFromDate, 'Y-m-d H:i:s', 'd-m-Y');
        $this->timesheetScheduleToDate = AppHelper::convertDateTimeFormat($this->timesheetScheduleToDate, 'Y-m-d H:i:s', 'd-m-Y');
    	
    }
    
    public function validateDates(){
		if(strtotime($this->timesheetScheduleToDate) < strtotime($this->timesheetScheduleFromDate)){
			$this->addError('timesheetScheduleToDate','To Date must be greater than or equal to From Date');
			$this->addError('timesheetScheduleFromDate','From Date Must be less than or equal to To Date ');
		}
	}
}
