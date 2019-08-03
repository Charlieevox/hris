<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_attendanceovertime".
 *
 * @property string $overtimeId
 * @property string $rate1
 * @property string $rate2
 * @property string $rate3
 * @property string $rate4
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsAttendanceOvertime extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_attendanceovertime';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['overtimeId'], 'required'],
            [['rate1', 'rate2', 'rate3', 'rate4'], 'number'],
            [['createdDate', 'editedDate'], 'safe'],
            [['overtimeId'], 'string', 'max' => 20],
            [['createdBy', 'editedBy'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'overtimeId' => 'Overtime Code',
            'rate1' => 'Amount In Minute',
            'rate2' => '2nd Hour',
            'rate3' => '3rd Hour',
            'rate4' => '4th Hour',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_attendanceovertime.overtimeId', $this->overtimeId])
                ->andFilterWhere(['like', 'ms_attendanceovertime.rate1', $this->rate1])
                ->andFilterWhere(['like', 'ms_attendanceovertime.rate2', $this->rate2])
                ->andFilterWhere(['like', 'ms_attendanceovertime.rate3', $this->rate3])
                ->andFilterWhere(['like', 'ms_attendanceovertime.Rate4', $this->rate4]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['overtimeId' => SORT_ASC],
                'attributes' => ['overtimeId', 'rate1','rate2','rate3','rate4']
            ],
        ]);

        $dataProvider->sort->attributes['rate1'] = [
            'asc' => [self::tableName() . '.rate1' => SORT_ASC],
            'desc' => [self::tableName() . '.rate1' => SORT_DESC],
        ];

        return $dataProvider;
    }

}
