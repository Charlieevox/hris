<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_pic".
 *
 * @property integer $picID
 * @property string $picName
 * @property integer $clientID
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsPic extends \yii\db\ActiveRecord
{
	public $clientIDs;
	public $clientNames;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
          return Yii::$app->user->identity->dbName.'.ms_pic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['picName', 'clientID','greetingID','email','cellPhone'], 'required'],
            [['clientID', 'greetingID', 'picID'], 'integer'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['picName', 'createdBy', 'editedBy', 'email'], 'string', 'max' => 50],
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
            'picID' => 'Pic ID',
            'picName' => 'Pic Name',
            'clientID' => 'Client Name',
            'greetingID' => 'greetingID',
            'email' => 'Email',
            'cellPhone' => 'Cell Phone',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }
	
	 public function getClient()
    {
        return $this->hasOne(MsClient::className(), ['clientID' => 'clientID']);
    }
    
        public function getGreets()
    {
        return $this->hasOne(LkGreeting::className(), ['greetingID' => 'greetingID']);
    }
    
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
	
	public function search()
    {
        $query = self::find()
	->joinWith('greets')
        ->joinWith('client')
        ->andFilterWhere(['like', 'ms_pic.picName', $this->picName])
	->andFilterWhere(['=', 'ms_pic.clientID', $this->clientID])
        ->andFilterWhere(['=', 'ms_pic.flagActive', $this->flagActive])
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
