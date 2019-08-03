<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_personnelwcalcactualdetail".
 *
 * @property integer $id
 * @property string $period
 * @property string $date
 * @property string $in
 * @property string $out
 */
class MsAttendanceWCalcActualDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_attendancewcalcactualdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'string', 'max' => 20],
            [['date', 'inTime', 'outTime'], 'safe'],
            [['period','nik'], 'string', 'max' => 45]
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
            'date' => 'Date',
            'inTime' => 'In',
            'ououtTimet' => 'Out',
        ];
    }
}
