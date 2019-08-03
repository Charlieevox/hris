<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "lk_userrole".
 *
 * @property integer $userRoleID
 * @property string $userRole
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsUser[] $msUser
 * @property MsUser[] $msUserAccess
 */
class LkUserRole extends \yii\db\ActiveRecord
{
	public $joinMsUserAccess;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_userrole';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userRole', 'createdBy', 'createdDate'], 'required'],
            [['flagActive'], 'boolean'],
            [['createdDate', 'editedDate'], 'safe'],
            [['userRole'], 'string', 'max' => 100],
            [['createdBy', 'editedBy'], 'string', 'max' => 50],
			[['userRole','flagActive'], 'safe', 'on'=>'search'],
			[['joinMsUserAccess'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userRoleID' => 'User Role ID',
            'userRole' => 'User Role',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }
	
	  public function getMsUser()
    {
        return $this->hasMany(MsUser::className(), ['userRoleID' => 'userRoleID']);
    }
	
	 public function getMsUserAccess()
    {
        return $this->hasMany(MsUserAccess::className(), ['userRoleID' => 'userRoleID']);
    }
	
	public static function findActive()
    {
        return self::find()->andWhere(self::tableName() . '.flagActive = 1');
    }
    
    public function search()
    {
        $query = self::find()
            ->andFilterWhere(['like', 'lk_userrole.userRole', $this->userRole])
            ->andFilterWhere(['=', 'lk_userrole.flagActive', $this->flagActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['userRole' => SORT_ASC],
                'attributes' => ['userRole']
            ],
        ]);
		
        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
            'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
        ];
        return $dataProvider;
    }
}
