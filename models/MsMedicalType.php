<?php

namespace app\models;


use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_medicaltype".
 *
 * @property integer $id
 * @property string $typeDescription
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsMedicalType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ms_medicaltype';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
            [['typeDescription', 'createdBy', 'editedBy'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'typeDescription' => 'Type Description',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
        ];
    }

    public function search() {
        $query = self::find()
                ->andFilterWhere(['LIKE', 'ms_medicaltype.typeDescription', $this->typeDescription]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
                'attributes' => ['id']
            ],
        ]);

        return $dataProvider;
    }

}
