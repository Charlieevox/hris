<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_payrollfixdetail".
 *
 * @property integer $nik
 * @property string $payrollCode
 * @property string $amount
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsPayrollFixDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_payrollfixdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nik'], 'required'],
            [['nik'], 'integer'],
            [['amount'], 'string'],
            [['createdDate', 'editedDate'], 'safe'],
            [['payrollCode'], 'string', 'max' => 10],
            [['createdBy', 'editedBy'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nik' => 'Nik',
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
