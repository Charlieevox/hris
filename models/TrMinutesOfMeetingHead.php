<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_minutesofmeetinghead".
 *
 * @property string $minutesOfMeetingNum
 * @property string $minutesOfMeetingStart
 * @property string $minutesOfMeetingEnd
 * @property string $username
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $minutesOfMeetingName
 * @property string $minutesOfMeetingApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrMinutesOfMeetingDetail[] $TrMinutesOfMeetingDetails
 * @property MsUser $user0
 */
class TrMinutesOfMeetingHead extends \yii\db\ActiveRecord
{
    public $joinMinutesOfMeetingDetail;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_minutesofmeetinghead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minutesOfMeetingNum', 'minutesOfMeetingStart', 'minutesOfMeetingEnd', 'username', 'locationID', 'minutesOfMeetingName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['minutesOfMeetingStart', 'minutesOfMeetingEnd', 'createdDate', 'editedDate'], 'safe'],
            [['status', 'locationID'], 'integer'],
            [['minutesOfMeetingNum', 'username', 'minutesOfMeetingName', 'minutesOfMeetingApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
            ['minutesOfMeetingStart','validateDates'], 
            [['minutesOfMeetingNum','minutesOfMeetingStart','minutesOfMeetingEnd','username','locationID'], 'safe', 'on'=>'search'],
            [['joinMinutesOfMeetingDetail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'minutesOfMeetingNum' => 'Minutes Of Meeting Number',
            'minutesOfMeetingStart' => 'Meeting Start Time',
            'minutesOfMeetingEnd' => 'Meeting End Time',
            'username' => 'Employee Name',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'minutesOfMeetingName' => 'Minutes Of Meeting Name',
            'minutesOfMeetingApproval' => 'Minutes Of Meeting Approval',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinutesOfMeetingDetails()
    {
        return $this->hasMany(TrMinutesOfMeetingDetail::className(), ['minutesOfMeetingNum' => 'minutesOfMeetingNum']);
    }
    
     public function getUser0()
    {
        return $this->hasOne(MsUser::className(), ['username' => 'username']);
    }
    
    public function search()
    {
    	$query = self::find()
    	->joinWith('user0')
    	->andFilterWhere(['like', 'tr_minutesofmeetinghead.minutesOfMeetingNum', $this->minutesOfMeetingNum])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_minutesofmeetinghead.minutesOfMeetingStart, '%d-%m-%Y')", $this->minutesOfMeetingStart])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_minutesofmeetinghead.minutesOfMeetingEnd, '%d-%m-%Y')", $this->minutesOfMeetingEnd])
        ->andFilterWhere(['=', 'tr_minutesofmeetinghead.locationID', $this->locationID])
    	->andFilterWhere(['=', 'tr_minutesofmeetinghead.username', $this->username]);
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['minutesOfMeetingNum' => SORT_DESC],
    					'attributes' => ['minutesOfMeetingNum']
    			],
    	]);
        
        $dataProvider->sort->attributes['minutesOfMeetingEnd'] = [
    			'asc' => [self::tableName() . '.minutesOfMeetingEnd' => SORT_ASC],
    			'desc' => [self::tableName() . '.minutesOfMeetingEnd' => SORT_DESC],
    	];
        
    	$dataProvider->sort->attributes['minutesOfMeetingStart'] = [
    			'asc' => [self::tableName() . '.minutesOfMeetingStart' => SORT_ASC],
    			'desc' => [self::tableName() . '.minutesOfMeetingStart' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['username'] = [
    			'asc' => ['ms_user.username' => SORT_ASC],
    			'desc' => ['ms_user.username' => SORT_DESC],
    	];
    
    	return $dataProvider;
    }
    
    public function afterFind(){
    	parent::afterFind();
        $this->minutesOfMeetingStart = AppHelper::convertDateTimeFormat($this->minutesOfMeetingStart, 'Y-m-d H:i:s', 'd-m-Y H:i');
        $this->minutesOfMeetingEnd = AppHelper::convertDateTimeFormat($this->minutesOfMeetingEnd, 'Y-m-d H:i:s', 'd-m-Y H:i');
    	$this->joinMinutesOfMeetingDetail = [];
    	$i = 0;
//        echo "<pre>";
//        var_dump($this->getMinutesOfMeetingDetails()->all());
//        echo "</pre>";
    	foreach ($this->getMinutesOfMeetingDetails()->all() as $joinMinutesOfMeetingDetail) {
                $this->joinMinutesOfMeetingDetail[$i]["username"] = $joinMinutesOfMeetingDetail->username;
    		$this->joinMinutesOfMeetingDetail[$i]["fullName"] = $joinMinutesOfMeetingDetail->user1->fullName;
                $this->joinMinutesOfMeetingDetail[$i]["taskDescription"] = $joinMinutesOfMeetingDetail->taskDescription;
    		$this->joinMinutesOfMeetingDetail[$i]["dueDate"] = $joinMinutesOfMeetingDetail->dueDate;;
    		$this->joinMinutesOfMeetingDetail[$i]["flagFinishedValue"] = ($joinMinutesOfMeetingDetail->flagFinished ? 1 : 0);
                $this->joinMinutesOfMeetingDetail[$i]["flagFinished"] = ($joinMinutesOfMeetingDetail->flagFinished ? "checked" : "");
    		$i += 1;
    	}
    }
	
	public function validateDates(){
		if(strtotime($this->minutesOfMeetingEnd) < strtotime($this->minutesOfMeetingStart)){
			$this->addError('minutesOfMeetingEnd','Meeting End must be greater than or equal to Meeting Start');
			$this->addError('minutesOfMeetingStart','Meeting Start Must be less than or equal to Meeting End ');
		}
	}
}
