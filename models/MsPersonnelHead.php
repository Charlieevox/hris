<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use app\components\AppHelper;
use Yii;

class MsPersonnelHead extends \yii\db\ActiveRecord {

    public $joinPersonnelfamily;
    public $joinPersonnelContract;
	public $joinPersonnelPosition;
	public $joinPersonnelStatus;
    public $bankDetail;
    public $imageGalleryKTP;
    public $imageGalleryNPWP;
    public $imageGalleryPhoto;
	
    public $imageGalleryKTPMode;	
    public $imageGalleryNPWPMode;	
    public $imageGalleryPhotoMode;
	public $position;

    public static function tableName() {
        return 'ms_personnelhead';
    }

    /**
     * @inheritdoc
	 
	 */
    public function rules() {
        return [
            //[['firstName','gender','divisionId','departmentId','bankName','dependent','npwpNo'], 'required'],
            [['npwpNo'],'string','length' => [20,20]],
			[['birthDate', 'createdDate', 'editedDate', 'phoneNo','joinPersonnelStatus','joinPersonnelPosition' ,'joinPersonnelfamily', 'joinPersonnelContract', 'taxId','locationID','imageKTP','imageNPWP','imagePhoto','positionID'], 'safe'],
            [['flagActive'], 'boolean'],
            [['major','employeeNo','shiftCode','nationality','swiftCode', 'country', 'firstName', 'lastName', 'fullName', 'birthPlace', 'city', 'branch'], 'string', 'max' => 50],
            [['maritalStatus','prorateSetting','taxSetting','overtimeId'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 60],
            [['curency'], 'string', 'max' => 8],
            [['email'], 'email'],
            [['dependent'], 'number','max' => 3],
            [['gender'], 'string', 'max' => 15],
            [['npwpAddress', 'address'], 'string', 'max' => 300],
            [['npwpName', 'education', 'divisionId', 'departmentId', 'createdBy', 'editedBy', 'ecFirstName', 'ecLastName', 'ecRelationShip', 'ecPhone1', 'ecPhone2'], 'string', 'max' => 45],
            [['empStatus', 'jamsostekParm','paymentMethod'], 'string', 'max' => 30],
            [['idNo','npwpNo', 'bpjskNo', 'bpkstkNo', 'bankName', 'bankNo'], 'string', 'max' => 25],
            [['imageGalleryPhoto'], 'file', 'extensions'=>'jpg, gif, png'],
			[['imageGalleryKTP','imageGalleryNPWP'], 'file', 'extensions'=>'jpg, gif, png, pdf'],
			[['imageGalleryKTPMode','imageGalleryNPWPMode','imageGalleryPhotoMode','notes'], 'safe'],
			[['fullName', 'position', 'divisionId', 'email', 'departmentId', 'phoneNo','positionID'], 'safe','on'=>'search'],
			
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'employeeNo' => 'Employee No',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'fullName' => 'Full Name',
            'birthPlace' => 'Birth Place',
            'birthDate' => 'Birth Date',
            'address' => 'Domicile Address',
            'city' => 'City',
            'phoneNo' => 'Phone No',
            'email' => 'Email',
            'gender' => 'Gender',
            'education' => 'Education',
			'major' => 'Major',
            'maritalStatus' => 'Marital Status',
            'dependent' => 'Tax Dependents',
            'empStatus' => 'Status',
            'jamsostekParm' => 'Jamsostek Type',
            'divisionId' => 'Division',
            'departmentId' => 'Department',
            'npwpNo' => 'NPWP No',
            'bpjskNo' => 'BPJS Kesehatan No',
            'bpkstkNo' => 'BPJS Tenaga Kerja No',
            'bankName' => 'Bank Initial',
            'branch' => 'Branch Name',
            'bankNo' => 'Account No',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'flagActive' => 'Flag Active',
            'curency' => 'Curency',
            'bankDetail' => 'Bank Name',
            'ecFirstName' => 'First Name',
            'ecLastName' => 'Last Name',
            'ecRelationShip' => 'Relationship',
            'ecPhone1' => 'Phone 1',
            'ecPhone2' => 'Phone 2',
            'npwpName' => 'NPWP Name',
            'npwpAddress' => 'NPWP Address',
            'nationality' => 'Nationality',
            'country' => 'Country',
            'taxId' => 'Company Tax Location',
            'imageKTP' => '',
            'imageNPWP' => '',
            'imagePhoto' => '',
            'imageGalleryKTP'=> '',
            'imageGalleryNPWP'=> '',
            'imageGalleryPhoto'=> '',
			'paymentMethod' => 'By',
            'swiftCode' => 'Swift Code',
            'shiftCode' => 'Shift Code',
            'idNo' => 'Identity Number',
            'locationID' => 'Location',
            'positionID' => 'Position'
        ];
    }

    public function search() {
        $query = self::find()
                ->joinWith('division')
				->joinWith('positiondesc')
                ->joinWith('department')
                ->andFilterWhere(['like', 'ms_personnelhead.fullName', $this->fullName])
                ->andFilterWhere(['like', 'ms_personnelhead.employeeNo', $this->employeeNo])
                ->andFilterWhere(['like', 'ms_personnelposition.positionDescription', $this->position])
                ->andFilterWhere(['like', 'ms_personnelhead.email', $this->email])
                ->andFilterWhere(['like', 'ms_personnelhead.phoneNo', $this->phoneNo])
                ->andFilterWhere(['like', 'ms_personneldepartment.departmentDesc', $this->departmentId])
                ->andFilterWhere(['like', 'ms_personnelDivision.description', $this->divisionId])
                ->andFilterWhere(['=', 'ms_personnelhead.flagActive', $this->flagActive]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['fullName' => SORT_ASC],
                'attributes' => ['fullName', 'employeeNo' ,'position', 'divisionId', 'email', 'departmentId', 'phoneNo']
            ],
        ]);

