<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_personneldivision".
 *
 * @property integer $id
 * @property string $description
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsPersonnelDivision extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_personneldivision';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['divisionId'], 'unique'],
            [['createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
		    [['divisionId'], 'integer'],
            [['description', 'createdBy', 'editedBy'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'divisionId' => 'Division Initial',
            'description' => 'Division',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Status',
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_personneldivision.divisionId', $this->divisionId])
                ->andFilterWhere(['like', 'ms_personneldivision.description', $this->description])
                ->andFilterWhere(['=', 'ms_personneldivision.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['description' => SORT_ASC],
                'attributes' => ['description','divisionId']
            ],
        ]);

        $dataProvider->sort->attributes['description'] = [
            'asc' => [self::tableName() . '.description' => SORT_ASC],
            'desc' => [self::tableName() . '.description' => SORT_DESC],
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
