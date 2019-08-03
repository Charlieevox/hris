<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_personneldepartment".
 *
 * @property string $departmentCode
 * @property string $departmentDesc
 * @property string $divisionId
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsPersonnelDepartment extends \yii\db\ActiveRecord
{
    public $divisiondesc;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_personneldepartment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['divisionId','departmentDesc'], 'required'],
            [['createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
            [['departmentCode', 'departmentDesc', 'divisionId', 'createdBy', 'editedBy'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'departmentCode' => 'Department Code',
            'departmentDesc' => 'Department Name',
            'divisionId' => 'Division',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',     
        ];
    }
    
    
     public function search() {
        $query = self::find()
                ->joinWith('division')
                ->andFilterWhere(['like', 'ms_personneldepartment.departmentCode', $this->departmentCode])
                ->andFilterWhere(['like', 'ms_personneldepartment.divisionId', $this->divisionId])
                ->andFilterWhere(['=', 'ms_personneldepartment.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['departmentDesc' => SORT_ASC],
                'attributes' => ['departmentDesc', 'departmentCode', 'divisionId', 'divisiondesc','shiftParm','prorateSetting']
            ],
        ]);

        $dataProvider->sort->attributes['departmentDesc'] = [
            'asc' => [self::tableName() . '.departmentDesc' => SORT_ASC],
            'desc' => [self::tableName() . '.departmentDesc' => SORT_DESC],
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
    
    public function getDivision()
    {
        return $this->hasOne(MsPersonnelDivision::className(), ['divisionId' => 'divisionId']);
    }

}
