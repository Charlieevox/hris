<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
/**
 * This is the model class for table "tr_minutesofmeetingdetail".
 *
 * @property integer $minutesOfMeetingDetailID
 * @property string $minutesOfMeetingNum
 * @property string $username
 * @property string $taskDescription
 * @property string $dueDate
 * @property boolean $flagFinished
 *
 * @property TrMinutesOfMeetingHead $minutesOfMeetingNum0
 * @property MsUser $user1
 */
class TrMinutesOfMeetingDetail extends \yii\db\ActiveRecord
{
    public $locationID;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_minutesofmeetingdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minutesOfMeetingNum', 'username', 'taskDescription', 'dueDate'], 'required'],
            [['dueDate'], 'safe'],
            [['flagFinished'], 'boolean'],
            [['minutesOfMeetingNum', 'username'], 'string', 'max' => 50],
            [['taskDescription'], 'string', 'max' => 100],
            [['username','taskDescription', 'dueDate','flagFinished', 'locationID'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'minutesOfMeetingDetailID' => 'Minutes Of Meeting Detail ID',
            'minutesOfMeetingNum' => 'Minutes Of Meeting Num',
            'username' => 'Participant',
            'taskDescription' => 'Task Description',
            'dueDate' => 'Due Date',
            'flagFinished' => 'Finished',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinutesOfMeetingNum0()
    {
        return $this->hasOne(TrMinutesOfMeetingHead::className(), ['minutesOfMeetingNum' => 'minutesOfMeetingNum']);
    }
    
     public function getUser1()
    {
        return $this->hasOne(MsUser::className(), ['username' => 'username']);
    }
    
     public function search()
    {
    	$query = self::find()
    	->joinWith('user1')
        ->joinWith('minutesOfMeetingNum0')
    	->andFilterWhere(['like', 'tr_minutesofmeetingdetail.minutesOfMeetingNum', $this->minutesOfMeetingNum])
        ->andFilterWhere(['=', 'tr_minutesofmeetingdetail.username', $this->username])
        ->andFilterWhere(['=', 'tr_minutesofmeetinghead.locationID', $this->locationID])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_minutesofmeetingdetail.dueDate, '%d-%m-%Y')", $this->dueDate])
        ->andFilterWhere(['like', 'tr_minutesofmeetingdetail.taskDescription', $this->taskDescription])
        ->andFilterWhere(['=', 'tr_minutesofmeetingdetail.flagFinished', $this->flagFinished]);
    	    	
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['dueDate' => SORT_ASC],
    					'attributes' => ['dueDate']
    			],
    	]);
        
        $dataProvider->sort->attributes['username'] = [
    			'asc' => ['ms_user.username' => SORT_ASC],
    			'desc' => ['ms_user.username' => SORT_DESC],
    	];
        
        $dataProvider->sort->attributes['minutesOfMeetingNum'] = [
    			'asc' => [self::tableName() . '.minutesOfMeetingNum' => SORT_ASC],
    			'desc' => [self::tableName() . '.minutesOfMeetingNum' => SORT_DESC],
    	];
        
       	$dataProvider->sort->attributes['taskDescription'] = [
    			'asc' => [self::tableName() . '.taskDescription' => SORT_ASC],
    			'desc' => [self::tableName() . '.taskDescription' => SORT_DESC],
    	];
        
        $dataProvider->sort->attributes['flagFinished'] = [
            'asc' => [self::tableName() . '.flagFinished' => SORT_ASC],
            'desc' => [self::tableName() . '.flagFinished' => SORT_DESC],
        ];
    	return $dataProvider;
    }
     public function afterFind(){
    	parent::afterFind();
        $this->dueDate = AppHelper::convertDateTimeFormat($this->dueDate, 'Y-m-d H:i:s', 'd-m-Y');
        
    }
}
