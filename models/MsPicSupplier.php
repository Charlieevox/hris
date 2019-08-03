<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_picsupplier".
 *
 * @property integer $picSupplierID
 * @property integer $supplierID
 * @property integer $greetingID
 * @property string $picName
 * @property string $email
 * @property string $cellPhone
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsPicSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_picsupplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['picName', 'supplierID','greetingID','email','cellPhone'], 'required'],
            [['supplierID', 'greetingID'], 'integer'],
            [['createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
            [['picName', 'email', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['cellPhone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'picSupplierID' => 'Pic Supplier ID',
            'supplierID' => 'Supplier ID',
            'greetingID' => 'Greeting ID',
            'picName' => 'Pic Name',
            'email' => 'Email',
            'cellPhone' => 'Cell Phone',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
        ];
    }
    
        public function getGreets()
    {
        return $this->hasOne(LkGreeting::className(), ['greetingID' => 'greetingID']);
    }
    
    	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
}
