<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_attendanceholiday".
 *
 * @property integer $id
 * @property string $date
 * @property string $holidayDescription
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editBy
 * @property string $editDate
 * @property boolean $flagActive
 */
class MsAttendanceHoliday extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_attendanceholiday';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['date', 'createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
            [['holidayDescription', 'createdBy', 'editedBy'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'holidayDescription' => 'Holiday Description',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edit By',
            'editedDate' => 'Edit Date',
            'flagActive' => 'Flag Active',
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_attendanceholiday.holidayDescription', $this->holidayDescription])
                ->andFilterWhere(['=', "DATE_FORMAT(ms_attendanceholiday.date, '%d-%m-%Y')", $this->date])
                ->andFilterWhere(['=', 'ms_attendanceholiday.flagActive', $this->flagActive]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date' => SORT_ASC],
                'attributes' => ['holidayDescription', 'date']
            ],
        ]);

//        echo "<pre>";
//        var_dump($dataProvider);
//        echo "</pre>";
//        Yii::$app->end();

        $dataProvider->sort->attributes['holidayDescription'] = [
            'asc' => [self::tableName() . '.holidayDescription' => SORT_ASC],
            'desc' => [self::tableName() . '.holidayDescription' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }

    public function afterFind() {
        parent::afterFind();
        $this->date = AppHelper::convertDateTimeFormat($this->date, 'Y-m-d', 'd-m-Y');
    }

}
