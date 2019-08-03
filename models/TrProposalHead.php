<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_proposalhead".
 *
 * @property string $proposalNum
 * @property string $proposalDate
 * @property boolean $status
 * @property string $subTotal
 * @property string $discount
 * @property string $total
 * @property string $additionalInfo
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class TrProposalHead extends \yii\db\ActiveRecord
{
	public $joinProposalDetail;
	public $projectNames;
	public $jobIDs;
	public $percentage;
        public $productName;
        public $barcodeNumber;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
       return Yii::$app->user->identity->dbName.'.tr_proposalhead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proposalDate', 'subTotal', 'clientID', 'discount', 'totalProposal', 'totalBudgets', 'locationID'], 'required'],
            [['proposalDate', 'createdDate', 'editedDate'], 'safe'],
            [['clientID', 'status', 'locationID'], 'integer'],
            [['subTotal', 'discount', 'totalProposal', 'totalBudgets'], 'string'],
            [['proposalNum', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['additionalInfo'], 'string', 'max' => 200],
            [['proposalNum', 'proposalDate', 'totalProposal', 'status', 'clientID', 'projectNames', 'percentage', 'locationID'], 'safe', 'on'=>'search'],
            [['joinProposalDetail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'proposalNum' => 'Proposal Number',
            'proposalDate' => 'Proposal Date',
            'clientID' => 'Client Name',
            'status' => 'Status',
            'subTotal' => 'Sub Total',
            'discount' => 'Discount',
            'totalProposal' => 'Total Proposal',
            'totalBudgets' => 'Total Budget',
            'additionalInfo' => 'Additional Info',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'percentage' => 'Percentage (%)',
            'locationID' => 'Location Name',
        ];
    }
	
	public function getProposalDetails()
	{
        return $this->hasMany(TrProposalDetail::className(), ['proposalNum' => 'proposalNum'])
		->from(['details' => TrProposalDetail::tableName()]);
    }
	
	public function getJob()
    {
        return $this->hasOne(TrJob::className(), ['jobID' => 'jobID'])->viaTable('tr_proposaldetail', ['proposalNum' => 'proposalNum']);
    }
	
	 public function getClient()
    {
        return $this->hasOne(MsClient::className(), ['clientID' => 'clientID']);
    }
    
      public function getStatus1()
	{
        return $this->hasOne(MsStatus::className(), ['statusID' => 'status'])->onCondition(['statusKey' => 'Proposal']);
    }
	
	
	public function search()
    {
        $query = self::find()
            ->joinWith('proposalDetails')
            ->joinWith('job')
            ->joinWith('client')
            ->joinWith('status1')
            ->andFilterWhere(['like', 'tr_proposalhead.proposalNum', $this->proposalNum])
            ->andFilterWhere(['=', "DATE_FORMAT(tr_proposalhead.proposalDate, '%d-%m-%Y')", $this->proposalDate])
            ->andFilterWhere(['=', 'tr_proposalhead.clientID', $this->clientID])
            ->andFilterWhere(['=', 'tr_proposalhead.totalProposal', $this->totalProposal])
            ->andFilterWhere(['like', 'tr_job.projectName', $this->projectNames])
            ->andFilterWhere(['=', 'tr_proposalhead.locationID', $this->locationID])
            ->andFilterWhere(['=', 'tr_proposalhead.status', $this->status]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['proposalNum' => SORT_DESC],
                'attributes' => ['proposalNum']
            ],
        ]);
		
		$dataProvider->sort->attributes['proposalDate'] = [
            'asc' => [self::tableName() . '.proposalDate' => SORT_ASC],
            'desc' => [self::tableName() . '.proposalDate' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['totalProposal'] = [
				'asc' => [self::tableName() . '.totalProposal' => SORT_ASC],
				'desc' => [self::tableName() . '.totalProposal' => SORT_DESC],
		];
		
		$dataProvider->sort->attributes['projectNames'] = [
				'asc' => ['tr_job.projectName' => SORT_ASC],
				'desc' => ['tr_job.projectName' => SORT_DESC],
		];
		
		$dataProvider->sort->attributes['clientID'] = [
				'asc' => ['ms_client.clientName' => SORT_ASC],
				'desc' => ['ms_client.clientName' => SORT_DESC],
		];
		
		$dataProvider->sort->attributes['status'] = [
			'asc' => [self::tableName() . '.status' => SORT_ASC],
			'desc' => [self::tableName() . '.status' => SORT_DESC],
		];
		
		 $dataProvider->sort->attributes['percentage'] = [
		'asc' => ['percentage' => SORT_ASC],
		'desc' => ['percentage' => SORT_DESC],
        ];
		
        return $dataProvider;
    }
	
	public function afterFind(){
        parent::afterFind();
        $this->proposalDate = AppHelper::convertDateTimeFormat($this->proposalDate, 'Y-m-d H:i:s', 'd-m-Y');
        $this->percentage = (($this->totalProposal-$this->totalBudgets)/$this->totalBudgets)*100;
        $this->joinProposalDetail = [];
        $i = 0;
        foreach ($this->getProposalDetails()->all() as $joinProposalDetail) {
            $this->joinProposalDetail[$i]["barcodeNumber"] = $joinProposalDetail->barcodeNumber;
            $this->joinProposalDetail[$i]["jobID"] = $joinProposalDetail->jobID;
            $this->joinProposalDetail[$i]["productName"] = $joinProposalDetail->product->productName;
            $this->joinProposalDetail[$i]["uomName"] = $joinProposalDetail->productDetail->uom->uomName;
            $this->joinProposalDetail[$i]["totalBudget"] = $joinProposalDetail->budget->totalCost;
            $this->joinProposalDetail[$i]["qty"] = $joinProposalDetail->qty;
            $this->joinProposalDetail[$i]["price"] = $joinProposalDetail->price;
            $this->joinProposalDetail[$i]["discount"] = $joinProposalDetail->discount;
            $this->joinProposalDetail[$i]["total"] = $joinProposalDetail->total;
            $i += 1;
        }
    }
}
