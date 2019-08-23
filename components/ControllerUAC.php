<?php
namespace app\components;

use Yii;
use yii\web\NotFoundHttpException;

class ControllerUAC extends \yii\web\Controller{
    public function beforeAction($action) {
        parent::beforeAction($action);
		$url = '/' . $action->controller->id;
	    $connection = Yii::$app->db;
	    $roleID = Yii::$app->user->identity->userRoleID;
	    $sql = "    SELECT DISTINCT a.accessID,b.node
			FROM ms_useraccess a
			JOIN lk_accesscontrol b ON a.accessID = b.accessID
			JOIN 
			(
				SELECT accessID, CASE WHEN indexAcc = 1 THEN 'index' ELSE '' END 'action' 
				FROM ms_useraccess 
				UNION ALL 
				SELECT accessID, CASE WHEN viewAcc = 1 THEN 'view' ELSE '' END 'action' 
				FROM ms_useraccess 
				UNION ALL 
				SELECT accessID, CASE WHEN insertAcc = 1 THEN 'create' ELSE '' END 'action' 
				FROM ms_useraccess 
				UNION ALL 
				SELECT accessID, CASE WHEN updateAcc = 1 THEN 'update' ELSE '' END 'action' 
				FROM ms_useraccess 
				UNION ALL 
				SELECT accessID, CASE WHEN deleteAcc = 1 THEN 'delete' ELSE '' END 'action' 
				FROM ms_useraccess
				UNION ALL 
				SELECT accessID, CASE WHEN authorizeAcc = 1 THEN 'approve' ELSE '' END 'action' 
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'browse' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'check' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'remove-image' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'maintenance' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'restore' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'confirmation' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'outstanding' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'depreciation' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'task' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'process' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'approve' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'revision' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'delete' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'update' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'budget' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'browseprop' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'browseinvoice' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'proposal' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'recurring' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'print' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'finish' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'timeline' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'linetime' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'browseschedule' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'download' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'input' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'Lists' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'Addbrowse' AS 'action'
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'printPage' AS 'action'
				FROM ms_useraccess    
				UNION ALL                                
				SELECT accessID, 'browsedelete' AS 'action'
				FROM ms_useraccess     
				UNION ALL
				SELECT accessID, 'Bankdescription' AS 'action'
				FROM ms_useraccess 
				UNION ALL
				SELECT accessID, 'description' AS 'action'
				FROM ms_useraccess   
				UNION ALL
				SELECT accessID, 'get-image' AS 'action'
				FROM ms_useraccess   
				UNION ALL
				SELECT accessID, 'upload' AS 'action'                        
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'reprocess' AS 'action'                        
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'close' AS 'action'                        
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'get-position' AS 'action'                        
				FROM ms_useraccess
					UNION ALL
				SELECT accessID, 'save' AS 'action'                        
				FROM ms_useraccess
				UNION ALL
				SELECT accessID, 'RemoveImage' AS 'action'                        
				FROM ms_useraccess 
				UNION ALL
				SELECT accessID, 'generate-schedule' AS 'action'                        
				FROM ms_useraccess 
			) c on a.accessID = c.accessID
			WHERE a.userRoleID = " . $roleID . " AND b.node = '" . $url . "' AND c.action = '". $action->id ."'
			ORDER BY a.accessID ";
		$model = $connection->createCommand($sql);
		$result = $model->queryAll();
        $result = \yii\helpers\ArrayHelper::getValue($result, 0);
	if($result['node'] != NULL){
            return true;
        } else {
            throw new NotFoundHttpException($sql);

        }
    }
	
