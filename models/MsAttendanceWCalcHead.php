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
            [['joinPersonnelwCalcDetail','createdDate','editedDate'], 'safe']
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
                ->andFilterWhere(['like', 'ms_attendancewcalchead.period', $this->period])
                ->andFilterWhere(['like', 'ms_attendancewcalchead.nik', $this->nik]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['nik' => SORT_ASC],
                'attributes' => ['nik','period']
            ],
        ]);

        $dataProvider->sort->attributes['nik'] = [
            'asc' => [self::tableName() . '.nik' => SORT_ASC],
            'desc' => [self::tableName() . '.nik' => SORT_DESC],
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
        foreach ($this->getWCaldetails()->all() as $joinPersonnelwCalcDetail) {
            $this->joinPersonnelwCalcDetail[$i]["actionDate"] = AppHelper::convertDateTimeFormat($joinPersonnelwCalcDetail->date, 'Y-m-d', 'd-m-Y');
            $this->joinPersonnelwCalcDetail[$i]["actionSchedule"] = $joinPersonnelwCalcDetail->shiftCode;
            $i += 1;
        }
    }
    
}
