<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_payrolltaxincome".
 *
 * @property string $period
 * @property string $nik
 * @property string $T01
 * @property string $T03
 * @property string $T04
 * @property string $T05
 * @property string $T06
 * @property string $T07
 * @property string $T10
 * @property string $NettoBefore
 * @property string $PPhBefore
 */
class TrPayrollTaxIncome extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_payrolltaxincome';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['T01', 'T03', 'T04', 'T05', 'T06', 'T07', 'T10', 'NettoBefore', 'PPhBefore'], 'number'],
            [['period'], 'string', 'max' => 8],
            [['nik'], 'string', 'max' => 20]
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
            'T01' => 'T01',
            'T03' => 'T03',
            'T04' => 'T04',
            'T05' => 'T05',
            'T06' => 'T06',
            'T07' => 'T07',
            'T10' => 'T10',
            'NettoBefore' => 'Netto Before',
            'PPhBefore' => 'Pph Before',
        ];
    }
}
