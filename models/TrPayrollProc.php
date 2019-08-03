<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_payrollproc".
 *
 * @property string $period
 * @property string $status
 */
class TrPayrollProc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_payrollproc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['period'], 'unique'],
            [['period'], 'required'],
            [['period', 'status'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'period' => 'Period',
            'status' => 'Status',
        ];
    }
    
        public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'tr_payrollproc.period', $this->period])
                ->andFilterWhere(['like', 'tr_payrollproc.status', $this->status]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['period' => SORT_ASC],
                'attributes' => ['period', 'status']
            ],
        ]);

        $dataProvider->sort->attributes['period'] = [
            'asc' => [self::tableName() . '.period' => SORT_ASC],
            'desc' => [self::tableName() . '.period' => SORT_DESC],
        ];

        return $dataProvider;
    }
    
}
