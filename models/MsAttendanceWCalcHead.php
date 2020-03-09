<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
/**
 * This is the model class for table "ms_personnelwcalchead".
 *
 * @property integer $period
 * @property string $nik
 */
class MsAttendanceWCalcHead extends \yii\db\ActiveRecord
{
    public $joinPersonnelwCalcDetail;
    public $fullName;
     
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_attendancewcalchead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','nik','period'], 'required'],
            [['id'], 'string', 'max' => 20],
            [['period'], 'string', 'max' => 15],
            [['nik','createdBy', 'editedBy' ], 'string', 'max' => 45],
            [['joinPersonnelwCalcDetail','createdDate','editedDate','fullName'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'period' => 'Period',
            'nik' => 'Employee',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }
    
	public function search() {
        $query = self::find()
                ->joinWith('personnelHead')
                ->andFilterWhere(['=', 'ms_attendancewcalchead.period', $this->period])
                ->andFilterWhere(['like', 'ms_personnelhead.fullname', $this->fullName]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['fullName' => SORT_ASC],
                'attributes' => ['period','fullName']
            ],
        ]);

        
        $dataProvider->sort->attributes['fullName'] = [
            'asc' => ['ms_personnelhead.fullName' => SORT_ASC],
            'desc' => ['ms_personnelhead.fullName' => SORT_DESC],
        ];

        return $dataProvider;
    }
    
    public function getWCaldetails()
    {
        return $this->hasMany(MsAttendanceWCalcDet::className(), ['id' => 'id']);
    }
    
    
    public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'nik']);
    }
    
    public function afterFind() {
        parent::afterFind();
        $this->joinPersonnelwCalcDetail = [];
        $i = 0;
        foreach ($this->getWCaldetails()->orderBy('date')->all() as $joinPersonnelwCalcDetail) {
            $this->joinPersonnelwCalcDetail[$i]["actionDate"] = AppHelper::convertDateTimeFormat($joinPersonnelwCalcDetail->date, 'Y-m-d', 'd-m-Y');
            $this->joinPersonnelwCalcDetail[$i]["actionSchedule"] = $joinPersonnelwCalcDetail->shiftCode;
            $i += 1;
        }
    }
    
}
