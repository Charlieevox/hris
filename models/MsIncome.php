<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_income".
 *
 * @property integer $incomeID
 * @property string $incomeName
 * @property string $incomeAccount
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrCashIn[] $trCashIn
 */
class MsIncome extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_income';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['incomeName', 'incomeAccount', 'createdBy', 'createdDate'], 'required'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['incomeName', 'incomeAccount', 'createdBy', 'editedBy'], 'string', 'max' => 50],
			[['incomeName','incomeAccount','flagActive'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'incomeID' => 'Income ID',
            'incomeName' => 'Income Name',
            'incomeAccount' => 'Income Account',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrCashIn()
    {
        return $this->hasMany(TrCashIn::className(), ['incomeID' => 'incomeID']);
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
	 public function search()
    {
        $query = self::find()
            ->andFilterWhere(['like', 'ms_income.incomeName', $this->incomeName])
			->andFilterWhere(['=', 'ms_income.incomeAccount', $this->incomeAccount])
            ->andFilterWhere(['=', 'ms_income.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['incomeName' => SORT_ASC],
                'attributes' => ['incomeName']
            ],
        ]);
		
		$dataProvider->sort->attributes['incomeAccount'] = [
            'asc' => ['ms_income.incomeAccount' => SORT_ASC],
            'desc' => ['ms_income.incomeAccount' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
}
