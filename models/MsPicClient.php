<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_picclient".
 *
 * @property integer $picClientID
 * @property integer $clientID
 * @property integer $greetingID
 * @property string $picName
 * @property string $email
 * @property string $cellPhone
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsPicClient extends \yii\db\ActiveRecord
{
    
    public $clientIDs;
    public $clientNames;
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_picclient';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientID', 'greetingID', 'picName', 'email', 'cellPhone', 'createdBy', 'createdDate'], 'required'],
            [['clientID', 'greetingID'], 'integer'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['picName', 'email', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['cellPhone'], 'string', 'max' => 15],
             [['picName','clientID', 'clientIDs'], 'safe', 'on'=>'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'picClientID' => 'Pic Client ID',
            'clientID' => 'Client ID',
            'greetingID' => 'Greeting ID',
            'picName' => 'Pic Name',
            'email' => 'Email',
            'cellPhone' => 'Cell Phone',
            'flagActive' => 'Flag Active',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }
    
     public function getGreets()
    {
        return $this->hasOne(LkGreeting::className(), ['greetingID' => 'greetingID']);
    }
    
    	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    
	 public function getClient()
    {
        return $this->hasOne(MsClient::className(), ['clientID' => 'clientID']);
    }
    
    public function search()
    {
        $query = self::find()
	->joinWith('greets')
        ->joinWith('client')
        ->andFilterWhere(['like', 'ms_picclient.picName', $this->picName])
	->andFilterWhere(['=', 'ms_picclient.clientID', $this->clientID])
        ->andFilterWhere(['=', 'ms_picclient.flagActive', $this->flagActive])
        ->andFilterWhere(['like', 'ms_client.clientName', $this->clientNames])
        ->andFilterWhere(['=', 'ms_client.clientID', $this->clientIDs]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['picName' => SORT_ASC],
                'attributes' => ['picName']
            ],
        ]);
	
		$dataProvider->sort->attributes['clientID'] = [
            'asc' => ['ms_client.clientName' => SORT_ASC],
            'desc' => ['ms_client.clientName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
    
}
