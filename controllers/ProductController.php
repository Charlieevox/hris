<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\MsProduct;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\MsProductDetail;
use yii\data\ArrayDataProvider;
use app\components\AppHelper;
use yii\db\Expression;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends ControllerUAC
{

    public function init() {
        if (Yii::$app->user->isGuest) {
            $this->goHome();
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
		$acc = explode('-', ControllerUAC::masterAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new MsProduct(['scenario' => 'search']);
        $model->flagActive = 1;

        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
			'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MsProduct();
        $model->flagActive = 1;
        $model->minQty = "1,00";
        $model->qtys = 1.00;
        $model->standardFee = "0,00";
        $model->vat = 1;
        $model->uomIDs = 1;
        $model->flag = 0;
        $model->barcodeNumbers = "Auto";
          
            
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
		$model->joinProductDetail = [];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if ($this->saveModel($model)) {
                AppHelper::insertTransactionLog('Add Master Product', $model->productName);
                return $this->redirect(['index']);
            } else {
                echo "<pre>";
                var_dump(Yii::$app->request->post());
                var_dump($model->joinProductDetail);
                echo "</pre>";
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $connection = Yii::$app->db;
        $sql = "SELECT b.barcodeNumber, b.uomID, b.qty, b.sellPrice, 1 AS flag, d.projecttypeName
        FROM ms_product a
        JOIN ms_productdetail b on a.productID = b.productID
        JOIN ms_category c on a.categoryID = c.categoryID
        JOIN lk_projecttype d on c.projecttypeID = d.projecttypeID
        where a.productID = '" .$model->productID . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                $model->barcodeNumbers = $detailMenu['barcodeNumber'];
                $model->uomIDs = $detailMenu['uomID'];
                $model->qtys = $detailMenu['qty'];
                $model->standardFee = $detailMenu['sellPrice'];
                $model->flag = $detailMenu['flag'];
                $model->projecttypeName = $detailMenu['projecttypeName'];
        }
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->flagActive = 1;
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
            if ($this->updateModel($model)) {
            	AppHelper::insertTransactionLog('Add Master Product', $model->productName);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
	
    public function actionBrowse($filter = null)
    {
        $this->view->params['browse'] = true;
        $model = new MsProductDetail(['scenario' => 'search']);
		$model->productIsActive = 1;
        $model->load(Yii::$app->request->queryParams);
		$model->productName = $filter;
        return $this->render('browse', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
     {
	$model = $this->findModel($id);
	
		$connection = Yii::$app->db;
		$sql = "SELECT b.barcodeNumber,a.productName
		FROM ms_product a
		JOIN ms_productdetail b on a.productID = b.productID
		JOIN tr_purchaseorderdetail c on b.barcodeNumber = c.barcodeNumber
		JOIN tr_salesorderdetail d on b.barcodeNumber = d.barcodeNumber
		WHERE a.productID = '" . $model->productID . "' ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
                
		if($count > 0){
			  return $this->redirect(['index']);
		}else{
                     $connection = Yii::$app->db;
        $sql = "SELECT b.barcodeNumber, b.uomID, b.qty, b.sellPrice, 1 AS flag
        FROM ms_product a
        JOIN ms_productdetail b on a.productID = b.productID
        where a.productID = '" .$model->productID . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                $model->barcodeNumbers = $detailMenu['barcodeNumber'];
                $model->uomIDs = $detailMenu['uomID'];
                $model->qtys = $detailMenu['qty'];
                $model->standardFee = $detailMenu['sellPrice'];
                $model->flag = $detailMenu['flag'];
        }
        
        $model->flagActive = 0;
        $model->save();
//        print_r($model->getErrors());
//        echo"<pre>";
//         var_dump($model);
//          echo"</pre>";
//		 yii::$app->end();
        AppHelper::insertTransactionLog('Delete Master Product', $model->productName);
        return $this->redirect(['index']);
    }
	 }
         
    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $connection = Yii::$app->db;
        $sql = "SELECT b.barcodeNumber, b.uomID, b.qty, b.sellPrice, 1 AS flag
        FROM ms_product a
        JOIN ms_productdetail b on a.productID = b.productID
        where a.productID = '" .$model->productID . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                $model->barcodeNumbers = $detailMenu['barcodeNumber'];
                $model->uomIDs = $detailMenu['uomID'];
                $model->qtys = $detailMenu['qty'];
                $model->standardFee = $detailMenu['sellPrice'];
                $model->flag = $detailMenu['flag'];
        }
        
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Product', $model->productName);
        return $this->redirect(['index']);
    }
	
    public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $barcode = $data['barcode'];
			
			$connection = Yii::$app->db;
	    	$sql = "SELECT barcodeNumber, productID
			FROM ms_productdetail
			WHERE barcodeNumber = '" . $barcode . "' ";
	    	$model = $connection->createCommand($sql);
	    	$headResult = $model->queryAll();
			
			  foreach ($headResult as $detailMenu) {
				$flagExists = true;
			}
            // $detailModel = MsProductDetail::findOne($barcode);
            // if ($detailModel !== null) {
                // $flagExists = true;
            // }
			
			
        }

        return \yii\helpers\Json::encode($flagExists);
    }

    public function actionGet()
    {
    	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$result = [];
    	if(Yii::$app->request->post() !== null){
    		$data = Yii::$app->request->post();
    		$barcodeNumber = $data['barcodeNumber'];
    		$detailModel = MsProductDetail::findOne($barcodeNumber);
    		if ($detailModel !== null){
    			$result['productName'] = $detailModel->product->productName;
    			$result['uomName'] = $detailModel->uom->uomName;
    			$result['buyPrice'] = $detailModel->buyPrice;
    			$result['sellPrice'] = $detailModel->sellPrice;
				$result['number'] = $detailModel->barcodeNumber;
    		}
    	}
    	return \yii\helpers\Json::encode($result);
    }
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    protected function saveModel($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
         $connection = Yii::$app->db;
        $sql = "SELECT MAX(CAST(barcodeNumber AS SIGNED)) as barcodeNumber FROM ms_productdetail ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();
        $count = count ($headResult);
        
        $barcode = "";
        $value = "";
        foreach ($headResult as $detailMenu) {
                $barcode = $detailMenu['barcodeNumber'];
        }
                 
        if ($barcode == NULL){
            $model->barcodeNumbers = "1";
        }else{
            $value = $barcode + 1;
            $model->barcodeNumbers = " $value ";
        }
        
        
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }	
		
                $productDetailModel = new MsProductDetail();
		$productDetailModel->barcodeNumber = $model->barcodeNumbers;
		$productDetailModel->productID = $model->productID;
		$productDetailModel->uomID = $model->uomIDs;
		$productDetailModel->qty = str_replace(",",".",str_replace(".","",$model->qtys));
		$productDetailModel->buyPrice = str_replace(",",".",str_replace(".","",$model->standardFee));
		$productDetailModel->sellPrice = str_replace(",",".",str_replace(".","",$model->standardFee));
         
		if (!$productDetailModel->save()) {
			//print_r($productDetailModel->getErrors());
			$transaction->rollBack();
			return false;
		}
                
          
		
                
//		MsProductDetail::deleteAll('productID = :productID', [":productID" => $model->productID]);
//		
//		if (empty($model->joinProductDetail) || !is_array($model->joinProductDetail) || count($model->	joinProductDetail) < 1) {
//			$transaction->rollBack();
//			return false;
//		}
//
//                
//		foreach ($model->joinProductDetail as $productDetail) {
//			$productDetailModel = new MsProductDetail();
//			$productDetailModel->productID = $model->productID;
//			$productDetailModel->barcodeNumber = $productDetail['barcodeNumber'];
//			$productDetailModel->uomID = $productDetail['uomID'];
//			$productDetailModel->qty = str_replace(",",".",str_replace(".","",$productDetail['qty']));
//			$productDetailModel->buyPrice = str_replace(",",".",str_replace(".","",$productDetail['buyPrice']));
//			$productDetailModel->sellPrice = str_replace(",",".",str_replace(".","",$productDetail['sellPrice']));
//                        
////                        var_dump($productDetailModel);
////                        Yii::$app->end();
//                        
//			if (!$productDetailModel->save()) {
//				$transaction->rollBack();
//				return false;
//			}
//		}   
                
        $transaction->commit();
        return true;
    }
	
	  protected function updateModel($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
		
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }	
           
            $productDetailModel = MsProductDetail::findOne($model->barcodeNumbers);
            $productDetailModel->productID = $model->productID;
            $productDetailModel->uomID = $model->uomIDs;
            $productDetailModel->qty = $model->qtys;
            $productDetailModel->buyPrice = str_replace(",",".",str_replace(".","",$model->standardFee));
            $productDetailModel->sellPrice = str_replace(",",".",str_replace(".","",$model->standardFee));

            if (!$productDetailModel->save()) {
                    print_r($productDetailModel->getErrors());
                    $transaction->rollBack();
                    return false;
            }
                
		//MsProductDetail::deleteAll('productID = :productID', [":productID" => $model->productID]);
		
//		if (empty($model->joinProductDetail) || !is_array($model->joinProductDetail) || count($model->	joinProductDetail) < 1) {
//			$transaction->rollBack();
//			return false;
//		}
//
//                
//		foreach ($model->joinProductDetail as $productDetail) {
//			$productDetailModel = MsProductDetail::findOne($productDetail['barcodeNumber']);
//			if($productDetailModel == NULL){
//				$productDetailModel = new MsProductDetail();
//				$productDetailModel->barcodeNumber = $productDetail['barcodeNumber'];
//			}
//			$productDetailModel->productID = $model->productID;
//			$productDetailModel->uomID = $productDetail['uomID'];
//			$productDetailModel->qty = str_replace(",",".",str_replace(".","",$productDetail['qty']));
//			$productDetailModel->buyPrice = str_replace(",",".",str_replace(".","",$productDetail['buyPrice']));
//			$productDetailModel->sellPrice = str_replace(",",".",str_replace(".","",$productDetail['sellPrice']));
//                        
//                     // var_dump($productDetailModel);
//                        // Yii::$app->end();
//                        
//			if (!$productDetailModel->save()) {
//				$transaction->rollBack();
//				return false;
//			}
//		}   
                
        $transaction->commit();
        return true;
    }
}
