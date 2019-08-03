<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_personnelwcalcdet".
 *
 * @property string $period
 * @property string $nik
 * @property string $date
 * @property string $shiftCode
 */
class MsAttendanceWCalcDet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_attendancewcalcdet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'string', 'max' => 20],
            [['period','nik'], 'string', 'max' => 15],
            [['date'], 'safe'],
            [['period'], 'string', 'max' => 15],
            [['nik', 'shiftCode'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'period' => 'Period',
            'nik' => 'Nik',
            'date' => 'Date',
            'shiftCode' => 'Shift Code',
        ];
    }
}
