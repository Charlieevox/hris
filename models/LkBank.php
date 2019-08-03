<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_bank".
 *
 * @property integer $bankID
 * @property string $bankName
 * @property string $bankAccount
 */
class LkBank extends \yii\db\ActiveRecord
{
	public $nameComb;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.lk_bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bankID', 'bankName', 'bankAccount', 'accountName'], 'required'],
            [['bankID'], 'integer'],
            [['bankName', 'bankAccount', 'accountName'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bankID' => 'Bank ID',
            'bankName' => 'Bank Name',
            'bankAccount' => 'Bank Account',
			'accountName' => 'Account Name',
			'nameComb' => 'Description',
        ];
    }
	
	public function afterFind(){
        parent::afterFind();
		$this->nameComb = $this->bankName . " - " . $this->bankAccount . " - " . $this->accountName;
		
	}
}
