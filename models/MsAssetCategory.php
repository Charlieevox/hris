<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "ms_assetcategory".
 *
 * @property integer $assetCategoryID
 * @property string $assetCategory
 * @property boolean $flagTax
 * @property string $assetCOA
 * @property string $depCOA
 * @property string $expCOA
 * @property integer $depLength
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsAssetCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.ms_assetcategory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assetCategory', 'assetCOA','abbreviation', 'depCOA', 'expCOA', 'depLength', 'createdBy', 'createdDate'], 'required'],
            [['flagTax', 'flagActive'], 'boolean'],
            [['assetCategory'], 'unique'],
            [['depLength'], 'integer'],
            [['createdDate', 'editedDate'], 'safe'],
            [['assetCategory', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['assetCOA', 'depCOA', 'expCOA'], 'string', 'max' => 20],
			[['abbreviation'], 'string', 'max' => 5],
			[['assetCategory', 'assetCOA', 'depCOA', 'expCOA','flagActive'], 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assetCategoryID' => 'Asset Category ID',
            'assetCategory' => 'Fixed Asset Category',
            'flagTax' => 'Flag Tax',
			'abbreviation' => 'Asset Code Initial',
            'assetCOA' => 'Asset Account',
            'depCOA' => 'Accumulated Depreciation Account',
            'expCOA' => 'Depreciation Expense Account',
            'depLength' => 'Depreciation Length (Month)',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }
	
	 public function getAssetCoa(){
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'assetCOA']);
    }
	
	 public function getDepCoa(){
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'depCOA'])
		->from(['coa1' => MsCoa::tableName()]);
    }
	
	 public function getExpCoa(){
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'expCOA'])
		->from(['coa2' => MsCoa::tableName()]);
    }
	
	public static function findActive(){
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    public function search()
    {
        $query = self::find()
                ->joinWith('assetCoa')
				->joinWith('depCoa')
				->joinWith('expCoa')
                ->andFilterWhere(['like', 'ms_assetcategory.assetCategory', $this->assetCategory])
                ->andFilterWhere(['=', 'ms_assetcategory.assetCOA', $this->assetCOA])
                ->andFilterWhere(['=', 'ms_assetcategory.depCOA', $this->depCOA])
                ->andFilterWhere(['=', 'ms_assetcategory.expCOA', $this->expCOA])
				->andFilterWhere(['=', 'ms_assetcategory.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['assetCategory' => SORT_ASC],
                'attributes' => ['assetCategory']
            ],
        ]);
		
		$dataProvider->sort->attributes['assetCOA'] = [
            'asc' => ['ms_coa.description' => SORT_ASC],
            'desc' => ['ms_coa.description' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['depCOA'] = [
		'asc' => ['ms_coa.description' => SORT_ASC],
		'desc' => ['ms_coa.description' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['expCOA'] = [
		'asc' => ['ms_coa.description' => SORT_ASC],
		'desc' => ['ms_coa.description' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
	
}
