<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_confirmationtopup".
 *
 * @property integer $confirmationID
 * @property string $confirmationDate
 * @property integer $topupID
 * @property string $bankAccount
 * @property string $bankName
 * @property string $accountName
 * @property integer $methodID
 * @property string $subTotal
 */
class TrConfirmationTopUp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_confirmationtopup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['confirmationDate', 'topupID', 'bankAccount', 'bankName', 'accountName', 'methodID', 'subTotal'], 'required'],
            [['confirmationDate'], 'safe'],
            [['topupID', 'methodID'], 'integer'],
            [['subTotal'], 'number'],
            [['bankAccount', 'bankName', 'accountName'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'confirmationID' => 'Confirmation ID',
            'confirmationDate' => 'Confirmation Date',
            'topupID' => 'Topup ID',
            'bankAccount' => 'Bank Account',
            'bankName' => 'Bank Name',
            'accountName' => 'Account Name',
            'methodID' => 'Method ID',
            'subTotal' => 'Sub Total',
        ];
    }
	
	public function getMethods()
    {
        return $this->hasOne(LkMethod::className(), ['methodID' => 'methodID']);
    }
}
