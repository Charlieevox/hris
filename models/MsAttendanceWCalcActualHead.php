<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_personnelwcalcactualhead".
 *
 * @property integer $id
 * @property string $period
 * @property string $nik
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsAttendanceWCalcActualHead extends \yii\db\ActiveRecord {

    public $joinPersonnelwCalcActualDetail;
    public $fullName;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_attendancewcalcactualhead';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'nik', 'period'], 'required'],
            [['id'], 'string', 'max' => 20],
            [['createdDate', 'editedDate'], 'safe'],
            [['period', 'nik', 'createdBy', 'editedBy'], 'string', 'max' => 45],
            [['joinPersonnelwCalcActualDetail','fullName'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'period' => 'Period',
            'nik' => 'FullName',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    public function search() {
        $query = self::find()
                ->joinWith('personnelHead')
                ->andFilterWhere(['=', 'ms_attendancewcalcactualhead.period', $this->period])
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

    public function getPersonnelHead() {
        return $this->hasOne(MsPersonnelHead::className(), ['id' => 'nik']);
    }

    public function getWCalActualdetails() {
        return $this->hasMany(MsAttendanceWCalcActualDetail::className(), ['period' => 'period','nik' => 'nik'] );
    }

    public function afterFind() {
        parent::afterFind();
        $this->joinPersonnelwCalcActualDetail = [];
        $i = 0;
        foreach ($this->getWCalActualdetails()->orderBy('date')->all()   as $joinPersonnelwCalcActualDetail) {
            $this->joinPersonnelwCalcActualDetail[$i]["actionDate"] = AppHelper::convertDateTimeFormat($joinPersonnelwCalcActualDetail->date, 'Y-m-d', 'd-m-Y');
            $this->joinPersonnelwCalcActualDetail[$i]["actionIn"] = $joinPersonnelwCalcActualDetail->inTime;
            $this->joinPersonnelwCalcActualDetail[$i]["actionOut"] = $joinPersonnelwCalcActualDetail->outTime;

            $i += 1;
        }
    }

}
