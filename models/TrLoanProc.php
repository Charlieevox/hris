<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_loanproc".
 *
 * @property integer $id
 * @property string $paymentPeriod
 * @property string $principalPaid
 */
class TrLoanProc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_loanproc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['principalPaid'], 'number'],
            [['paymentPeriod'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'paymentPeriod' => 'Payment Period',
            'principalPaid' => 'Principal Paid',
        ];
    }
}
