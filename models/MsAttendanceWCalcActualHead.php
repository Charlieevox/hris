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
            [['joinPersonnelwCalcActualDetail'], 'safe']
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
                ->andFilterWhere(['like', 'ms_attendancewcalcactualhead.period', $this->period])
                ->andFilterWhere(['like', 'ms_attendancewcalcactualhead.nik', $this->nik]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['nik' => SORT_ASC],
                'attributes' => ['nik', 'period']
            ],
        ]);

        $dataProvider->sort->attributes['nik'] = [
            'asc' => [self::tableName() . '.nik' => SORT_ASC],
            'desc' => [self::tableName() . '.nik' => SORT_DESC],
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
        foreach ($this->getWCalActualdetails()->all() as $joinPersonnelwCalcActualDetail) {
            $this->joinPersonnelwCalcActualDetail[$i]["actionDate"] = AppHelper::convertDateTimeFormat($joinPersonnelwCalcActualDetail->date, 'Y-m-d', 'd-m-Y');
            $this->joinPersonnelwCalcActualDetail[$i]["actionIn"] = $joinPersonnelwCalcActualDetail->inTime;
            $this->joinPersonnelwCalcActualDetail[$i]["actionOut"] = $joinPersonnelwCalcActualDetail->outTime;

            $i += 1;
        }
    }

}