    public function availableAction($userRoleID, $controllerID){
        $create = ' hidden ';
        $template = '';
        $url = '/' . $controllerID;
        $connection = Yii::$app->db;
		
		$sql = "SELECT CAST(a.viewAcc AS UNSIGNED) AS viewAcc, CAST(a.insertAcc AS UNSIGNED) 'insertAcc', CAST(a.updateAcc AS UNSIGNED) 'updateAcc', 
					CAST(a.deleteAcc AS UNSIGNED) 'deleteAcc', CAST(a.authorizeAcc as UNSIGNED) 'authorizeAcc'
			FROM ms_useraccess a
			JOIN lk_accesscontrol b ON a.accessID = b.accessID
			WHERE a.userRoleID = " . $userRoleID . " AND b.node = '" . $url . "'
			ORDER BY a.accessID ";
		
        $temp = $connection->createCommand($sql);
		$result = $temp->queryAll();
        $result = \yii\helpers\ArrayHelper::getValue($result, 0);
         
        $viewColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'viewAcc');
		$insertColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'insertAcc');
        $updateColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'updateAcc');
		$deleteColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'deleteAcc');
		$authorizeColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'authorizeAcc');
	          
		if ($result['insertAcc'] == 1) {
			$create = '';
		}
		
		if ($result['viewAcc'] == 1) {
			$template = $template . '{view}';
		}
		
		if ($result['updateAcc'] == 1) {
			$template = $template . '{update}';
		}
		
		if ($result['authorizeAcc'] == 1) {
			$template = $template . '{approve}';
		}
		
		if ($result['deleteAcc'] == 1) {
			$template = $template . '{delete}';
		}
		
        return $create . '-' . $template;   
    }
	
	
	public function masterAction($userRoleID, $controllerID){
        $create = ' hidden ';
        $template = '';
        $url = '/' . $controllerID;
        $connection = Yii::$app->db;
		
		$sql = "SELECT CAST(a.viewAcc AS UNSIGNED) 'viewAcc', CAST(a.insertAcc AS UNSIGNED) 'insertAcc', CAST(a.updateAcc AS UNSIGNED) 'updateAcc', 
                CAST(a.deleteAcc AS UNSIGNED) 'deleteAcc', CAST(a.authorizeAcc as UNSIGNED) 'authorizeAcc'
		FROM ms_useraccess a
		JOIN lk_accesscontrol b ON a.accessID = b.accessID
		WHERE a.userRoleID = " . $userRoleID . " AND b.node = '" . $url . "'
		ORDER BY a.accessID ";
		
        $temp = $connection->createCommand($sql);
     
		$result = $temp->queryAll();
        $result = \yii\helpers\ArrayHelper::getValue($result, 0);

		$insertColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'insertAcc');
        $updateColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'updateAcc');
		$deleteColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'deleteAcc');
		
		if ($result['insertAcc'] == 1) {
			$create = '';
		}
		
		if ($result['updateAcc'] == 1) {
			$template = $template . '{update}';
		}
		
		if ($result['deleteAcc'] == 1) {
			$template = $template . '{delete}';
		}
        return $create . '-' . $template;
        
    }
	
	public function assetAction($userRoleID, $controllerID){
        $create = ' hidden ';
        $template = '';
        $url = '/' . $controllerID;
        $connection = Yii::$app->db;
		
		$sql = "SELECT CAST(a.viewAcc AS UNSIGNED) 'viewAcc', CAST(a.insertAcc AS UNSIGNED) 'insertAcc', CAST(a.updateAcc AS UNSIGNED) 'updateAcc', 
					CAST(a.deleteAcc AS UNSIGNED) 'deleteAcc', CAST(a.authorizeAcc as UNSIGNED) 'authorizeAcc', CAST(a.insertAcc AS UNSIGNED) 'maintenanceAcc', 
					CAST(a.insertAcc AS UNSIGNED) 'checkAcc'
			FROM ms_useraccess a
			JOIN lk_accesscontrol b ON a.accessID = b.accessID
			WHERE a.userRoleID = " . $userRoleID . " AND b.node = '" . $url . "'
			ORDER BY a.accessID ";
		
        $temp = $connection->createCommand($sql);
		$result = $temp->queryAll();
        $result = \yii\helpers\ArrayHelper::getValue($result, 0);
		
        $viewColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'viewAcc');
		$insertColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'insertAcc');
        $updateColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'updateAcc');
		$deleteColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'deleteAcc');
		$maintenanceColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'maintenanceAcc');
		$checkColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'checkAcc');
		
		
		if ($result['insertAcc'] == 1) {
			$create = '';
		}
		
		if ($result['viewAcc'] == 1) {
			$template = $template . '{view}';
		}
		
		
		if ($result['checkAcc'] == 1) {
			$template = $template . '{check}';
		}
		
		if ($result['updateAcc'] == 1) {
			$template = $template . '{update}';
		}
		
		if ($result['maintenanceAcc'] == 1) {
			$template = $template . '{maintenance}';
		}
		
		if ($result['deleteAcc'] == 1) {
			$template = $template . '{delete}';
		}
        return $create . '-' . $template;
        
    }
    
    
	public function proposalAction($userRoleID, $controllerID){
        $create = ' hidden ';
        $template = '';
        $url = '/' . $controllerID;
        $connection = Yii::$app->db;
		
		$sql = "SELECT CAST(a.viewAcc AS UNSIGNED) 'viewAcc', CAST(a.insertAcc AS UNSIGNED) 'insertAcc', CAST(a.updateAcc AS UNSIGNED) 'updateAcc', 
					CAST(a.deleteAcc AS UNSIGNED) 'deleteAcc', CAST(a.authorizeAcc as UNSIGNED) 'authorizeAcc'
			FROM ms_useraccess a
			JOIN lk_accesscontrol b ON a.accessID = b.accessID
			WHERE a.userRoleID = " . $userRoleID . " AND b.node = '" . $url . "'
			ORDER BY a.accessID ";
		
        $temp = $connection->createCommand($sql);
		$result = $temp->queryAll();
        $result = \yii\helpers\ArrayHelper::getValue($result, 0);
		
        $viewColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'viewAcc');
		$insertColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'insertAcc');
        $updateColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'updateAcc');
		$authorizeColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'authorizeAcc');
		$deleteColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'deleteAcc');
	
		
		if ($result['insertAcc'] == 1) {
			$create = '';
		}
		
		if ($result['viewAcc'] == 1) {
			$template = $template . '{view}';
		}
		
		if ($result['updateAcc'] == 1) {
			$template = $template . '{update}';
		}
		
		if ($result['authorizeAcc'] == 1) {
			$template = $template . '{approve}';
		}
		
		if ($result['deleteAcc'] == 1) {
			$template = $template . '{delete}';
		}
        return $create . '-' . $template;
        
    }
    
     public function jobAction($userRoleID, $controllerID){
        $create = ' hidden ';
        $template = '';
        $url = '/' . $controllerID;
        $connection = Yii::$app->db;
		
		$sql = "SELECT CAST(a.viewAcc AS UNSIGNED) 'viewAcc', CAST(a.insertAcc AS UNSIGNED) 'insertAcc', CAST(a.updateAcc AS UNSIGNED) 'updateAcc', 
					CAST(a.deleteAcc AS UNSIGNED) 'deleteAcc', CAST(a.authorizeAcc as UNSIGNED) 'authorizeAcc', CAST(a.authorizeAcc as UNSIGNED) 'budgetAcc',
					CAST(a.authorizeAcc as UNSIGNED) 'finishAcc'
			FROM ms_useraccess a
			JOIN lk_accesscontrol b ON a.accessID = b.accessID
			WHERE a.userRoleID = " . $userRoleID . " AND b.node = '" . $url . "'
			ORDER BY a.accessID ";
		
        $temp = $connection->createCommand($sql);
		$result = $temp->queryAll();
        $result = \yii\helpers\ArrayHelper::getValue($result, 0);
		
        $viewColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'viewAcc');
		$insertColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'insertAcc');
        $updateColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'updateAcc');
		$deleteColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'deleteAcc');
		$budgetColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'budgetAcc');
		$finishColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'finishAcc');
	
		
		if ($result['insertAcc'] == 1) {
			$create = '';
		}
		
		if ($result['viewAcc'] == 1) {
			$template = $template . '{view}';
		}
		
		if ($result['updateAcc'] == 1) {
			$template = $template . '{update}';
		}
                
		if ($result['deleteAcc'] == 1) {
			$template = $template . '{delete}';
		}
		
		if ($result['budgetAcc'] == 1) {
			$template = $template . '{budget}';
		}
		
		if ($result['finishAcc'] == 1) {
			$template = $template . '{finish}';
		}
        return $create . '-' . $template;
        
    }
    
    
     public function budgetAction($userRoleID, $controllerID){
        $create = ' hidden ';
        $template = '';
        $url = '/' . $controllerID;
        $connection = Yii::$app->db;
		
		$sql = "SELECT CAST(a.viewAcc AS UNSIGNED) 'viewAcc', CAST(a.insertAcc AS UNSIGNED) 'insertAcc', CAST(a.updateAcc AS UNSIGNED) 'updateAcc', 
					CAST(a.deleteAcc AS UNSIGNED) 'deleteAcc', CAST(a.authorizeAcc as UNSIGNED) 'authorizeAcc', CAST(a.authorizeAcc as UNSIGNED) 'proposalAcc' 
			FROM ms_useraccess a
			JOIN lk_accesscontrol b ON a.accessID = b.accessID
			WHERE a.userRoleID = " . $userRoleID . " AND b.node = '" . $url . "'
			ORDER BY a.accessID ";
		
        $temp = $connection->createCommand($sql);
		$result = $temp->queryAll();
        $result = \yii\helpers\ArrayHelper::getValue($result, 0);
		
        $viewColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'viewAcc');
		$insertColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'insertAcc');
		$updateColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'updateAcc');
		$deleteColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'deleteAcc');
		$proposalColumn = \yii\helpers\ArrayHelper::getColumn($temp->queryAll(), 'proposalAcc');
	
		
		if ($result['insertAcc'] == 1) {
			$create = '';
		}
		
		if ($result['viewAcc'] == 1) {
			$template = $template . '{view}';
		}
		
		if ($result['updateAcc'] == 1) {
			$template = $template . '{update}';
		}
                
                if ($result['deleteAcc'] == 1) {
			$template = $template . '{delete}';
		}
		
		if ($result['proposalAcc'] == 1) {
			$template = $template . '{proposal}';
		}
		
        return $create . '-' . $template;
        
    }
}