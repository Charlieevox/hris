<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_payrollprorate".
 *
 * @property integer $id
 * @property string $prorateDesc
 * @property string $type
 * @property string $day
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsPayrollProrate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_payrollprorate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prorateId'], 'required'],
            [['createdDate', 'editedDate','prorateId'], 'safe'],
            [['prorateId', 'type', 'day'], 'string', 'max' => 50],
            [['day'], 'integer', 'max' => 32],
            [['createdBy', 'editedBy'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prorateId' => 'Prorate Id',
            'type' => 'Type',
            'day' => 'Day',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }
    
        public function search() {
        $query = self::find()
                ->andFilterWhere(['=', 'ms_payrollprorate.type', $this->type])
                ->andFilterWhere(['like', 'ms_payrollprorate.prorateId', $this->prorateId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['prorateId' => SORT_ASC],
                'attributes' => ['prorateId']
            ],
        ]);

        $dataProvider->sort->attributes['prorateId'] = [
            'asc' => [self::tableName() . '.prorateId' => SORT_ASC],
            'desc' => [self::tableName() . '.prorateId' => SORT_DESC],
        ];

        return $dataProvider;
    }
    
}
