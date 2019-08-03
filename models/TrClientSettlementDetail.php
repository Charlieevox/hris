<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_clientsettlementdetail".
 *
 * @property integer $settlementDetailID
 * @property string $settlementNum
 * @property string $salesNum
 * @property string $tax
 * @property string $settlementTotal
 *
 * @property TrClientsettlementhead $settlementNum0
 */
class TrClientSettlementDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_clientsettlementdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['settlementNum', 'salesNum', 'tax', 'settlementTotal'], 'required'],
            [['tax', 'settlementTotal'], 'number'],
            [['settlementNum', 'salesNum'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'settlementDetailID' => 'Settlement Detail ID',
            'settlementNum' => 'Settlement Num',
            'salesNum' => 'Sales Num',
            'tax' => 'Tax',
            'settlementTotal' => 'Settlement Total',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettlementNum0()
    {
        return $this->hasOne(TrClientSettlementHead::className(), ['settlementNum' => 'settlementNum']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesNum0()
    {
        return $this->hasOne(TrSalesOrderHead::className(), ['salesNum' => 'salesNum']);
    }
}
