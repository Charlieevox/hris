<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "ms_location".
 *
 * @property integer $locationID
 * @property string $locationCode
 * @property string $locationName
 * @property string $address
 * @property string $phone
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsUser[] $msUsers
 * @property Stock[] $stocks
 * @property MsProductdetail[] $barcodeNumbers
 * @property TrPurchaseorderhead[] $trPurchaseorderheads
 * @property TrSalesorderhead[] $trSalesorderheads
 */
class MsLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['locationName', 'createdBy', 'createdDate'], 'required'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['locationCode', 'phone'], 'string', 'max' => 20],
            [['locationName', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
			[['locationName','address','flagActive'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'locationID' => 'Location ID',
            'locationCode' => 'Location Code',
            'locationName' => 'Location Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'flagActive' => 'Flag Active',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMsUsers()
    {
        return $this->hasMany(MsUser::className(), ['locationID' => 'locationID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['locationID' => 'locationID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarcodeNumbers()
    {
        return $this->hasMany(MsProductdetail::className(), ['barcodeNumber' => 'barcodeNumber'])->viaTable('stock', ['locationID' => 'locationID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrPurchaseorderheads()
    {
        return $this->hasMany(TrPurchaseorderhead::className(), ['locationID' => 'locationID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrSalesorderheads()
    {
        return $this->hasMany(TrSalesorderhead::className(), ['locationID' => 'locationID']);
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    public function search()
    {
        $query = self::find()
            ->andFilterWhere(['like', 'ms_location.locationName', $this->locationName])
			->andFilterWhere(['like', 'ms_location.address', $this->address])
            ->andFilterWhere(['=', 'ms_location.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['locationName' => SORT_ASC],
                'attributes' => ['locationName']
            ],
        ]);
		
		$dataProvider->sort->attributes['address'] = [
            'asc' => [self::tableName() . '.address' => SORT_ASC],
            'desc' => [self::tableName() . '.address' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
}
