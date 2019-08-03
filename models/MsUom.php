<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_uom".
 *
 * @property integer $uomID
 * @property string $uomName
 * @property string $notes
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsProductdetail[] $productdetails
 */
class MsUom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_uom';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uomName'], 'required'],
			[['uomName','createdBy','editedBy'], 'string', 'max' => 50],
			[['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['notes'], 'string', 'max' => 100],
			[['uomName','notes','flagActive'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uomID' => 'UOM ID',
            'uomName' => 'Unit Name',
            'notes' => 'Notes',
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
    public function getMsProductDetails()
    {
        return $this->hasMany(MsProductDetail::className(), ['uomID' => 'uomID']);
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    public function search()
    {
        $query = self::find()
            ->andFilterWhere(['like', 'ms_uom.uomName', $this->uomName])
			->andFilterWhere(['like', 'ms_uom.notes', $this->notes])
            ->andFilterWhere(['=', 'ms_uom.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['uomName' => SORT_ASC],
                'attributes' => ['uomName']
            ],
        ]);
		$dataProvider->sort->attributes['notes'] = [
            'asc' => [self::tableName() . '.notes' => SORT_ASC],
            'desc' => [self::tableName() . '.notes' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
}
