<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "ms_transnumber".
 *
 * @property integer $transNumberID
 * @property string $transType
 * @property string $transAbbreviation
 */
class MsTransNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_transnumber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transType', 'transAbbreviation'], 'required'],
            [['transType'], 'string', 'max' => 50],
            [['transAbbreviation'], 'string', 'max' => 3],
        	[['transType','transAbbreviation'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transNumberID' => 'Trans Number ID',
            'transType' => 'Transaction Type',
            'transAbbreviation' => 'Transaction Abbreviation',
        ];
    }
    
    public function search()
    {
    	$query = self::find()
    	->andFilterWhere(['like', 'ms_transnumber.transType', $this->transType])
    	->andFilterWhere(['like', 'ms_transnumber.transAbbreviation', $this->transAbbreviation]);
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['transType' => SORT_ASC],
    					'attributes' => ['transType']
    			],
    	]);
    	
    	$dataProvider->sort->attributes['transAbbreviation'] = [
    			'asc' => [self::tableName() . '.transAbbreviation' => SORT_ASC],
    			'desc' => [self::tableName() . '.transAbbreviation' => SORT_DESC],
    	];
    	
    	return $dataProvider;
    }
}
