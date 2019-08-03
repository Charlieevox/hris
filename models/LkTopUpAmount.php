<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_topupamount".
 *
 * @property integer $topupAmountID
 * @property string $amount
 */
class LkTopUpAmount extends \yii\db\ActiveRecord
{
	public $amountSep;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.lk_topupamount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'topupAmountID' => 'Topup Amount ID',
            'amount' => 'Amount',
        ];
    }
	
	public function afterFind(){
        parent::afterFind();
		$this->amountSep = number_format($this->amount,2,",",".");
		
	}
}
