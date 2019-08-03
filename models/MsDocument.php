<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_document".
 *
 * @property integer $documentID
 * @property string $documentName
 * @property string $notes
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrDocumentTrackingHead[] $trDocumentTrackingHead
 */
class MsDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentName', 'createdBy', 'createdDate'], 'required'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['documentName', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['notes'], 'string', 'max' => 100],
			[['documentName','notes','flagActive'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'documentID' => 'Document ID',
            'documentName' => 'Document Name',
            'notes' => 'Notes',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrDocumenttrackingheads()
    {
        return $this->hasMany(TrDocumentTrackingHead::className(), ['documentID' => 'documentID']);
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    public function search()
    {
        $query = self::find()
            ->andFilterWhere(['like', 'ms_document.documentName', $this->documentName])
			->andFilterWhere(['=', 'ms_document.notes', $this->notes])
            ->andFilterWhere(['=', 'ms_document.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['documentName' => SORT_ASC],
                'attributes' => ['documentName']
            ],
        ]);
		$dataProvider->sort->attributes['notes'] = [
            'asc' => [self::tableName() . '.notes' => SORT_ASC],
            'desc' => [self::tableName() . '.notes' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
	
}