        $dataProvider->sort->attributes['fullName'] = [
            'asc' => [self::tableName() . '.fullName' => SORT_ASC],
            'desc' => [self::tableName() . '.fullName' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }

    public function getDivision() {
        return $this->hasOne(MsPersonnelDivision::className(), ['divisionId' => 'divisionId']);
    }

    public function getDepartment() {
        return $this->hasOne(MsPersonnelDepartment::className(), ['departmentCode' => 'departmentId']);
    }

    // public function getPositiondesc() {
        // return $this->hasOne(MsPersonnelPosition::className(), ['id' => 'position']);
    // }

    public function getMaritalstatusdesc() {
        return $this->hasOne(MsSetting::className(), ['value1' => 'maritalStatus'])->where('key1="maritalStatus"');
    }

    public function getGenderdesc() {
        return $this->hasOne(LkGender::className(), ['id' => 'gender']);
    }

    public function getFamilydetail() {
        return $this->hasMany(MsPersonnelFamily::className(), ['id' => 'id']);
    }

    public function getContractdetail() {
        return $this->hasMany(MsPersonnelContract::className(), ['nik' => 'id']);
    }

    public function getBankdesc() {
        return $this->hasOne(MsBank::className(), ['bankId' => 'bankName']);
    }
	
	public function getEmpStatus() {
		return $this->hasOne(MsSetting::className(), ['value1' => 'empStatus'])->where('key1="Status"');
    }

    public function getPositiondesc() {
		return $this->hasOne(MsPersonnelPosition::className(), ['id' => 'positionID']);
    }
	
	// public function getPositiondesc()
    // {
    //     return $this->hasOne(MsPersonnelPosition::className(), ['id' => 'position'])
	// 				->viaTable('ms_personnelcontract', ['nik' => 'id'], function ($query) {

	// 			//$query->Andwhere('now() between ms_personnelcontract.startdate and ms_personnelcontract.enddate');
	// 			//$query->groupBy('ms_personnelcontract.nik');
    //     });
    // }

    public function afterFind() {
        parent::afterFind();
        $this->birthDate = AppHelper::convertDateTimeFormat($this->birthDate, 'Y-m-d', 'd-m-Y');
        $this->joinPersonnelfamily = [];
        $this->joinPersonnelContract = [];
		$this->joinPersonnelPosition = [];
		$this->joinPersonnelStatus = [];
		$this->viewData();
		
		$connection = Yii::$app->db;
		$sqlStatus = "select value1,key2 from ms_setting where key1= 'Status'";
		$temp = $connection->createCommand($sqlStatus);
		$statusResult = $temp->queryAll();
		
		$sqlPosition = "select id,PositionDescription from ms_personnelposition where flagActive= '1' order by PositionDescription";
		$temp2 = $connection->createCommand($sqlPosition);
		$positionResult = $temp2->queryAll();

        $i = 0;
        foreach ($this->getFamilydetail()->all() as $joinPersonnelFamilyDetail) {
            $this->joinPersonnelfamily[$i]["firstName"] = $joinPersonnelFamilyDetail->firstName;
            $this->joinPersonnelfamily[$i]["lastName"] = $joinPersonnelFamilyDetail->lastName;
            $this->joinPersonnelfamily[$i]["relationship"] = $joinPersonnelFamilyDetail->relationship;
            $this->joinPersonnelfamily[$i]["idNumber"] = $joinPersonnelFamilyDetail->idNumber;
            $this->joinPersonnelfamily[$i]["birthPlace"] = $joinPersonnelFamilyDetail->birthPlace;
            $this->joinPersonnelfamily[$i]["birthDate"] = AppHelper::convertDateTimeFormat($joinPersonnelFamilyDetail->birthDate, 'Y-m-d', 'd-m-Y');
            $i += 1;
        }

        $j = 0;
        foreach ($this->getContractdetail()->all() as $joinPersonnelContractDetail) {

			$this->joinPersonnelContract[$j]["startWorking"] = AppHelper::convertDateTimeFormat($joinPersonnelContractDetail->startWorking, 'Y-m-d', 'd-m-Y');
            $this->joinPersonnelContract[$j]["startDate"] = AppHelper::convertDateTimeFormat($joinPersonnelContractDetail->startDate, 'Y-m-d', 'd-m-Y');
            $this->joinPersonnelContract[$j]["endDate"] = AppHelper::convertDateTimeFormat($joinPersonnelContractDetail->endDate, 'Y-m-d', 'd-m-Y');
            $this->joinPersonnelContract[$j]["docNo"] = $joinPersonnelContractDetail->docNo;
            $j += 1;
        }
        
		$k=0;
		foreach ($positionResult as $joinPersonnelPosition) {
            $this->joinPersonnelPosition[$k]["id"] = $joinPersonnelPosition['id'];
            $this->joinPersonnelPosition[$k]["text"] = $joinPersonnelPosition['PositionDescription'];
            $k += 1;
        }
				
		$l=0;
		foreach ($statusResult as $joinPersonnelStatus) {

            $this->joinPersonnelStatus[$l]["id"] = $joinPersonnelStatus['value1'];
            $this->joinPersonnelStatus[$l]["text"] = $joinPersonnelStatus['key2'];
            $l += 1;
        }
    }
	
	public function viewData(){
		$connection = Yii::$app->db;
		
		$sql = "
			select nik,a.position,b.positiondescription from ms_personnelcontract a
			JOIN ms_personnelposition b on a.position = b.id
			where now() between a.startdate and a.enddate
			group by nik,a.position,b.positiondescription";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		
		
		foreach ($headResult as $detailMenu) {
			$this->position = $detailMenu['positiondescription'];
		
		}				        
	}		
}
