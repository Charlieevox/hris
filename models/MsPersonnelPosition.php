<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_personnelposition".
 *
 * @property integer $id
 * @property string $positionDescription
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsPersonnelPosition extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_personnelposition';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
			[['positionDescription'], 'unique'],
            [['positionDescription'], 'string', 'max' => 100],
            [['jobDescription'], 'string', 'max' => 1000],
            [['createdBy', 'editedBy'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'positionDescription' => 'Position Name',
            'jobDescription' => 'Job Description',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Status',
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_personnelposition.id', $this->id])
                ->andFilterWhere(['like', 'ms_personnelposition.positionDescription', $this->positionDescription])
                ->andFilterWhere(['=', 'ms_personnelposition.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
                'attributes' => ['id', 'positionDescription']
            ],
        ]);

        $dataProvider->sort->attributes['positionDescription'] = [
            'asc' => [self::tableName() . '.positionDescription' => SORT_ASC],
            'desc' => [self::tableName() . '.positionDescription' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }

}
