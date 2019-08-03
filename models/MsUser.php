<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_user".
 *
 * @property string $username
 * @property string $fullName
 * @property string $password
 * @property string $salt
 * @property integer $userRoleID
 * @property boolean $flagActive
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsUser $createBy0
 * @property MsUser[] $msUsers
 * @property LkUserRole $userRoles
 * @property MsUser $location
 */
class MsUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	public $password_input;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_input', 'fullName', 'userRoleID', 'companyID'],'required', 'on' => 'create'],
	    [['fullName'], 'required', 'on' => 'update'],
            [['userRoleID','companyID'], 'integer'],
            [['flagActive'], 'boolean'],
            [['createdBy','editedBy'], 'string', 'max' => 50],
            [['createdDate','editedDate'], 'safe'],
            [['username'], 'string', 'min' => 4, 'max' => 50],
            [['password_input'], 'string', 'min' => 4, 'max' => 50],
            [['username'], 'unique'],
            [['username', 'fullName', 'userRoleID', 'flagActive', 'companyID'], 'safe', 'on' => 'search'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'User Name',
            'fullName' => 'Employee Name',
            'password_input' => 'Password',
            'userRoleID' => 'User Role',
            'flagActive' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'companyID' => 'Company Name',
        ];
    }
   
    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getCreateBy0()
    // {
        // return $this->hasOne(MsUser::className(), ['userID' => 'createBy']);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getMsUsers()
    // {
        // return $this->hasMany(MsUser::className(), ['createBy' => 'userID']);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasOne(LkUserRole::className(), ['userRoleID' => 'userRoleID']);
    }
	
	public function getLocation()
    {
        return $this->hasOne(MsLocation::className(), ['locationID' => 'locationID']);
    }
	
	public function getCompany()
    {
        return $this->hasOne(MsCompany::className(), ['companyID' => 'companyID']);
    }
    
	  public static function findActive()
      {
          return self::find()->andWhere(self::tableName() . '.flagActive = 1');
      }
	
    public function getRole(){
        return $this->userRoleID;
    }
	
    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		if($insert){
    			$this->salt = Yii::$app->getSecurity()->generateRandomString(45);
    			$this->password = md5($this->password_input . $this->salt);
    		}else{
    			if(!empty($this->password_input)){
    				$this->salt = Yii::$app->getSecurity()->generateRandomString(45);
    				$this->password = md5($this->password_input . $this->salt);
    			}
    		}
    		return true;
    	} 
    }    
    
	 public function search()
    {
    	$query = self::find()
        ->joinWith('userRoles')
        ->joinWith('company')
        ->joinWith('location')
        ->where('ms_user.userRoleID >= 0')
        ->andFilterWhere(['like', 'ms_user.username', $this->username])
        ->andFilterWhere(['like', 'ms_user.fullName', $this->fullName])
        ->andFilterWhere(['=', 'ms_user.userRoleID', $this->userRoleID])
        ->andFilterWhere(['=', 'ms_user.companyID', $this->companyID])
        ->andFilterWhere(['=', 'ms_user.flagActive', $this->flagActive]);
    			
    	
    	$dataProvider = new ActiveDataProvider([
    	    'query' => $query,
    	    'sort' => [
    	        'defaultOrder' => ['username' => SORT_ASC],
    	        'attributes' => ['username']
    	    ],
    	]);
    	
		$dataProvider->sort->attributes['fullName'] = [
        'asc' => [self::tableName() . '.fullName' => SORT_DESC],
        'desc' => [self::tableName() . '.fullName' => SORT_ASC],
        ];
		
    	$dataProvider->sort->attributes['userRoleID'] = [
    	    'asc' => ['lk_userrole.userRole' => SORT_ASC],
    	    'desc' => ['lk_userrole.userRole' => SORT_DESC],
    	];

        $dataProvider->sort->attributes['flagActive'] = [
            'asc' => [self::tableName() . '.flagActive' => SORT_DESC],
            'desc' => [self::tableName() . '.flagActive' => SORT_ASC],
        ];
		
		$dataProvider->sort->attributes['companyID'] = [
    	    'asc' => ['ms_company.companyName' => SORT_ASC],
    	    'desc' => ['ms_company.companyName' => SORT_DESC],
    	];
		
    	return $dataProvider;
    }
   
    
    public function validatePassword($password){        
        return md5($password . $this->salt) == $this->password;
    }
    
    //User Identity Implementation BEGIN
    public static function findIdentity($id)
	{	
        return MsUser::findOne($id);
    }
    
    public function getId()
    {
        return $this->username;
    }
    
    public function getAuthKey()
    {
        return null;
    }
    
    public function validateAuthKey($authKey)
    {
        return null;
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }
    
    //User Identity Implementation END
    
}
