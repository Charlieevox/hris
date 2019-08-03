<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_expense".
 *
 * @property integer $expenseID
 * @property string $expenseName
 * @property string $expenseAccount
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrCashOut[] $trCashOut
 */
class MsExpense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_expense';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expenseName', 'expenseAccount', 'createdBy', 'createdDate'], 'required'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['expenseName', 'expenseAccount', 'createdBy', 'editedBy'], 'string', 'max' => 50],
			[['expenseName','expenseAccount','flagActive'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expenseID' => 'Expense ID',
            'expenseName' => 'Expense Name',
            'expenseAccount' => 'Expense Account',
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
    public function getTrCashOut()
    {
        return $this->hasMany(TrCashOut::className(), ['expenseID' => 'expenseID']);
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
	 public function search()
    {
        $query = self::find()
            ->andFilterWhere(['like', 'ms_expense.expenseName', $this->expenseName])
			->andFilterWhere(['=', 'ms_expense.expenseAccount', $this->expenseAccount])
            ->andFilterWhere(['=', 'ms_expense.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['expenseName' => SORT_ASC],
                'attributes' => ['expenseName']
            ],
        ]);
		
		$dataProvider->sort->attributes['expenseAccount'] = [
            'asc' => ['ms_expense.expenseAccount' => SORT_ASC],
            'desc' => ['ms_expense.expenseAccount' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
}
