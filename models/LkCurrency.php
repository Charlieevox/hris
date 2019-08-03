<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_currency".
 *
 * @property string $currencyID
 * @property string $currencyName
 * @property string $currencySign
 * @property string $rate
 *
 * @property TrPurchaseOrderHead[] $trpurchaseorderhead
 * @property TrSalesOrderHead[] $trsalesorderhead
 */
class LkCurrency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return  'lk_currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currencyID', 'currencyName', 'currencySign', 'rate'], 'required'],
            [['rate'], 'number'],
            [['currencyID'], 'string', 'max' => 5],
            [['currencyName'], 'string', 'max' => 50],
            [['currencySign'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currencyID' => 'Currency ID',
            'currencyName' => 'Currency Name',
            'currencySign' => 'Currency Sign',
            'rate' => 'Rate',
        ];
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrPurchaseOrderHeads()
    {
        return $this->hasMany(TrPurchaseOrderHead::className(), ['currencyID' => 'currencyID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrSalesOrderHeads()
    {
        return $this->hasMany(TrSalesOrderHead::className(), ['currencyID' => 'currencyID']);
    }
}
