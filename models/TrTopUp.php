<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "tr_topup".
 *
 * @property integer $topupID
 * @property integer $companyID
 * @property integer $bankID
 * @property string $totalTopup
 * @property string $confirmationDate
 * @property integer $methodID
 * @property string $bankAccount
 * @property string $bankName
 * @property string $accountName
 * @property string $totalPayment
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class TrTopUp extends \yii\db\ActiveRecord
{
	public $confirmationPhotos;
	public $joinConfirmationTopUp;
        public $companyNames;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_topup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companyID', 'bankID', 'totalTopup', 'topupDate', 'createdBy', 'topupName', 'createdDate','status'], 'required', 'on'=>'create'],
			[['totalPayment'], 'required', 'on'=>'confirmation'],
            [['companyID', 'bankID'], 'integer'],
			[['totalPayment','totalTopup'], 'string'],
			[['status'], 'boolean'],
			['totalPayment', 'compare', 'compareValue' => '0,00', 'operator' => '>'],
            [['createdDate', 'editedDate', 'topupDate'], 'safe'],
			[['additionalInfo'], 'string', 'max' => 200],
            [['createdBy', 'editedBy'], 'string', 'max' => 50],
			[['confirmationPhotos'], 'file', 'extensions' => 'png, jpg', 'maxFiles' => 3],
			[['topupDate','companyID','bankID','totalTopup','status'], 'safe', 'on'=>'search'],
			[['joinConfirmationTopUp'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'topupID' => 'Topup ID',
            'companyID' => 'Company Name',
            'topupDate' => 'Top Up Date',
            'bankID' => 'Bank Name',
            'totalTopup' => 'Total Top Up',
            'totalPayment' => 'Total Payment',
            'createdBy' => 'Created By',
            'topupName' => 'Top Up Name',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'additionalInfo' => 'Additional Information',
            'confirmationPhotos' => 'Attachment',
            'status' => 'status',
            'companyNames' => 'Company Name',
        ];
    }
	
	public function getBanks()
    {
        return $this->hasOne(LkBank::className(), ['bankID' => 'bankID']);
    }
	
	public function getCompanies()
    {
        return $this->hasOne(MsCompany::className(), ['companyID' => 'companyID']);
    }
	
	public function getConfirmationTopUps()
    {
        return $this->hasOne(TrConfirmationTopUp::className(), ['topupID' => 'topupID']);
    }
	
	 // public function getUploadConfirmationDirectory($isBasePath = true)
    // {
        // if ($isBasePath) {
            // return Yii::$app->basePath . '/assets_b/uploads/' . Yii::$app->user->identity->company->companyName . '/confirmation-photos/' . $this->topupID . "/";
        // } else {
            // return Yii::$app->urlManager->baseUrl . '/assets_b/uploads/' . Yii::$app->user->identity->company->companyName . '/confirmation-photos/' . $this->topupID . "/";
        // }
    // }
	
		public function search()
    {
        $query = self::find()
			->joinWith('banks')
			->joinWith('companies')
			->andFilterWhere(['=', "DATE_FORMAT(tr_topup.topupDate, '%d-%m-%Y')", $this->topupDate])
			->andFilterWhere(['=', 'tr_topup.companyID', $this->companyID])
            ->andFilterWhere(['=', 'tr_topup.bankID', $this->bankID])
			->andFilterWhere(['=', 'tr_topup.totalTopup', $this->totalTopup])
			->andFilterWhere(['=', 'tr_topup.status', $this->status]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['topupDate' => SORT_DESC],
                'attributes' => ['topupDate']
            ],
        ]);
		
		$dataProvider->sort->attributes['companyID'] = [
            'asc' => ['ms_company.companyName' => SORT_ASC],
            'desc' => ['ms_company.companyName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['bankID'] = [
            'asc' => ['lk_bank.bankName' => SORT_ASC],
            'desc' => ['lk_bank.bankName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['totalTopup'] = [
				'asc' => [self::tableName() . '.totalTopup' => SORT_ASC],
				'desc' => [self::tableName() . '.totalTopup' => SORT_DESC],
		];
		
		$dataProvider->sort->attributes['status'] = [
				'asc' => [self::tableName() . '.status' => SORT_ASC],
				'desc' => [self::tableName() . '.status' => SORT_DESC],
		];
		
		
        return $dataProvider;
    }
	
	public function afterFind(){
        parent::afterFind();
		$this->topupDate = AppHelper::convertDateTimeFormat($this->topupDate, 'Y-m-d H:i:s', 'd-m-Y');
		
		$this->joinConfirmationTopUp = [];
    	$i = 0;
    	foreach ($this->getConfirmationTopUps()->all() as $joinConfirmationTopUp) {
    		$this->joinConfirmationTopUp[$i]["confirmationDate"] = $joinConfirmationTopUp->confirmationDate = AppHelper::convertDateTimeFormat($joinConfirmationTopUp->confirmationDate, 'Y-m-d H:i:s', 'd-m-Y');
			$this->joinConfirmationTopUp[$i]["methodID"] = $joinConfirmationTopUp->methodID;
			$this->joinConfirmationTopUp[$i]["methodName"] = $joinConfirmationTopUp->methods->methodName;
    		$this->joinConfirmationTopUp[$i]["bankAccount"] = $joinConfirmationTopUp->bankAccount;
    		$this->joinConfirmationTopUp[$i]["bankName"] = $joinConfirmationTopUp->bankName;
    		$this->joinConfirmationTopUp[$i]["accountName"] = $joinConfirmationTopUp->accountName;
    		$this->joinConfirmationTopUp[$i]["subTotal"] = $joinConfirmationTopUp->subTotal;
    		$i += 1;
    	}
		
	}
	
	
	
	
	
		 // public function afterSave($insert, $changedAttributes)
    // {  
			// parent::afterSave($insert, $changedAttributes);
                // FileHelper::createDirectory($this->getUploadConfirmationDirectory());
                // foreach ($this->confirmationPhotos as $photo) {
                     // $id = uniqid();
                     // $result = $photo->saveAs($this->getUploadConfirmationDirectory() . $this->topupID . '-' . $id . '.' . $photo->extension);
                // }
        	
	// }
	
    
			
	    // public function afterDelete()
    // {
        // parent::afterDelete();
        // FileHelper::removeDirectory($this->getUploadConfirmationDirectory());
    // }
	
	
	 // public function getPhotosInitialPreview()
    // {
        // $files = FileHelper::findFiles($this->getUploadConfirmationDirectory(), ['recursive' => false]);
        // $image = [];
        // if (isset($files[0])) {
            // foreach ($files as $index => $file) {
				// $temp1 = explode("/", $file);
                // $file = end($temp1);
				// $temp2 = explode("\\", $file);
                // $file = end($temp2);
                // if(StringHelper::startsWith($file, $this->topupID, false)){
                    // $file = $this->getUploadConfirmationDirectory(false) . $file;
                    // $image[] = '<img src="' . $file . '" class="file-preview-image">';
                // }
            // }
        // }
        // return $image;
    // }

    // public function getPhotosInitialPreviewConfig()
    // {
        // $files = FileHelper::findFiles($this->getUploadConfirmationDirectory(), ['recursive' => false]);
        // $image = [];
        // if (isset($files[0])) {
            // foreach ($files as $index => $file) {
                // $temp1 = explode("/", $file);
                // $file = end($temp1);
				// $temp2 = explode("\\", $file);
                // $file = end($temp2);
                // if(StringHelper::startsWith($file, $this->topupID, false)){
                    // $image[] = [
                        // 'url' => Yii::$app->urlManager->createUrl(['topup/remove-image', 'id' => $this->topupID]),
                        // 'key' => $file,
                        // 'extra' => ['key' => $file]
                    // ];
                // }
            // }
        // }
        // return $image;
    // }

    // public function removeImage($imageID)
    // {
        // $filePath = $this->getUploadConfirmationDirectory() . $imageID;
        // unlink($filePath);
    // }
	
	 // public function getImages($type)
    // {
         // $files = FileHelper::findFiles($this->getUploadConfirmationDirectory(), ['recursive' => false]);
         // $image = [];
         // if (isset($files[0])) {
             // foreach ($files as $index => $file) {
                // $temp1 = explode("/", $file);
                // $file = end($temp1);
				// $temp2 = explode("\\", $file);
                // $file = end($temp2);

                 // if($type <> 'All'){
                     // if(StringHelper::startsWith($file, $type, false)){
                         // $file = $this->getUploadConfirmationDirectory(false) . $file;
                         // $image[] = $file;
                     // }
                 // }
                 // else{
                     // $file = $this->getUploadConfirmationDirectory(false) . $file;
                     // $image[] = $file;
                 // }
             // }
         // }
         // return $image;
     // }
	 
	 
}
