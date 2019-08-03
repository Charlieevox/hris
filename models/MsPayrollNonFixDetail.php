<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_payrollNonFixDetail".
 *
 * @property string $nik
 * @property string $period
 * @property string $payrollCode
 * @property string $amount
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsPayrollNonFixDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_payrollnonfixdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nik'], 'required'],
            [['amount'], 'number'],
            [['createdDate', 'editedDate'], 'safe'],
            [['nik'], 'string', 'max' => 20],
            [['period', 'payrollCode', 'createdBy', 'editedBy'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nik' => 'Nik',
            'period' => 'Period',
            'payrollCode' => 'Payroll Code',
            'amount' => 'Amount',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }
    
    public function getPayrollComponentDesc()
    {
        return $this->hasOne(MsPayrollComponent::className(), ['payrollCode' => 'payrollCode']);
    }
}
