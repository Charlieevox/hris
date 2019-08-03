<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "ms_coa".
 *
 * @property string $coaNo
 * @property integer $coaLevel
 * @property string $description
 * @property string $currency
 * @property integer $locationID
 * @property boolean $flagModule
 * @property boolean $flagActive
 * @property integer $ordinal
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsCoa extends \yii\db\ActiveRecord
{
    public $counters;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_coa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coaNo', 'description'], 'required'],
            [['coaLevel', 'locationID', 'ordinal'], 'integer'],
            [['flagModule', 'flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['coaNo'], 'string', 'max' => 25],
            [['description'], 'string', 'max' => 100],
            [['currency'], 'string', 'max' => 5],
            [['createdBy', 'editedBy'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coaNo' => 'Coa No',
            'coaLevel' => 'Coa Level',
            'description' => 'Description',
            'currency' => 'Currency',
            'locationID' => 'Location ID',
            'flagModule' => 'Flag Module',
            'flagActive' => 'Flag Active',
            'ordinal' => 'Ordinal',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edit By',
            'editedDate' => 'Edit Date',
        ];
    }
    
    public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    public function search()
    {
     
        $query = self::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $dataProvider;
    }
}
