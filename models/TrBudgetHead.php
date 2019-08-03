<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_budgethead".
 *
 * @property integer $ID
 * @property integer $jobID
 * @property string $additionalInfo
 * @property string $totalCost
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class TrBudgetHead extends \yii\db\ActiveRecord
{
	public $joinBudgetDetailStaff;
	public $joinBudgetDetailMisc;
	public $totalCostSep;
	public $statusData;
	public $projectNames;
        public $clientIDs;
        public $clientNames;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_budgethead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jobID', 'budgetHeadDate', 'totalCost', 'locationID'], 'required'],
            [['jobID','locationID'], 'integer'],
            [['totalCost'], 'string'],
            [['budgetHeadDate', 'createdDate', 'editedDate'], 'safe'],
            [['additionalInfo'], 'string', 'max' => 200],
            [['createdBy', 'editedBy'], 'string', 'max' => 50],
            [['jobID', 'budgetHeadDate', 'totalCost', 'totalCostSep', 'statusData', 'projectNames', 'locationID'], 'safe', 'on' => 'search'],
            [['joinBudgetDetailStaff','joinBudgetDetailMisc'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
			'budgetHeadDate' => 'Budget Head Date',
            'jobID' => 'Project Name',
            'additionalInfo' => 'Additional Info',
            'totalCost' => 'Total Cost',
			'totalCostSep' => 'Total Budget',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
			'projectNames' => 'Project Name',
            'locationID' => 'Location Name',
        ];
    }
	
	  public function getBudgetDetailStaffs()
    {
        return $this->hasOne(TrBudgetDetailStaff::className(), ['BHID' => 'ID']);
    }
	
	 public function getBudgetDetailMiscs()
    {
        return $this->hasOne(TrBudgetDetailMisc::className(), ['BHID' => 'ID']);
    }
	
		 public function getJobs()
    {
        return $this->hasOne(TrJob::className(), ['jobID' => 'jobID']);
    }
	
		 public function search()
    {
    	$query = self::find()
		->joinWith('budgetDetailStaffs')
    	->joinWith('budgetDetailMiscs')
		->joinWith('jobs')
		->andFilterWhere(['=', "DATE_FORMAT(tr_budgethead.budgetHeadDate, '%d-%m-%Y')", $this->budgetHeadDate])
    	->andFilterWhere(['=', 'tr_budgethead.jobID', $this->jobID])
		->andFilterWhere(['like', 'tr_job.projectName', $this->projectNames])
		->andFilterWhere(['=', 'tr_budgethead.totalCost', $this->totalCost])
		->andFilterWhere(['=', 'tr_budgethead.totalCost', $this->totalCostSep])
                ->andFilterWhere(['=', 'tr_budgethead.locationID', $this->locationID])
		->andFilterWhere(['=', 'tr_job.status', $this->statusData]);
		
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query, 
    			'sort' => [
    					'defaultOrder' => ['budgetHeadDate' => SORT_DESC],
    					'attributes' => ['budgetHeadDate']
    			],
    	]);
    
    	$dataProvider->sort->attributes['jobID'] = [
    			'asc' => ['tr_job.projectName' => SORT_ASC],
    			'desc' => ['tr_job.projectName' => SORT_DESC],
    	];
		
		$dataProvider->sort->attributes['totalCost'] = [
    			'asc' => [self::tableName() . '.totalCost' => SORT_ASC],
    			'desc' => [self::tableName() . '.totalCost' => SORT_DESC],
    	];
		
		$dataProvider->sort->attributes['totalCostSep'] = [
    			'asc' => [self::tableName() . '.totalCostSep' => SORT_ASC],
    			'desc' => [self::tableName() . '.totalCostSep' => SORT_DESC],
    	];
		
		$dataProvider->sort->attributes['projectNames'] = [
			'asc' => ['tr_job.projectName' => SORT_ASC],
			'desc' => ['tr_job.projectName' => SORT_DESC],
    	];
    	return $dataProvider;
    }
	
	 public function afterFind(){
    	parent::afterFind();
        $this->budgetHeadDate = AppHelper::convertDateTimeFormat($this->budgetHeadDate, 'Y-m-d H:i:s', 'd-m-Y');
		$this->totalCostSep = number_format($this->totalCost,2,",",".");
    	$this->joinBudgetDetailStaff = [];
		$this->joinBudgetDetailMisc = [];
    	$i = 0;
    	foreach ($this->getBudgetDetailStaffs()->all() as $joinBudgetDetailStaff) {
			$this->joinBudgetDetailStaff[$i]["positionID"] = $joinBudgetDetailStaff->positionID;
			$this->joinBudgetDetailStaff[$i]["positionName"] = $joinBudgetDetailStaff->positions->positionName;
                        $this->joinBudgetDetailStaff[$i]["unit"] = $joinBudgetDetailStaff->timePosition->unit;
			$this->joinBudgetDetailStaff[$i]["rate"] = $joinBudgetDetailStaff->positions->rate;
                        $this->joinBudgetDetailStaff[$i]["hourUnit"] = $joinBudgetDetailStaff->timePosition->unitValue;
			$this->joinBudgetDetailStaff[$i]["length"] = $joinBudgetDetailStaff->length;
                         $this->joinBudgetDetailStaff[$i]["totalCost"] = $joinBudgetDetailStaff->totalCost;
                        $i += 1;
    	}
		
		$i = 0;
		foreach ($this->getBudgetDetailMiscs()->all() as $joinBudgetDetailMisc) {
			$this->joinBudgetDetailMisc[$i]["coaNo"] = $joinBudgetDetailMisc->coaNo;
			$this->joinBudgetDetailMisc[$i]["description"] = $joinBudgetDetailMisc->coaNos->description;
			$this->joinBudgetDetailMisc[$i]["subTotal"] = $joinBudgetDetailMisc->subTotal;
			$this->joinBudgetDetailMisc[$i]["qty"] = $joinBudgetDetailMisc->qty;
			$this->joinBudgetDetailMisc[$i]["totalCost"] = $joinBudgetDetailMisc->totalCost;
    		$i += 1;
    	}
    }
}
