<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_category".
 *
 * @property integer $categoryID
 * @property string $categoryName
 * @property string $notes
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsProduct[] $Products
 */
class MsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryName','coaNo','projecttypeID'], 'required'],
			[['categoryName','createdBy','editedBy'], 'string', 'max' => 50],
			[['coaNo'], 'string', 'max' => 20],
			[['flagActive'], 'boolean'],
            [['projecttypeID'], 'integer'],
            [['createdDate', 'editedDate'], 'safe'],
            [['notes'], 'string', 'max' => 100],
			[['categoryName','coaNo','projecttypeID','notes','flagActive'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categoryID' => 'Category ID',
            'categoryName' => 'Revenue Category',
            'notes' => 'Category Description',
            'flagActive' => 'Status',
            'coaNo' => 'Revenue Account',
            'projecttypeID' => 'Billing Duration',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(MsProduct::className(), ['categoryID' => 'categoryID']);
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
	 public function getCoaNos(){
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'coaNo']);
    }
    
    public function getProjecttype()
    {
        return $this->hasOne(LkProjecttype::className(), ['projecttypeID' => 'projecttypeID']);
    }
	
    public function search()
    {
        $query = self::find()
        ->joinWith('coaNos')
        ->joinWith('projecttype')
        ->andFilterWhere(['like', 'ms_category.categoryName', $this->categoryName])
        ->andFilterWhere(['like', 'ms_category.notes', $this->notes])
        ->andFilterWhere(['=', 'ms_category.coaNo', $this->coaNo])
        ->andFilterWhere(['=', 'ms_category.projecttypeID', $this->projecttypeID])
        ->andFilterWhere(['=', 'ms_category.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['categoryName' => SORT_ASC],
                'attributes' => ['categoryName']
            ],
        ]);
	$dataProvider->sort->attributes['notes'] = [
            'asc' => [self::tableName() . '.notes' => SORT_ASC],
            'desc' => [self::tableName() . '.notes' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
		
	$dataProvider->sort->attributes['coaNo'] = [
            'asc' => ['ms_coa.description' => SORT_ASC],
            'desc' => ['ms_coa.description' => SORT_DESC],
        ];
                
        $dataProvider->sort->attributes['projecttypeID'] = [
            'asc' => ['lk_projecttype.projecttypeName' => SORT_ASC],
            'desc' => ['lk_projecttype.projecttypeName' => SORT_DESC],
        ];
		
        return $dataProvider;
    }
}
