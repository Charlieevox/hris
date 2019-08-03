<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_payrollincomedetail".
 *
 * @property integer $nik
 * @property string $payrollCode
 * @property double $amount
 * @property string $startDate
 * @property string $endDate
 * @property string $createBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsPayrollIncomeDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_payrollincomedetail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nik'], 'required'],
            [['nik'], 'integer'],
            [['amount'], 'number'],
            [['startDate', 'endDate', 'createdDate', 'editedDate'], 'safe'],
            [['payrollCode', 'createdBy', 'editedBy'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'nik' => 'Nik',
            'payrollCode' => 'Payroll Code',
            'amount' => 'Amount',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
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
    
    public function getPayrollType()
    {
        return $this->hasOne(MsPayrollComponent::className(), ['payrollCode' => 'payrollCode']);
    }
    
    
}
