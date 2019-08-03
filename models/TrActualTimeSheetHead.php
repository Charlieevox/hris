<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
/**
 * This is the model class for table "tr_actualtimesheethead".
 *
 * @property string $actualTimesheetNum
 * @property string $actualTimesheetDate
 * @property string $username
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $actualTimesheetName
 * @property string $actualTimesheetApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrActualTimeSheetDetail[] $trActualtimesheetdetails
 * @property MsUser $user
 */
class TrActualTimeSheetHead extends \yii\db\ActiveRecord
{
    public $joinActualTimeSheetDetail;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_actualtimesheethead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['actualTimesheetNum', 'actualTimesheetDate', 'username', 'locationID', 'actualTimesheetName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['actualTimesheetDate', 'createdDate', 'editedDate'], 'safe'],
            [['status'], 'integer'],
            [['actualTimesheetNum', 'username', 'actualTimesheetName', 'actualTimesheetApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
            [['actualTimesheetNum','actualTimesheetDate','username'], 'safe', 'on'=>'search'],
            [['joinActualTimeSheetDetail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'actualTimesheetNum' => 'Actual TimeSheet Number',
            'actualTimesheetDate' => 'Actual TimeSheet Date',
            'username' => 'Employee Name',
            'customerID' => 'customerID',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'actualTimesheetName' => 'Actual Timesheet Name',
            'actualTimesheetApproval' => 'Actual Timesheet Approval',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'locationID' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActualtimesheetdetails()
    {
        return $this->hasMany(TrActualTimeSheetDetail::className(), ['actualTimesheetNum' => 'actualTimesheetNum']);
    }
    
    public function getUser()
    {
        return $this->hasOne(MsUser::className(), ['username' => 'username']);
    }
    
     public function search()
    {
    	$query = self::find()
    	->joinWith('user')
    	->andFilterWhere(['like', 'tr_actualtimesheethead.actualTimesheetNum', $this->actualTimesheetNum])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_actualtimesheethead.actualTimesheetDate, '%d-%m-%Y')", $this->actualTimesheetDate])
    	->andFilterWhere(['=', 'tr_actualtimesheethead.locationID', $this->locationID])
        ->andFilterWhere(['=', 'tr_actualtimesheethead.username', $this->username]);
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['actualTimesheetNum' => SORT_DESC],
    					'attributes' => ['actualTimesheetNum']
    			],
    	]);
    
    	$dataProvider->sort->attributes['actualTimesheetDate'] = [
    			'asc' => [self::tableName() . '.actualTimesheetDate' => SORT_ASC],
    			'desc' => [self::tableName() . '.actualTimesheetDate' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['username'] = [
    			'asc' => ['ms_user.username' => SORT_ASC],
    			'desc' => ['ms_user.username' => SORT_DESC],
    	];
		
    	return $dataProvider;
    }
    
    public function afterFind(){
    	parent::afterFind();
        $this->actualTimesheetDate = AppHelper::convertDateTimeFormat($this->actualTimesheetDate, 'Y-m-d H:i:s', 'd-m-Y');
    	$this->joinActualTimeSheetDetail = [];
    	$i = 0;
    	foreach ($this->getActualtimesheetdetails()->all() as $joinActualTimeSheetDetail) {
    		$this->joinActualTimeSheetDetail[$i]["timeQty"] = $joinActualTimeSheetDetail->timeQty;
                $this->joinActualTimeSheetDetail[$i]["clientID"] = $joinActualTimeSheetDetail->clientID;
    		$this->joinActualTimeSheetDetail[$i]["clientName"] = $joinActualTimeSheetDetail->client->clientName;
    		$this->joinActualTimeSheetDetail[$i]["description"] = $joinActualTimeSheetDetail->description;
    		$i += 1;
    	}
    }
}
