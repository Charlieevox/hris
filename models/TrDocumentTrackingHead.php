<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
/**
 * This is the model class for table "tr_documenttrackinghead".
 *
 * @property string $documentTrackingNum
 * @property string $documentTrackingDate
 * @property integer $documentID
 * @property string $documentNum
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $documentTrackingName
 * @property string $documentTrackingApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrDocumentTrackingDetail[] $trDocumentTrackingDetail
 * @property MsDocument $document
 */
class TrDocumentTrackingHead extends \yii\db\ActiveRecord
{
	public $joinTrDocumentTrackingDetail;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_documenttrackinghead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentTrackingNum', 'documentTrackingDate', 'documentID', 'documentNum', 'locationID', 'documentTrackingName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['documentTrackingDate', 'createdDate', 'editedDate'], 'safe'],
            [['documentID', 'status', 'locationID'], 'integer'],
            [['documentTrackingNum', 'documentNum', 'documentTrackingName', 'documentTrackingApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
			[['documentTrackingNum','documentTrackingDate','documentID','documentNum','locationID'], 'safe', 'on'=>'search'],
			[['joinTrDocumentTrackingDetail'], 'safe']
        ];
    }
	
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'documentTrackingNum' => 'Document Tracking Number',
            'documentTrackingDate' => 'Document Tracking Date',
            'documentID' => 'Document Name',
            'documentNum' => 'Document Number',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'documentTrackingName' => 'Document Tracking Name',
            'documentTrackingApproval' => 'Document Tracking Approval',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrDocumentTrackingDetail()
    {
        return $this->hasMany(TrDocumentTrackingDetail::className(), ['documentTrackingNum' => 'documentTrackingNum']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(MsDocument::className(), ['documentID' => 'documentID']);
    }
	
	public function search()
    {
        $query = self::find()
			->joinWith('trDocumentTrackingDetail')
			->joinWith('document')
			->andFilterWhere(['like', 'tr_documenttrackinghead.documentTrackingNum', $this->documentTrackingNum])
                        ->andFilterWhere(['=', "DATE_FORMAT(tr_documenttrackinghead.documentTrackingDate, '%d-%m-%Y')", $this->documentTrackingDate])
                        ->andFilterWhere(['=', 'tr_documenttrackinghead.documentID', $this->documentID])
                        ->andFilterWhere(['=', 'tr_documenttrackinghead.locationID', $this->locationID])
			->andFilterWhere(['=', 'tr_documenttrackinghead.documentNum', $this->documentNum]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['documentTrackingDate' => SORT_DESC],
                'attributes' => ['documentTrackingDate']
            ],
        ]);
		
		$dataProvider->sort->attributes['documentTrackingNum'] = [
            'asc' => [self::tableName() . '.documentTrackingNum' => SORT_ASC],
            'desc' => [self::tableName() . '.documentTrackingNum' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['documentID'] = [
            'asc' => ['ms_document.documentName' => SORT_ASC],
            'desc' => ['ms_document.documentName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['documentNum'] = [
            'asc' => [self::tableName() . '.documentNum' => SORT_ASC],
            'desc' => [self::tableName() . '.documentNum' => SORT_DESC],
        ];
		
        return $dataProvider;
    }
	
	public function afterFind(){
        parent::afterFind();
		$this->documentTrackingDate = AppHelper::convertDateTimeFormat($this->documentTrackingDate, 'Y-m-d H:i:s', 'd-m-Y');
        $this->joinTrDocumentTrackingDetail = [];
        $i = 0;
        foreach ($this->getTrDocumentTrackingDetail()->all() as $joinTrDocumentTrackingDetail) {
            $this->joinTrDocumentTrackingDetail[$i]["actionDate"] = AppHelper::convertDateTimeFormat($joinTrDocumentTrackingDetail->actionDate, 'Y-m-d H:i:s', 'd-m-Y H:i');
            $this->joinTrDocumentTrackingDetail[$i]["actionDesc"] = $joinTrDocumentTrackingDetail->actionDesc;
			$this->joinTrDocumentTrackingDetail[$i]["actionBy"] = $joinTrDocumentTrackingDetail->actionBy;
            $i += 1;
        }
    }
}
