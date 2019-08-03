<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "ms_useraccess".
 *
 * @property integer $ID
 * @property integer $userRoleID
 * @property string $accessID
 * @property boolean $viewAcc
 * @property boolean $insertAcc
 * @property boolean $updateAcc
 * @property boolean $deleteAcc
 * @property boolean $authorizeAcc
 *
 * @property LkFilteraccess $access
 */
class MsUserAccess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_useraccess';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userRoleID', 'accessID'], 'required'],
            [['userRoleID'], 'integer'],
            [['viewAcc', 'insertAcc', 'updateAcc', 'deleteAcc', 'authorizeAcc'], 'boolean'],
            [['accessID'], 'string', 'max' => 10],
			[['userRoleID', 'accessID',], 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'userRoleID' => 'User Role',
            'accessID' => 'Access',
            'viewAcc' => 'View',
            'insertAcc' => 'Insert',
            'updateAcc' => 'Update',
            'deleteAcc' => 'Delete',
            'authorizeAcc' => 'Authorize',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getAccess()
    // {
        // return $this->hasOne(LkFilterAccess::className(), ['accessID' => 'accessID']);
    // }
	
	 public function getUserRoles()
    {
        return $this->hasOne(LkUserRole::className(), ['userRoleID' => 'userRoleID']);
    }
	
	 public function getAccessControls()
    {
        return $this->hasOne(LkAccessControl::className(), ['accessID' => 'accessID']);
    }
	
	
	 public function search()
    {
    	$query = self::find()
    			->joinWith('userRoles')
				->joinWith('accessControls')
		    	->andFilterWhere(['like', 'ms_useraccess.userRoleID', $this->userRoleID])
				->andFilterWhere(['like', 'ms_useraccess.accessID', $this->accessID])
	            ->andFilterWhere(['=', 'ms_useraccess.viewAcc', $this->viewAcc])
            	->andFilterWhere(['=', 'ms_useraccess.insertAcc', $this->insertAcc])
				->andFilterWhere(['=', 'ms_useraccess.updateAcc', $this->updateAcc])
            	->andFilterWhere(['=', 'ms_useraccess.deleteAcc', $this->deleteAcc])
                ->andFilterWhere(['=', 'ms_useraccess.authorizeAcc', $this->authorizeAcc]);
    			
    	
    	$dataProvider = new ActiveDataProvider([
    	    'query' => $query,
    	    'sort' => [
    	        'defaultOrder' => ['userRoleID' => SORT_ASC],
    	        'attributes' => ['userRoleID']
    	    ],
    	]);
		
    	$dataProvider->sort->attributes['accessID'] = [
    	    'asc' => ['lk_accesscontrol.description' => SORT_ASC],
    	    'desc' => ['lk_accesscontrol.description' => SORT_DESC],
    	];
		
		$dataProvider->sort->attributes['viewAcc'] = [
            'asc' => [self::tableName() . '.viewAcc' => SORT_ASC],
            'desc' => [self::tableName() . '.viewAcc' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['insertAcc'] = [
            'asc' => [self::tableName() . '.insertAcc' => SORT_ASC],
            'desc' => [self::tableName() . '.insertAcc' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['updateAcc'] = [
            'asc' => [self::tableName() . '.updateAcc' => SORT_ASC],
            'desc' => [self::tableName() . '.updateAcc' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['deleteAcc'] = [
            'asc' => [self::tableName() . '.deleteAcc' => SORT_ASC],
            'desc' => [self::tableName() . '.deleteAcc' => SORT_DESC],
        ];
                
             	$dataProvider->sort->attributes['authorizeAcc'] = [
            'asc' => [self::tableName() . '.authorizeAcc' => SORT_ASC],
            'desc' => [self::tableName() . '.authorizeAcc' => SORT_DESC],
        ];
		
    	return $dataProvider;
    }
}
