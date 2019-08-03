<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_position".
 *
 * @property integer $ID
 * @property string $positionName
 * @property string $rate
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsPosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.ms_position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['positionName', 'rate','timeID'], 'required'],
            [['rate'], 'string'],
            [['timeID'], 'integer'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['positionName', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['positionName','rate','flagActive','timeID'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'Position ID',
            'positionName' => 'Position Name',
            'rate' => 'Rate',
            'timeID' => 'unit',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
      public function getTimes(){
        return $this->hasOne(LkTime::className(), ['timeID' => 'timeID']);
    }
    
    public function search()
    {
        $query = self::find()
                  ->joinWith('times')
            ->andFilterWhere(['like', 'ms_position.positionName', $this->positionName])
			->andFilterWhere(['=', 'ms_position.rate', $this->rate])
                ->andFilterWhere(['=', 'ms_position.timeID', $this->timeID])
            ->andFilterWhere(['=', 'ms_position.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['positionName' => SORT_ASC],
                'attributes' => ['positionName']
            ],
        ]);
        
        $dataProvider->sort->attributes['timeID'] = [
            'asc' => ['lk_time.unit' => SORT_ASC],
            'desc' => ['lk_time.unit' => SORT_DESC],
        ];
        
		$dataProvider->sort->attributes['rate'] = [
            'asc' => [self::tableName() . '.rate' => SORT_ASC],
            'desc' => [self::tableName() . '.rate' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
		
        return $dataProvider;
    }
}
