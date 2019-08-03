<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_personnelshift".
 *
 * @property integer $shiftCode
 * @property string $start
 * @property string $end
 * @property integer $overnight
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsAttendanceShift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_attendanceshift';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shiftCode'], 'unique'],
            [['shiftCode'], 'required'],
            [['overnight'], 'integer'],
            [['start', 'end', 'createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
            [['shiftCode'], 'string', 'max' => 40],
            [['createdBy', 'editedBy'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shitfCode' => 'Shift Code',
            'start' => 'Start',
            'end' => 'End',
            'overnight' => 'Overnight',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
        ];
    }

    public function search() {
    $query = self::find()
            ->andFilterWhere(['=', 'ms_attendanceshift.overnight', $this->overnight])
            ->andFilterWhere(['like', 'ms_attendanceshift.shiftCode', $this->shiftCode])
            ->andFilterWhere(['=', 'ms_attendanceshift.flagActive', $this->flagActive]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'defaultOrder' => ['shiftCode' => SORT_ASC],
            'attributes' => ['shiftCode','overnight']
        ],
    ]);

    $dataProvider->sort->attributes['shiftCode'] = [
        'asc' => [self::tableName() . '.shiftCode' => SORT_ASC],
        'desc' => [self::tableName() . '.shiftCode' => SORT_DESC],
    ];

    $dataProvider->sort->attributes['flagActive'] = [
        'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
        'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
    ];
    return $dataProvider;
}

    
}
