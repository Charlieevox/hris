<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_client".
 *
 * @property integer $clientID
 * @property string $clientName
 * @property integer $picGreet
 * @property string $picName
 * @property integer $dueDate
 * @property string $addresLine1
 * @property string $addresLine2
 * @property string $city
 * @property string $state
 * @property integer $zipCode
 * @property string $country
 * @property string $phone1
 * @property string $phone2
 * @property string $fax
 * @property string $mobile
 * @property string $email1
 * @property string $email2
 * @property string $email3
 * @property string $npwp
 * @property string $notes
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property LkGreeting $picGreet0
 * @property TrSalesorderhead[] $trSalesorderheads
 */
class MsClient extends \yii\db\ActiveRecord
{
    public $address;
    public $joinMsPicClient;
    public $flag;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientName', 'dueDate', 'addresLine1', 'city', 'state', 'zipCode', 'country'], 'required'],
	    [['phone1', 'flagActive', 'createdBy', 'createdDate'], 'required'],
            [['email1','email2','email3'], 'email'],
            [['dueDate', 'zipCode'], 'integer'],
            [['flagActive','vatSubject'], 'boolean'],
            [['createdDate', 'editedDate', 'createdBy', 'createdDate'], 'safe'],
            [['clientName', 'country'], 'string', 'max' => 45],
            [['addresLine1', 'addresLine2'], 'string', 'max' => 200],
            [['city', 'state', 'email1', 'email2', 'email3', 'npwp', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['phone1', 'phone2', 'fax', 'mobile'], 'string', 'max' => 20],
            [['notes'], 'string', 'max' => 100],
            [['clientName', 'address', 'phone1', 'mobile', 'notes', 'email1'], 'safe', 'on'=>'search'],
            [['joinMsPicClient'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clientID' => 'Client ID',
            'clientName' => 'Client Name',
            'dueDate' => 'Payment Due (Days)',
            'addresLine1' => 'Address Line 1',
            'addresLine2' => 'Address Line 2',
            'city' => 'City',
            'state' => 'State / Province',
            'zipCode' => 'Zip Code',
            'country' => 'Country',
            'phone1' => 'Office Phone 1',
            'phone2' => 'Office Phone 2',
            'fax' => 'Office Fax',
            'mobile' => 'Mobile',
            'email1' => 'E-mail 1',
            'email2' => 'E-mail 2',
            'email3' => 'E-mail 3',
            'npwp' => 'NPWP',
            'notes' => 'Notes',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
             'vatSubject' => 'VAT Subject',
        ];
    }
    
    
    /**
     * @return \yii\db\ActiveQuery

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrSalesorderheads()
    {
        return $this->hasMany(TrSalesorderhead::className(), ['clientID' => 'clientID']);
    }
    
    public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
     public function getPicClient()
    {
        return $this->hasMany(MsPicClient::className(), ['clientID' => 'clientID']);
    }
    
    public function search()
    {
        $query = self::find()
            ->addSelect(["clientID, clientName, phone1, email1 , CONCAT(addresLine1, ' ', city, ' ', state, ' ', zipCode, ' ', country) AS address, flagActive"])    
            ->andFilterWhere(['like', 'ms_client.clientName', $this->clientName])
            //->andFilterWhere(['like', 'ms_client.addressLine1', $this->addresLine1])
            ->andFilterWhere(['like', 'ms_client.phone1', $this->phone1])
            ->andFilterWhere(['like', 'ms_client.email1', $this->email1])
            ->andFilterWhere(['=', 'ms_client.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['clientName' => SORT_ASC],
                'attributes' => ['clientName']
            ],
        ]);
	
	

	$dataProvider->sort->attributes[$this->address] = [
            'asc' => ['address' => SORT_ASC],
            'desc' => [ 'address' => SORT_DESC],
        ];
		
	$dataProvider->sort->attributes['phone1'] = [
            'asc' => [self::tableName() . '.phone1' => SORT_ASC],
            'desc' => [self::tableName() . '.phone1' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['email1'] = [
            'asc' => [self::tableName() . '.email1' => SORT_ASC],
            'desc' => [self::tableName() . '.email1' => SORT_DESC],
        ];
		
	$dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
    
    
    public function afterFind(){
        parent::afterFind();
        $this->joinMsPicClient = [];
        $i = 0;
        foreach ($this->getPicClient()->all() as $joinMsPicClient) {
            $this->joinMsPicClient[$i]["picClientID"] = $joinMsPicClient->picClientID;
            $this->joinMsPicClient[$i]["greetingID"] = $joinMsPicClient->greetingID;
            $this->joinMsPicClient[$i]["greetingName"] = $joinMsPicClient->greets->greetingName;
            $this->joinMsPicClient[$i]["picName"] = $joinMsPicClient->picName;
            $this->joinMsPicClient[$i]["email"] = $joinMsPicClient->email;
            $this->joinMsPicClient[$i]["cellPhone"] = $joinMsPicClient->cellPhone;
            $i += 1;
        }
    }
}
