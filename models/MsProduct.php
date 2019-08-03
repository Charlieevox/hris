<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_product".
 *
 * @property integer $productID
 * @property integer $categoryID
 * @property string $productName
 * @property string $minQty
 * @property string $notes
 * @property boolean $vat
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsCategory $Categories
 */
class MsProduct extends \yii\db\ActiveRecord
{
    public $joinProductDetail;
    public $price;
	public $flag;
    public $barcodeNumbers;
    public $uomIDs;
    public $qtys;
    public $standardFee;
    public $projecttypeName;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_product';
    }
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryID', 'productName', 'minQty', 'createdBy', 'createdDate'], 'required'],
             [['uomIDs', 'qtys', 'standardFee'], 'required'],
            [['categoryID'], 'integer'],
            [['minQty'], 'string'],
            [['vat', 'flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['productName', 'notes'], 'string', 'max' => 100],
            [['createdBy', 'editedBy'], 'string', 'max' => 50],
            [['barcodeNumbers'], 'string', 'max' => 50],
            [['productName','vat','minQty','flagActive'], 'safe', 'on'=>'search'],
			[['joinProductDetail'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'productID' => 'Product ID',
            'categoryID' => 'Revenue Category',
            'productName' => 'Service (Product) Name',
            'minQty' => 'Reorder Qty',
            'notes' => 'Notes',
            'vat' => 'VAT',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'barcodeNumbers' => 'Product Code',
            'uomIDs' => 'Unit',
            'qtys' => 'Qty',
            'standardFee' => 'Standard Fee',
            'projecttypeName' => 'Billing Duration',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories(){
        return $this->hasOne(MsCategory::className(), ['categoryID' => 'categoryID']);
    }
	
	public static function findActive(){
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    public function search()
    {
        $query = self::find()
                ->joinWith('categories')
                ->andFilterWhere(['like', 'ms_product.productName', $this->productName])
				->andFilterWhere(['like', 'ms_product.notes', $this->notes])
                ->andFilterWhere(['=', 'ms_product.categoryID', $this->categoryID])
                ->andFilterWhere(['=', 'ms_product.vat', $this->vat])
                ->andFilterWhere(['=', 'ms_product.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['productName' => SORT_ASC],
                'attributes' => ['productName']
            ],
        ]);
		
		$dataProvider->sort->attributes['categoryID'] = [
            'asc' => ['ms_category.categoryName' => SORT_ASC],
            'desc' => ['ms_category.categoryName' => SORT_DESC],
        ];
		
	$dataProvider->sort->attributes['minQty'] = [
            'asc' => [self::tableName() . '.minQty' => SORT_ASC],
            'desc' => [self::tableName() . '.minQty' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['vat'] = [
            'asc' => [self::tableName() . '.vat' => SORT_ASC],
            'desc' => [self::tableName() . '.vat' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['notes'] = [
            'asc' => [self::tableName() . '.notes' => SORT_ASC],
            'desc' => [self::tableName() . '.notes' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
	
	public function getProductDetails(){
        return $this->hasMany(MsProductDetail::className(), ['productID' => 'productID']);
    }
	
	public function afterFind(){
        parent::afterFind();
        $this->joinProductDetail = [];
        $i = 0;
        foreach ($this->getProductDetails()->all() as $joinProductDetail) {
            $this->joinProductDetail[$i]["barcodeNumber"] = $joinProductDetail->barcodeNumber;
            $this->joinProductDetail[$i]["uomID"] = $joinProductDetail->uomID;
            $this->joinProductDetail[$i]["uomName"] = $joinProductDetail->uom->uomName;
            $this->joinProductDetail[$i]["qty"] = $joinProductDetail->qty;
            $this->joinProductDetail[$i]["buyPrice"] = $joinProductDetail->buyPrice;
            $this->joinProductDetail[$i]["sellPrice"] = $joinProductDetail->sellPrice;
            $i += 1;
            //$this->price = $joinProductDetail->buyPrice;
        }
    }
}
