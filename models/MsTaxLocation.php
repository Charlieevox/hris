<?php

namespace app\models;

    
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_taxlocation".
 *
 * @property string $id
 * @property string $npwpNo
 * @property string $officeName
 * @property string $address
 * @property string $city
 * @property string $phone1
 * @property string $phone2
 * @property string $taxSigner_1
 * @property string $position_1
 * @property string $npwpSigner_1
 * @property string $phone1_1
 * @property string $email_1
 * @property string $taxSigner_2
 * @property string $position_2
 * @property string $npwpSigner_2
 * @property string $phone1_2
 * @property string $phone2_2
 * @property string $email_2
 * @property string $taxSigner_3
 * @property string $position_3
 * @property string $npwpSigner_3
 * @property string $phone1_3
 * @property string $phone2_3
 * @property string $email_3
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 * @property boolean $flagActive
 */
class MsTaxLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_taxlocation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['createdDate', 'editedDate'], 'safe'],
            [['flagActive'], 'boolean'],
			[['address'], 'string', 'max' => 200],
            [['id', 'npwpNo', 'officeName', 'city', 'phone1', 'phone2', 'taxSigner_1', 'position_1', 'npwpSigner_1', 'phone1_1', 'email_1', 'taxSigner_2', 'position_2', 'npwpSigner_2', 'phone1_2', 'phone2_2', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['email_2', 'taxSigner_3', 'position_3', 'npwpSigner_3', 'phone1_3', 'phone2_3', 'email_3','zipCode','phone2_1'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Branch Initial',
            'npwpNo' => 'NPWP No',
            'officeName' => 'Tax Office',
            'address' => 'Address',
            'city' => 'City',
            'phone1' => 'Phone1',
            'phone2' => 'Phone2',
            'taxSigner_1' => 'Full Name',
            'position_1' => 'Position',
            'npwpSigner_1' => 'NPWP Signer 1',
            'phone1_1' => 'Phone 1',
			'phone2_1' => 'Phone 2',
            'email_1' => 'Email',
            'taxSigner_2' => 'Full Name',
            'position_2' => 'Position',
            'npwpSigner_2' => 'NPWP Signer 2',
            'phone1_2' => 'Phone 1',
            'phone2_2' => 'Phone 2',
            'email_2' => 'Email',
            'taxSigner_3' => 'Full Name',
            'position_3' => 'Position',
            'npwpSigner_3' => 'NPWP Signer 3',
            'phone1_3' => 'Phone 1',
            'phone2_3' => 'Phone 2',
            'email_3' => 'Email',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
			'zipCode' => 'Zip Code',
        ];
    }
	
	
	public function search() {
        $query = self::find()
                ->andFilterWhere(['like', 'ms_taxlocation.id', $this->id])
                ->andFilterWhere(['like', 'ms_taxlocation.officeName', $this->officeName])
                ->andFilterWhere(['like', 'ms_taxlocation.npwpNo', $this->npwpNo])
                ->andFilterWhere(['like', 'ms_taxlocation.taxSigner_1', $this->taxSigner_1])
                ->andFilterWhere(['=', 'ms_taxlocation.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['officeName' => SORT_ASC],
                'attributes' => ['id','officeName','npwpNo','taxSigner_1']
            ],
        ]);

        $dataProvider->sort->attributes['officeName'] = [
            'asc' => [self::tableName() . '.officeName' => SORT_ASC],
            'desc' => [self::tableName() . '.officeName' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
	
	
}
