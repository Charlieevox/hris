<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_personnelbank".
 *
 * @property string $bankId
 * @property string $bankDesc
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsBank extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_bank';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bankId', 'bankDesc'], 'unique'],
            [['bankId'], 'required'],
            [['createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
            [['bankId', 'bankDesc', 'createdBy', 'editedBy'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bankId' => 'Bank Initial',
            'bankDesc' => 'Bank Name',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_bank.bankId', $this->bankId])
                ->andFilterWhere(['like', 'ms_bank.bankDesc', $this->bankDesc])
                ->andFilterWhere(['=', 'ms_bank.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['bankDesc' => SORT_ASC],
                'attributes' => ['bankDesc', 'bankId']
            ],
        ]);

        $dataProvider->sort->attributes['bankDesc'] = [
            'asc' => [self::tableName() . '.bankDesc' => SORT_ASC],
            'desc' => [self::tableName() . '.bankDesc' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }

    public static function findActive() {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }

}
