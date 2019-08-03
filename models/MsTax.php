<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_tax".
 *
 * @property integer $taxID
 * @property string $taxName
 * @property string $taxRate
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsTax extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_tax';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taxName', 'taxRate', 'coaNo'], 'required'],
            [['taxName','createdBy','editedBy'], 'string', 'max' => 50],
			[['coaNo'], 'string', 'max' => 20],
			[['taxRate'], 'string'],
			['taxRate', 'compare', 'compareValue' => '0,00', 'operator' => '>'],
			['taxRate','validateDates'], 
			[['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
			[['taxName','taxRate','coaNo','flagActive'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'taxID' => 'Tax ID',
            'taxName' => 'Tax Name',
            'taxRate' => 'Tax Rate',
			'flagActive' => 'Status',
			'coaNo' => 'Account',
        ];
    }
	
	 public function getCoaNos(){
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'coaNo']);
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    public function search()
    {
        $query = self::find()
			->joinwith('coaNos')
            ->andFilterWhere(['like', 'ms_tax.taxName', $this->taxName])
			->andFilterWhere(['=', 'ms_tax.taxRate', $this->taxRate])
			->andFilterWhere(['=', 'ms_tax.coaNo', $this->coaNo])
            ->andFilterWhere(['=', 'ms_tax.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['taxName' => SORT_ASC],
                'attributes' => ['taxName']
            ],
        ]);
		$dataProvider->sort->attributes['taxRate'] = [
            'asc' => [self::tableName() . '.taxRate' => SORT_ASC],
            'desc' => [self::tableName() . '.taxRate' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['coaNo'] = [
            'asc' => ['ms_coa.description' => SORT_ASC],
            'desc' => ['ms_coa.description' => SORT_DESC],
        ];
        return $dataProvider;
    }
	
	public function validateDates(){
		 $this->taxRate = str_replace(",",".",str_replace(".","",$this->taxRate));
		if($this->taxRate > 100){
			$this->addError('taxRate','Tax Rate Must be less than 100,00 ');
		}
	}
}
