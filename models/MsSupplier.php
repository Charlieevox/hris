<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_supplier".
 *
 * @property integer $supplierID
 * @property string $supplierName
 * @property string $pic
 * @property integer $dueDate
 * @property string $address
 * @property string $phone1
 * @property string $phone2
 * @property string $fax
 * @property string $mobile
 * @property string $email
 * @property string $npwp
 * @property string $notes
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class MsSupplier extends \yii\db\ActiveRecord
{
    public $joinMsPicSupplier;
    public $flag;
    public $picNames;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplierName', 'dueDate', 'addressLine1', 'phone1', 'createdBy', 'createdDate'], 'required'],
            [['dueDate'], 'integer'],
			[['email'], 'email'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['supplierName', 'email', 'createdBy', 'editedBy'], 'string', 'max' => 50],
			[['npwp'], 'string', 'max' => 25],
            [['addressLine1', 'addressLine2', 'notes'], 'string', 'max' => 200],
            [['phone1', 'phone2', 'fax', 'mobile'], 'string', 'max' => 20],
			[['supplierName','addressLine1','phone1','mobile','picNames'], 'safe', 'on'=>'search'],
            [['joinMsPicSupplier'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'supplierID' => 'Supplier ID',
            'supplierName' => 'Vendor Name',
            'dueDate' => 'Due Date (Days)',
            'addressLine1' => 'Address Line 1',
            'addressLine2' => 'Address Line 2',
            'phone1' => 'Phone 1',
            'phone2' => 'Phone 2',
            'fax' => 'Fax',
            'mobile' => 'Mobile',
            'email' => 'E-Mail',
            'npwp' => 'NPWP',
            'notes' => 'Notes',
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
    
      public function getPicSupplier()
    {
        return $this->hasOne(MsPicSupplier::className(), ['supplierID' => 'supplierID']);
    }
	
	public function search()
    {
        $query = self::find()
            ->joinWith('picSupplier')
            ->andFilterWhere(['like', 'ms_supplier.supplierName', $this->supplierName])
            ->andFilterWhere(['like', 'ms_supplier.addressLine1', $this->addressLine1])
            ->andFilterWhere(['like', 'ms_supplier.phone1', $this->phone1])
            ->andFilterWhere(['like', 'ms_picsupplier.picName', $this->picNames])
            ->andFilterWhere(['=', 'ms_supplier.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['supplierName' => SORT_ASC],
                'attributes' => ['supplierName']
            ],
        ]);

		$dataProvider->sort->attributes['addressLine1'] = [
            'asc' => [self::tableName() . '.addressLine1' => SORT_ASC],
            'desc' => [self::tableName() . '.addressLine1' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['phone1'] = [
            'asc' => [self::tableName() . '.phone1' => SORT_ASC],
            'desc' => [self::tableName() . '.phone1' => SORT_DESC],
        ];
                
                	
        $dataProvider->sort->attributes['picNames'] = [
            'asc' => ['picName' => SORT_ASC],
            'desc' => ['picName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
    
     public function afterFind(){
        parent::afterFind();
        $this->joinMsPicSupplier = [];
        $i = 0;
        foreach ($this->getPicSupplier()->all() as $joinMsPicSupplier) {
            $this->joinMsPicSupplier[$i]["picSupplierID"] = $joinMsPicSupplier->picSupplierID;
            $this->joinMsPicSupplier[$i]["greetingID"] = $joinMsPicSupplier->greetingID;
            $this->joinMsPicSupplier[$i]["greetingName"] = $joinMsPicSupplier->greets->greetingName;
            $this->joinMsPicSupplier[$i]["picName"] = $joinMsPicSupplier->picName;
            $this->joinMsPicSupplier[$i]["email"] = $joinMsPicSupplier->email;
            $this->joinMsPicSupplier[$i]["cellPhone"] = $joinMsPicSupplier->cellPhone;
            $i += 1;
        }
    }
}
