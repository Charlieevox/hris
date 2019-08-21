<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use app\models\MsPersonnelDivision;
use app\models\MsPersonnelDepartment;
use app\models\MsPersonnelPosition;
use app\models\MsPayrollJamsostek;
use app\models\MsBank;
use app\models\LkEducation;
use app\models\LkGender;
use app\models\MsSetting;
use app\models\LkCurrency;
use app\models\MsPayrollProrate;
use app\models\MsTaxLocation;
use kartik\widgets\DepDrop;
use app\models\MsAttendanceOvertime;
use app\models\MsAttendanceShift;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-head-form">
    <?php
    $form = ActiveForm::begin(['id' => 'mainForm', 'enableAjaxValidation' => true, 'options' => [
                    'enctype' => 'multipart/form-data',
                ],
    ]);
    ?>

    <?php
    $imageUrlKTP = \yii\helpers\Url::toRoute(['personnel-head/get-image', 'fileName' => $model->imageKTP]);
    $imageUrlNPWP = \yii\helpers\Url::toRoute(['personnel-head/get-image', 'fileName' => $model->imageNPWP]);
    $imageUrlPhoto = \yii\helpers\Url::toRoute(['personnel-head/get-image', 'fileName' => $model->imagePhoto]);
    $imageUrlUnknown = \yii\helpers\Url::toRoute(['personnel-head/get-image', 'fileName' => "unknown.jpg"]);
    $deleteUrlKTP = \yii\helpers\Url::toRoute(['personnel-head/remove-image', 'id' => $model->id, 'fileName' => $model->imageKTP, 'docName' => 'KTP']);
    $deleteUrlNPWP = \yii\helpers\Url::toRoute(['personnel-head/remove-image', 'id' => $model->id, 'fileName' => $model->imageNPWP, 'docName' => 'NPWP']);
    $deleteUrlPhoto = \yii\helpers\Url::toRoute(['personnel-head/remove-image', 'id' => $model->id, 'fileName' => $model->imagePhoto, 'docName' => 'PHOTO']);

    if ($model->imageKTP != NULL) {
        $initialPreviewKTP = [
            Html::img($imageUrlKTP, ['class' => 'file-preview-image', 'alt' => $model->imageKTP, 'title' => $model->imageKTP])
        ];
        $initialPreviewConfigKTP = [
            [
                'url' => $deleteUrlKTP,
                'key' => $model->imageKTP,
                'extra' => ['key' => $model->imageKTP],
            ]
        ];
    } else {
        $initialPreviewKTP = '';
        $initialPreviewConfigKTP = '';
    }

    if ($model->imageNPWP != NULL) {
        $initialPreviewNPWP = [
            Html::img($imageUrlNPWP, ['class' => 'file-preview-image', 'alt' => $model->imageNPWP, 'title' => $model->imageNPWP])
        ];
        $initialPreviewConfigNPWP = [
            [
                'url' => $deleteUrlNPWP,
                'key' => $model->imageNPWP,
                'extra' => ['key' => $model->imageNPWP],
            ]
        ];
    } else {
        $initialPreviewNPWP = '';
        $initialPreviewConfigNPWP = '';
    }

    if ($model->imagePhoto != NULL) {
        $initialPreviewPhoto = [
            Html::img($imageUrlPhoto, ['class' => 'file-preview-image', 'alt' => $model->imagePhoto, 'title' => $model->imagePhoto])
        ];
        $initialPreviewConfigPhoto = [
            [
                'url' => $deleteUrlPhoto,
                'key' => $model->imagePhoto,
                'extra' => ['key' => $model->imagePhoto],
            ]
        ];
    } else {
        $initialPreviewPhoto = '';
        $initialPreviewConfigPhoto = '';
    }



    $connection = Yii::$app->db;
    $sql = "select * from ms_company";
    $temp = $connection->createCommand($sql);
    $headResult = $temp->queryOne();
    ?>

    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <div class="row"> 
                <div class="col-md-6"> 
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="col-md-6"> 
					<div class="row"> 
						<div class="col-md-8">

						</div>	
						<div class="col-md-4">
							<?php
							if ($model->imagePhoto != NULL) {
								echo '<img src="' . $imageUrlPhoto . '" alt="Cover" class="file-preview-image pull-right" style="width:145px;height:126px;" >';
							} else {
								echo '<img src="' . $imageUrlUnknown . '" alt="Cover" class="file-preview-image pull-right" style="width:145px;height:126px;" >';
							}
							?> 
						</div>
					</div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="tabsControlStyling">
                <ul  id="myTab" class="nav nav-tabs nav-tabs-custom nav-justified tab-pane">
                    <li class="active"><a data-toggle="tab" href="#home" class="glyphicon glyphicon-user"> Profile</a></li>
                    <li><a data-toggle="tab" href="#menu1" class="glyphicon glyphicon-inbox"> Employment</a></li>
                    <li><a data-toggle="tab" href="#menu2" class="glyphicon glyphicon-tag"> Payment</a></li>
                    <li><a data-toggle="tab" href="#menu3" class="glyphicon glyphicon-folder-open"> BPJS</a></li>
                    <li><a data-toggle="tab" href="#menu4" class="glyphicon glyphicon-file"> NPWP</a></li>
                    <li><a data-toggle="tab" href="#menu5" class="glyphicon glyphicon-phone-alt"> Emergency</a></li>
                    <li><a data-toggle="tab" href="#menu6" class="glyphicon glyphicon-file"> Document</a></li>
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="information-form">
                            <div class="panel panel-default">
                                <div class="panel-heading"> <b> Information </b></div>
                                <div class="panel-body">
									<div class ="row">
										<div class="col-md-6">
											<?= $form->field($model, 'fullName')->textInput(['maxlength' => true, 'placeholder' => 'Enter First Name...']) ?>
										</div>

										<div class="col-md-6">
											 <?= $form->field($model, 'employeeNo')->textInput(['maxlength' => true, 'placeholder' => 'Enter Employee No']) ?>
										</div>	
										
									</div>
									
									<div class ="row">

									
										<div class="col-md-6">
											<?=
												$form->field($model, 'gender')
												->dropDownList(ArrayHelper::map(LkGender::find()
													->orderBy('id')->all(), 'id', 'description'), ['prompt' => 'Select ' . $model->getAttributeLabel('gender')])
                                            ?>
										</div>	
										
										<div class="col-md-6">
											<div class="row">
                                                <div class="col-md-4">
                                                    <?= $form->field($model, 'birthPlace')->textInput(['maxlength' => true, 'placeholder' => 'ex: Jakarta']) ?>
                                                </div>
                                                <div class="col-md-8">
                                                    <?=
                                                    $form->field($model, 'birthDate')->widget(DatePicker::className(),AppHelper::getDatePickerConfig ())
                                                    ?>			
                                                </div>    
                                            </div>
										</div>

									</div>									
									
									<div class ="row">
										
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													<?=
															$form->field($model, 'education')
															->dropDownList(ArrayHelper::map(LkEducation::find()
																			->orderBy('EducationId')->all(), 'educationId', 'educationDescription'), ['prompt' => 'Select ' . $model->getAttributeLabel('Education')])
													?>
												</div>	
												
												<div class="col-md-6">
													<?= $form->field($model, 'major')->textInput(['maxlength' => true, 'placeholder' => 'ex: Social']) ?>
												</div>	
													
											</div>
										</div>		
										
										<div class="col-md-6">
											 <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'ex: admin.web.com']) ?>
										</div>	
									</div>									
								
									<div class ="row">
										<div class="col-md-6">
											<?= $form->field($model, 'address')->textArea(['style' => 'padding-bottom: 2px !important; height: 100px !important;', 'rows' => '5', 'placeholder' => 'ex: Jalan Manokwari 10 No 15 Rt.002 Rw.008 Kec. Tanjung Pandan']) ?>   
										</div>

										<div class="col-md-6">
											<?= $form->field($model, 'city')->textInput(['maxlength' => true, 'placeholder' => 'ex: Belitung']) ?>
                                            <?=
                                                    $form->field($model, 'phoneNo')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
                                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                                        'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
                                                    ])
                                            ?>
										</div>										
									</div>	

									<div class ="row">
										<div class="col-md-6">
											<?php Pjax::begin(['id' => 'divdropdown']) ?>                           
                                            <?=
                                                    $form->field($model, 'divisionId', [
                                                        'addon' => [
                                                            'append' => [
                                                                'content' =>
                                                                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['personnel-division/browse'], [
                                                                    'type' => 'button',
                                                                    'title' => 'Add Division',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-target-width' => '375',
                                                                    'data-target-height' => '375',
                                                                    'data-target-value' => '.divHiddenInput',
                                                                    'class' => 'btn btn-primary WindowDialogBrowse'
                                                                ]) . ' ' .
                                                                Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['personnel-division/browse'], [
                                                                    'type' => 'button',
                                                                    'title' => 'Edit Division',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-filter-Input' => '.divdropdownclass',
                                                                    'data-target-width' => '375',
                                                                    'data-target-height' => '375',
                                                                    'data-target-value' => '.divHiddenInput',
                                                                    'class' => 'btn btn-primary WindowDialogBrowse btneditdiv'
                                                                ]),
                                                                'asButton' => true
                                                            ],
                                                        ]
                                                    ])
													->dropDownList(ArrayHelper::map(MsPersonnelDivision::findActive()->where('flagActive="1"')
                                                                    ->orderBy('divisionId')->all(), 'divisionId', 'description'), ['prompt' => 'Select Division', 'class' => 'divdropdownclass',
                                                        'onchange' => ''
                                                        . '$.post( "' . Yii::$app->urlManager->createUrl('personnel-head/lists?id=') . '"+$(this).val(), function( data ) {
														$( "select#description" ).html(data);
													});'
                                            ]);?>
                                            <?php Pjax::end() ?>
										</div>

										<div class="col-md-6">
											<?php Pjax::begin(['id' => 'depdropdown']) ?>
                                             <?=
                                                    $form->field($model, 'departmentId', [
                                                        'addon' => [
                                                            'append' => [
                                                                'content' =>
                                                                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['personnel-department/browse'], [
                                                                    'type' => 'button',
                                                                    'title' => 'Add Department',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-target-width' => '500',
                                                                    'data-target-height' => '550',
                                                                    'data-target-value' => '.depHiddenInput',
                                                                    'class' => 'btn btn-primary WindowDialogBrowse'
                                                                ]) . ' ' .
                                                                Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['personnel-department/browse'], [
                                                                    'type' => 'button',
                                                                    'title' => 'Edit Department',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-filter-Input' => '.depdropdownclass',
                                                                    'data-target-width' => '375',
                                                                    'data-target-height' => '375',
                                                                    'data-target-value' => '.depHiddenInput',
                                                                    'class' => 'btn btn-primary WindowDialogBrowse btneditdepartment'
                                                                ]),
                                                                'asButton' => true
                                                            ],
                                                        ]
                                                    ])
                                                    ->dropDownList(ArrayHelper::map(MsPersonnelDepartment::findActive()->where('flagActive="1"')
                                                                    ->orderBy('departmentCode')->all(), 'departmentCode', 'departmentDesc'), ['prompt' => 'Select Department', 'id' => 'description', 'class' => 'depdropdownclass'])
                                            ?>

                                            <?php Pjax::end() ?>
										</div>										
									</div>	
															
									<div class="row">
										<div class="col-md-6">
											 <?= $form->field($model, 'idNo')->textInput(['maxlength' => true, 'placeholder' => 'ex: admin.web.com']) ?>
										</div>	

										<div class="col-md-6">
											<?=
												$form->field($model, 'locationID')
												->dropDownList(ArrayHelper::map(MsSetting::find()
																->where('key1="Area"')->all(), 'value1', 'key2'), ['prompt' => 'Select ' . $model->getAttributeLabel('locationID')])
											?>
										</div>	
									</div>
								
                                </div>
                            </div>
                        </div> 
						<div class="panel-footer">
							<div class="pull-right">
								<a class='btn btn-success btnNextHome' href='#'><i class='glyphicon glyphicon-step-forward'></i>&nbsp;Next</a>
							</div>
							<div class="clearfix"></div> 
						</div>
                    </div>

                    <div id="menu1" class="tab-pane fade">
                        <div class="contract-form">
                            <div class="panel panel-default">
                                <div class="panel-heading"><b>Employment</b></div>
                                <div class="panel-body">
                                    <div class="row" id="familydetail">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="table-responsive">
													<?php Pjax::begin(['id' => 'posdetail']);
														$connection = Yii::$app->db;
														$sql = "SELECT id,positionDescription as'text' FROM ms_personnelposition";
														$temp = $connection->createCommand($sql);
														$positionDetailPjax = $temp->queryAll();
														
														$positionDetailPjax = \yii\helpers\Json::encode($positionDetailPjax);
														
													
													?>
													
													
                                                    <table class="table table-bordered Contract-Detail-Table" style="border-collapse: inherit;">
                                                        <thead>
                                                            <tr>
																<th style="width: 15%;">Start Working</th>
                                                                <th style="width: 15%;">Start Date</th>
                                                                <th style="width: 15%;">End Date</th>
                                                                <th style="width: 18%;">Agreement No</th>
																<th style="width: 20%;">Status</th>
																<th style="width: 20%;"><span style='vertical-align: bottom;'>Position</span>
																<?=
																Html::a('<i class="glyphicon glyphicon-plus"></i>', ['personnel-position/addbrowse'], [
																	'type' => 'button',
																	'title' => 'Add Position',
																	'data-toggle' => 'tooltip',
																	'data-target-width' => '400',
																	'data-target-height' => '400',
																	'data-target-value' => '.posHiddenInput',
																	'class' => 'btn btn-xs pull-right btn-primary WindowDialogBrowse'
																]);
																?>
																</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="actionBody">
															<?= Html::hiddenInput('MsPersonnelHead[joinPersonnelContract][0][startWorking]', '', ['class' => 'startWorking-hidden']) ?>
                                                            <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelContract][0][startContract]', '', ['class' => 'StartContract-hidden']) ?>
                                                            <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelContract][0][endContract]', '', ['class' => 'EndContract-hidden']) ?>
                                                            <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelContract][0][docNo]', '', ['class' => 'DocNo-hidden']) ?>
															<?= Html::hiddenInput('MsPersonnelHead[joinPersonnelContract][0][status]', '', ['class' => 'status-hidden']) ?>
															<?= Html::hiddenInput('MsPersonnelHead[joinPersonnelContract][0][position]', '', ['class' => 'position-hidden']) ?>
                                                        </tbody>

                                                        <tfoot class="table-detail">
                                                            <tr>  
																<td class="td-input">
                                                                    <?=
                                                                    DatePicker::widget([
                                                                        'removeButton' => false,
                                                                        'name' => 'startWorking',
                                                                        'options' => ['class' => 'form-control actionStartWorking', 'placeholder' => 'ex: 01-01-2016'],
                                                                        'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                                    ]);
                                                                    ?>
                                                                </td> 															
                                                                <td class="td-input">
                                                                    <?=
                                                                    DatePicker::widget([
                                                                        'removeButton' => false,
                                                                        'name' => 'startContract',
                                                                        'options' => ['class' => 'form-control actionStartContract', 'placeholder' => 'ex: 01-01-2016'],
                                                                        'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                                    ]);
                                                                    ?>
                                                                </td> 
                                                                <td class="td-input">
                                                                    <?=
                                                                    DatePicker::widget([
                                                                        'removeButton' => false,
                                                                        'name' => 'endContract',
                                                                        'options' => ['class' => 'form-control actionEndContract', 'placeholder' => 'ex: 01-01-2016'],
                                                                        'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                                    ]);
                                                                    ?>
                                                                </td> 
                                                                <td class="td-input">
                                                                    <?=
                                                                    Html::textInput('docNo', '', [
                                                                        'class' => 'form-control actionDocNo',
                                                                        'maxlength' => 50, 'placeholder' => 'ex. HRD/2016/01-0001'
                                                                    ])
                                                                    ?>
                                                                </td>   
																<td class="td-input">
																<?=
																	Html ::dropDownList('status', '', ArrayHelper::map(MsSetting ::find()->where('key1="Status"')->all(), 'value1', 'key2'), 
																		[
																		'class' => 'form-control actionStatus', 
																		'prompt' => 'Select Status'])
																?>
																</td>
																
																<td class="td-input">
																	<?=
																		Html ::dropDownList('position', '',ArrayHelper::map(MsPersonnelPosition::find()->where('flagActive="1"')
                                                                            ->orderBy('positionDescription')->all(), 'id', 'positionDescription'), 
																			[
																			'class' => 'form-control actionPosition', 
																			'prompt' => 'Select Position'])
																	?>
																</td>
																
                                                                <td class="td-input text-center">
                                                                    <?= Html::a('<i class="glyphicon glyphicon-plus">&nbsp;Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
													<?php Pjax::end() ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>
						<div class="panel-footer">
							<div class="pull-right">
								<a class='btn btn-success btnNext1' href='#'><i class='glyphicon glyphicon-step-forward'></i>&nbsp;Next</a>
							</div>
							<div class="clearfix"></div> 
						</div>
                    </div>

                    <div id="menu2" class="tab-pane fade">
                        <div class="payment-form">
                            <div class="panel panel-default">
                                <div class="panel-heading"><b>Payment</b></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?=
                                                    Html::hiddenInput('bankHiddenInput', '', [
                                                        'class' => 'form-control bankHiddenInput'
                                                    ])
                                                    ?>
                                                    <?=
                                                    Html::hiddenInput('divHiddenInput', '', [
                                                        'class' => 'form-control divHiddenInput'
                                                    ])
                                                    ?>
                                                    <?=
                                                    Html::hiddenInput('depHiddenInput', '', [
                                                        'class' => 'form-control depHiddenInput'
                                                    ])
                                                    ?>
                                                    <?=
                                                    Html::hiddenInput('posHiddenInput', '', [
														'id' => 'posHiddenInput',
                                                        'class' => 'form-control posHiddenInput'
                                                    ])
                                                    ?>
													
													<?=
                                                    Html::hiddenInput('posHiddenDetail', '', [
														'id' => 'posHiddenDetail',
                                                        'class' => 'form-control posHiddenInput'
                                                    ])
                                                    ?>
													
													<?=
                                                    Html::hiddenInput('taxHiddenInput', '', [
                                                        'class' => 'form-control taxHiddenInput'
                                                    ])
                                                    ?>
													<?=
                                                    Html::hiddenInput('jamsostekHiddenInput', '', [
                                                        'class' => 'form-control jamsostekHiddenInput'
                                                    ])
                                                    ?>
													
                                                    <?=
                                                            $form->field($model, 'paymentMethod')
                                                           ->dropDownList(ArrayHelper::map(MsSetting::find()
                                                                    ->where('key1="paymentMethod"')->all(), 'value1', 'key2'), ['prompt' => 'Select ' . $model->getAttributeLabel('paymentMethod')])
                                                    ?>

                                                </div>


                                                <div class="col-md-6">
                                                    <?=
                                                            $form->field($model, 'curency')
                                                            ->dropDownList(ArrayHelper::map(LkCurrency::find()
                                                                            ->orderBy('currencyName')->all(), 'currencyID', 'currencyName'), ['prompt' => 'Select ' . $model->getAttributeLabel('curency')])
                                                    ?>
                                                </div>

                                            </div>
											<div class="row">
												<div class= "col-md-6">
													
                                                    <?php Pjax::begin(['id' => 'bankdropdown']) ?>
                                                    <?=
                                                            $form->field($model, 'bankName', [
                                                                'addon' => [
                                                                    'append' => [
                                                                        'content' =>
                                                                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['bank/browse'], [
                                                                            'type' => 'button',
                                                                            'title' => 'Add Bank',
                                                                            'data-toggle' => 'tooltip',
                                                                            'data-target-width' => '375',
                                                                            'data-target-height' => '375',
                                                                            'data-target-value' => '.bankHiddenInput',
                                                                            'class' => 'btn btn-primary WindowDialogBrowse'
                                                                        ]) . ' ' .
                                                                        Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['bank/browse'], [
                                                                            'type' => 'button',
                                                                            'title' => 'Edit Bank',
                                                                            'data-toggle' => 'tooltip',
                                                                            'data-filter-Input' => '.bankdropdownclass',
                                                                            'data-target-width' => '375',
                                                                            'data-target-height' => '375',
                                                                            'data-target-value' => '.bankHiddenInput',
                                                                            'class' => 'btn btn-primary WindowDialogBrowse btneditbank'
                                                                        ]),
                                                                        'asButton' => true
                                                                    ],
                                                                ]
                                                            ])
                                                            ->dropDownList(ArrayHelper::map(MsBank::find()->where('flagActive="1"')
                                                                            ->orderBy('bankId')->all(), 'bankId', 'bankId'), ['prompt' => 'Select ' . $model->getAttributeLabel('bankName'), 'class' => 'bankdropdownclass',
                                                                'onchange' => ''
                                                                . '$.post( "' . Yii::$app->urlManager->createUrl('personnel-head/bankdescription?id=') . '"+$(this).val(), function( data ) {
													$("#mspersonnelhead-bankdetail" ).val(data);
													});'])
                                                    ?>
													<?php Pjax::end() ?>
													</div>
													<div class= "col-md-6">
														<?= $form->field($model, 'bankDetail')->textInput(['maxlength' => true, 'id' => 'mspersonnelhead-bankdetail', 'readonly' => 'true']) ?>
													</div>
											</div>
										</div>
                                        <div class="col-md-6">
											<div class="row">
												<div class="col-md-6">
													<?=
															$form->field($model, 'bankNo')->textInput(['maxlength' => true, 'placeholder' => 'ex: 1123141231'])
															->widget(\yii\widgets\MaskedInput::classname(), [
																'mask' => '9',
																'clientOptions' => ['repeat' => 15, 'greedy' => false]
															])
													?>
												</div>
												<div class="col-md-6">
													<?= $form->field($model, 'swiftCode')->textInput(['maxlength' => true, 'placeholder' => 'ex: CENAIDJA']) ?>

												
												</div>
											</div>
											
											
                                            <?= $form->field($model, 'branch')->textInput(['maxlength' => true, 'placeholder' => 'ex: KC Tangerang Kota']) ?>

                                        </div>
                                    </div>
                                </div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading"> <b> Calculation </b></div>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-6">
											<?=
													$form->field($model, 'prorateSetting')
													->dropDownList(ArrayHelper::map(MsPayrollProrate::find()
																	->orderBy('prorateId')->all(), 'prorateId', 'prorateId'), ['prompt' => 'Select ' . $model->getAttributeLabel('prorateSetting')])
											?>
											
										</div>
										<div class="col-md-6">
											<?=
													$form->field($model, 'taxSetting')
													->dropDownList(ArrayHelper::map(MsSetting::find()
																	->where('key1="TaxParm"')
																	->orderBy('value1')->all(), 'value1', 'key2'), ['prompt' => 'Select ' . $model->getAttributeLabel('taxSetting')])
											?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<?=
													$form->field($model, 'overtimeId')
													->dropDownList(ArrayHelper::map(MsAttendanceOvertime::find()
																	->orderBy('overtimeId')->all(), 'overtimeId', 'overtimeId'), ['prompt' => 'Select ' . $model->getAttributeLabel('overtimeId')])
											?>
										</div>

										<div class="col-md-6">
											<?=
													$form->field($model, 'shiftCode')
													->dropDownList(ArrayHelper::map(MsAttendanceShift::find()
																	->orderBy('shiftCode')->all(), 'shiftCode', 'shiftCode'), ['prompt' => 'Select ' . $model->getAttributeLabel('shiftCode')])
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<div class="pull-right">
								<a class='btn btn-success btnNext2' href='#'><i class='glyphicon glyphicon-step-forward'></i>&nbsp;Next</a>
							</div>
							<div class="clearfix"></div> 
						</div>
                    </div>

                    <div id="menu3" class="tab-pane fade">
                        <div class="bpjs-form">
                            <div class="panel panel-default">
                                <div class="panel-heading"><b>BPJS</b></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
											<?php Pjax::begin(['id' => 'jamsostekdropdown']) ?>
												<?=
													$form->field($model, 'jamsostekParm',
													[
														'addon' => [
															'append' => [
																'content' =>
																Html::a('<i class="glyphicon glyphicon-plus"></i>', ['payroll-jamsostek/browse'], [
																	'type' => 'button',
																	'title' => 'Add Jamsostek',
																	'data-toggle' => 'tooltip',
																	'data-target-width' => '1036',
																	'data-target-height' => '567',
																	'data-target-value' => '.jamsostekHiddenInput',
																	'class' => 'btn btn-primary WindowDialogBrowse'
																]) . ' ' .
																Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['payroll-jamsostek/browse'], [
																	'type' => 'button',
																	'title' => 'Edit Jamsostek',
																	'data-toggle' => 'tooltip',
																	'data-filter-Input' => '.jamsostekdropdownclass',
																	'data-target-width' => '1036',
																	'data-target-height' => '567',
																	'data-target-value' => '.jamsostekHiddenInput',
																	'class' => 'btn btn-primary WindowDialogBrowse btneditjamsostek'
																]),
																'asButton' => true
															],
														]
													])
													->dropDownList(ArrayHelper::map(MsPayrollJamsostek::find()
													->orderBy('jamsostekCode')->all(), 'jamsostekCode', 'jamsostekCode'), ['prompt' => 'Select ' . $model->getAttributeLabel('jamsostekParm'),'class' => 'jamsostekdropdownclass'])
												?>
											<?php Pjax::end() ?>
                                        </div>

                                        <div class="col-md-6">
                                            <?=
                                                    $form->field($model, 'bpjskNo')->textInput(['maxlength' => true, 'placeholder' => 'ex: 7371000000000004'])
                                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                                        'mask' => '9',
                                                        'clientOptions' => ['repeat' => 15, 'greedy' => false]
                                                    ])
                                            ?> 
                                            <?=
                                                    $form->field($model, 'bpkstkNo')->textInput(['maxlength' => true, 'placeholder' => 'ex: 0001311223344'])
                                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                                        'mask' => '9',
                                                        'clientOptions' => ['repeat' => 15, 'greedy' => false]
                                                    ])
                                            ?> 

                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><b>Fill based on Family Identity Card</b></div>
                                        <div class="panel-body">
                                            <div class="row" id="familydetail">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered Family-Detail-Table" style="border-collapse: inherit;">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 15%;">First Name</th>
                                                                        <th style="width: 15%;">Last Name</th>
                                                                        <th style="width: 15%;">Relationship</th>
                                                                        <th style="width: 15%;">ID Number</th>
                                                                        <th style="width: 15%;">Birth Place</th>
                                                                        <th style="width: 15%;">Birth Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="actionBody">
                                                                    <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelfamily][0][firstName]', '', ['class' => 'firstName-hidden']) ?>
                                                                    <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelfamily][0][lastName]', '', ['class' => 'lastName-hidden']) ?>
                                                                    <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelfamily][0][relationship]', '', ['class' => 'relationship-hidden']) ?>
                                                                    <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelfamily][0][idNumber]', '', ['class' => 'idNumber-hidden']) ?>
                                                                    <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelfamily][0][birthPlace]', '', ['class' => 'birthPlace-hidden']) ?>
                                                                    <?= Html::hiddenInput('MsPersonnelHead[joinPersonnelfamily][0][birthDate]', '', ['class' => 'birthDate-hidden']) ?>
                                                                </tbody>

                                                                <tfoot class="table-detail">
                                                                    <tr>
                                                                        <td class="td-input">
                                                                            <?=
                                                                            Html::textInput('firstName', '', [
                                                                                'class' => 'form-control firstName-1',
                                                                                'maxlength' => 50, 'placeholder' => 'ex. Gaby'
                                                                            ])
                                                                            ?>
                                                                        </td>
                                                                        <td class="td-input">
                                                                            <?=
                                                                            Html::textInput('lastName', '', [
                                                                                'class' => 'form-control lastName-1',
                                                                                'maxlength' => 50, 'placeholder' => 'ex. Gaby'
                                                                            ])
                                                                            ?>
                                                                        </td>
                                                                        <td class="td-input">
                                                                            <?=
                                                                            Html::textInput('relationship', '', [
                                                                                'class' => 'form-control relationship-1',
                                                                                'maxlength' => 50, 'placeholder' => 'ex. Wife/Child'
                                                                            ])
                                                                            ?>
                                                                        </td>                                                
                                                                        <td class="td-input">
                                                                            <?=
                                                                            Html::textInput('idNumber', '', [
                                                                                'class' => 'form-control idNumber-1',
                                                                                'maxlength' => 50, 'placeholder' => 'ex. 311111111'
                                                                            ])
                                                                            ?>
                                                                        </td>
                                                                        <td class="td-input">
                                                                            <?=
                                                                            Html::textInput('birthPlace', '', [
                                                                                'class' => 'form-control birthPlace-1',
                                                                                'maxlength' => 50, 'placeholder' => 'ex. Jakarta'
                                                                            ])
                                                                            ?>
                                                                        </td>                                            
                                                                        <td class="td-input">
                                                                            <?=
                                                                            DatePicker::widget([
                                                                                'removeButton' => false,
                                                                                'name' => 'birthDate',
                                                                                'options' => ['class' => 'form-control birthDate-1', 'placeholder' => 'ex: 01-01-1990'],
                                                                                'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                                            ]);
                                                                            ?>
                                                                        </td>   
                                                                        <td class="td-input text-center">
                                                                            <?= Html::a('<i class="glyphicon glyphicon-plus">&nbsp;Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="panel-footer">
							<div class="pull-right">
								<a class='btn btn-success btnNext3' href='#'><i class='glyphicon glyphicon-step-forward'></i>&nbsp;Next</a>
							</div>
							<div class="clearfix"></div> 
						</div>
                    </div>

                    <div id="menu4" class="tab-pane fade">
                        <div class="npwp-form">
                            <div class="panel panel-default">
                                <div class="panel-heading"><b>NPWP Information</b></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'npwpName')->textInput(['maxlength' => true, 'placeholder' => 'Enter Last Name...']) ?>
                                            <?= $form->field($model, 'npwpAddress')->textArea(['style' => 'padding-bottom: 2px !important;', 'rows' => '5', 'placeholder' => 'ex: Jalan Manokwari 10 No 15 Rt.002 Rw.008 Kec. Tanjung Pandan']) ?>
                                            <a class='btn btn-warning  btnSameAsId pull-right' href='#'><i class='glyphicon glyphicon-refresh'></i> Same As Id</a>
                                        </div>

                                        <div class="col-md-6">
                                            <?=
                                                    $form->field($model, 'npwpNo')->textInput(['maxlength' => true, 'placeholder' => 'ex: 02.414.520.3-056.000'])
                                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                                        'mask' => '9[9].9[9][9].9[9][9].9-[9][9][9].9[9][9]',
                                                        'class' => 'npwp',
                                                    ])
                                            ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?=
                                                            $form->field($model, 'maritalStatus')
                                                            ->dropDownList(ArrayHelper::map(MsSetting::find()
                                                                            ->where('key1="MaritalStatus"')->all(), 'value1', 'key2'), ['prompt' => 'Select ' . $model->getAttributeLabel('maritalStatus')])
                                                    ?>
                                                </div>
                                                <div class="col-md-6">
												<?=
                                                    $form->field($model, 'dependent')->textInput(['maxlength' => true, 'placeholder' => 'ex: 3'])
                                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                                        'mask' => '9',
                                                        'clientOptions' => ['repeat' => 1, 'greedy' => false]
                                                    ])
                                            ?> 
                                                </div>    
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?=
                                                            $form->field($model, 'nationality')
                                                            ->dropDownList(ArrayHelper::map(MsSetting::find()
                                                                            ->where('key1="Nationality"')->all(), 'value1', 'key2'), ['prompt' => 'Select ' . $model->getAttributeLabel('nationality')])
                                                    ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => 'ex: Indonesia']) ?>
                                                </div>    
                                            </div>
											<?php Pjax::begin(['id' => 'taxdropdown']) ?>
                                            <?=
                                                    $form->field($model, 'taxId',
													[
                                                                'addon' => [
                                                                    'append' => [
                                                                        'content' =>
                                                                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['tax-location/browse'], [
                                                                            'type' => 'button',
                                                                            'title' => 'Add Tax Location',
                                                                            'data-toggle' => 'tooltip',
                                                                            'data-target-width' => '1140',
                                                                            'data-target-height' => '584',
                                                                            'data-target-value' => '.taxHiddenInput',
                                                                            'class' => 'btn btn-primary WindowDialogBrowse'
                                                                        ]) . ' ' .
                                                                        Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['tax-location/browse'], [
                                                                            'type' => 'button',
                                                                            'title' => 'Edit Tax Location',
                                                                            'data-toggle' => 'tooltip',
                                                                            'data-filter-Input' => '.taxdropdownclass',
                                                                            'data-target-width' => '1140',
                                                                            'data-target-height' => '584',
                                                                            'data-target-value' => '.taxHiddenInput',
                                                                            'class' => 'btn btn-primary WindowDialogBrowse btneditTax'
                                                                        ]),
                                                                        'asButton' => true
                                                                    ],
                                                                ]
                                                            ])
                                                    ->dropDownList(ArrayHelper::map(MsTaxLocation::find()->where('flagActive="1"')
                                                                    ->orderBy('id')->all(), 'id', 'officeName'), ['prompt' => 'Select ' . $model->getAttributeLabel('taxId'),'class' => 'taxdropdownclass'])
                                            ?>
											<?php Pjax::end() ?>
											
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="panel-footer">
							<div class="pull-right">
								<a class='btn btn-success btnNext4' href='#'><i class='glyphicon glyphicon-step-forward'></i>&nbsp;Next</a>
							</div>
							<div class="clearfix"></div> 
						</div>
                    </div>

                    <div id="menu5" class="tab-pane fade">
                        <div class="emergencycall-form">
                            <div class="panel panel-default">
                                <div class="panel-heading"><b>Contact Person in case of emergency</b></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'ecFirstName')->textInput(['maxlength' => true, 'placeholder' => 'Enter First Name...']) ?>
                                            <?= $form->field($model, 'ecLastName')->textInput(['maxlength' => true, 'placeholder' => 'Enter Last Name...']) ?>

                                        </div>

                                        <div class="col-md-6">
                                            <?= $form->field($model, 'ecRelationShip')->textInput(['maxlength' => true, 'placeholder' => 'ex: wife...']) ?>
                                            <?=
                                                    $form->field($model, 'ecPhone1')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
                                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                                        'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
                                                    ])
                                            ?>
                                            <?=
                                                    $form->field($model, 'ecPhone2')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
                                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                                        'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
                                                    ])
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="panel-footer">
							<div class="pull-right">
								<a class='btn btn-success btnNext5' href='#'><i class='glyphicon glyphicon-step-forward'></i>&nbsp;Next</a>
							</div>
							<div class="clearfix"></div> 
						</div>
                    </div>

                    <div id="menu6" class="tab-pane fade">
                        <div class="document-form">
                            <div class="panel panel-default">
                                <div class="panel-heading"><b>Identity Card</b></div>
                                <div class="panel-body">
									<div class = "actionImageKTP">
										<?= Html::activeHiddenInput($model, 'imageGalleryKTPMode', ['class' => 'imageGalleryKTPMode']) ?>
										<?=
										$form->field($model, 'imageGalleryKTP')->widget(\kartik\file\FileInput::classname(), [
											'options' => [
												'accept' => 'image/*',
												'class' => 'imageGalleryKTP',
											],
											'pluginOptions' => [
												'removeLabel' => 'Hapus',
												'cancelLabel' => 'Batal',
												'showUpload' => false,
												'showCancel' => false,
												'showRemove' => true,
												'showCaption' => false,
												'initialPreview' => $initialPreviewKTP,
												'layoutTemplates' => [
													'main1' => '{preview}' .
													'<div class="kv-upload-progress hide"></div>' .
													'<div class="input-group {class}">' .
													'   {caption}' .
													'   <div class="input-group-btn">' .
													'       {browse}' .
													'   </div>' .
													'</div>',
													'preview' => '<div class="file-preview {class}">' .
													'    <div class="{dropClass}">' .
													'    <div class="file-preview-thumbnails">' .
													'    </div>' .
													'    <div class="clearfix"></div>' .
													'    <div class="file-preview-status text-center text-success"></div>' .
													'    <div class="kv-fileinput-error"></div>' .
													'    </div>' .
													'</div>']
											],
										])->hint('Rekomendasi Ukuran: 268 x 268 px')
										?>
									</div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading"><b>Photo</b></div>
                                <div class="panel-body">
									<div class = "actionImagePhoto">
										<?= Html::activeHiddenInput($model, 'imageGalleryPhotoMode', ['class' => 'imageGalleryPhotoMode']) ?>
										<?=
										$form->field($model, 'imageGalleryPhoto')->widget(\kartik\file\FileInput::classname(), [
											'options' => [
												'accept' => '*',
												'class' => 'imageGalleryPhoto',
											],
											'pluginOptions' => [
												'removeLabel' => 'Hapus',
												'cancelLabel' => 'Batal',
												'showUpload' => false,
												'showCancel' => false,
												'showRemove' => true,
												'showCaption' => false,
												'initialPreview' => $initialPreviewPhoto,
												'layoutTemplates' => [
													'main1' => '{preview}' .
													'<div class="kv-upload-progress hide"></div>' .
													'<div class="input-group {class}">' .
													'   {caption}' .
													'   <div class="input-group-btn">' .
													'       {browse}' .
													'   </div>' .
													'</div>',
													'preview' => '<div class="file-preview {class}">' .
													'    <div class="{dropClass}">' .
													'    <div class="file-preview-thumbnails">' .
													'    </div>' .
													'    <div class="clearfix"></div>' .
													'    <div class="file-preview-status text-center text-success"></div>' .
													'    <div class="kv-fileinput-error"></div>' .
													'    </div>' .
													'</div>']
											],
										])->hint('Rekomendasi Ukuran: 268 x 268 px')
										?>
									</div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading"><b>NPWP</b></div>
                                <div class="panel-body">
									<div class = "actionImageNpwp">
										<?= Html::activeHiddenInput($model, 'imageGalleryNPWPMode', ['class' => 'imageGalleryNPWPMode']) ?>
										<?=
										$form->field($model, 'imageGalleryNPWP')->widget(\kartik\file\FileInput::classname(), [
											'options' => [
												'accept' => 'image/*',
												'class' => 'imageGalleryNPWP',
											],
											'pluginOptions' => [
												'removeLabel' => 'Hapus',
												'cancelLabel' => 'Batal',
												'showUpload' => false,
												'showCancel' => false,
												'showRemove' => true,
												'showCaption' => false,
												'initialPreview' => $initialPreviewNPWP,
												'layoutTemplates' => [
													'main1' => '{preview}' .
													'<div class="kv-upload-progress hide"></div>' .
													'<div class="input-group {class}">' .
													'   {caption}' .
													'   <div class="input-group-btn">' .
													'       {browse}' .
													'   </div>' .
													'</div>',
													'preview' => '<div class="file-preview {class}">' .
													'    <div class="{dropClass}">' .
													'    <div class="file-preview-thumbnails">' .
													'    </div>' .
													'    <div class="clearfix"></div>' .
													'    <div class="file-preview-status text-center text-success"></div>' .
													'    <div class="kv-fileinput-error"></div>' .
													'    </div>' .
													'</div>']
											],
										])->hint('Rekomendasi Ukuran: 268 x 268 px')
										?>
									</div>	
                                </div>
                            </div>
                        </div>
						<div class="panel-footer">
							<div class="pull-right">
								<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
							</div>
							<div class="clearfix"></div> 
						</div>  
                    </div>
                </div>
            </div>
        </div>
</div>
<?php ActiveForm::end(); ?>
</div>
<?php
$familyDetail = \yii\helpers\Json::encode($model->joinPersonnelfamily);
$contractDetail = \yii\helpers\Json::encode($model->joinPersonnelContract);
$positionDetail = \yii\helpers\Json::encode($model->joinPersonnelPosition);
$statusDetail = \yii\helpers\Json::encode($model->joinPersonnelStatus);
$url_position = Yii::$app->urlManager->createUrl('personnel-head/get-position');
$getDepAjaxURL = Yii::$app->request->baseUrl. '/personnel-head/lists';
$deleteRow = '';
if (!isset($isView)) {
    $deleteRow = <<< DELETEROW
			"   <td class='text-center'>" +
			"       <a class='btn btn-danger btn-sm btnDelete' href='#'><i class='glyphicon glyphicon-remove'></i>Delete</a>" +
			"   </td>" +
DELETEROW;
}

$js = <<< SCRIPT
        
$(document).ready(function () {
			
		var dataPosition = $positionDetail;
		var dataStatus = $statusDetail;
        var initValue = $familyDetail;
        var rowTemplate = "" +
        "<tr>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='firstName form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][firstName]' data-key='{{Count}}' value='{{firstName}}'" +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='lastName form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][lastName]' value='{{lastName}}'  " +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='relationship form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][relationship]' value='{{relationship}}'  " +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='idNumber form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][idNumber]' value='{{idNumber}}' " +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='birthPlace form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][birthPlace]' value='{{birthPlace}}' " +
        "   </td>" +
		"   <td class='text-left'>" +
		"	<div class='input-group date'>" +
		"			<div class='input-group-addon'>" +
        "				<span class='glyphicon glyphicon-calendar'></span>" +
		"			</div>" +
		"		<input id='datepicker1' type='text' class='birthDate form-control datepicker datepickerBirthDay' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][birthDate]' value='{{birthDate}}'>" +
		"	</div>" +
		"   </td>" +
        $deleteRow
        "</tr>";
						        
        if (initValue != null) {
            initValue.forEach(function(entry) {
            addRow(entry.firstName.toString(), entry.lastName.toString(),entry.relationship.toString(),entry.idNumber.toString(),entry.birthPlace.toString(),entry.birthDate.toString());
            });
        }
		
        $('.Family-Detail-Table .btnAdd').on('click', function (e) {
        e.preventDefault();
        var actionfirstName = $('.firstName-1').val();
        var actionlastName = $('.lastName-1').val();
        var actionrelationship = $('.relationship-1').val();
        var actionidNumber =  $('.idNumber-1').val();
        var actionbirthPlace =  $('.birthPlace-1').val();
        var actionbirthDate = $('.birthDate-1').val();

        if(actionfirstName=="" || actionfirstName==undefined){
        console.log(actionfirstName);
            bootbox.alert("Fill Name");
            return false;
        }

        if(actionrelationship=="" || actionrelationship==undefined){
            bootbox.alert("Fill Relationship");
            return false;
        }   
        
        addRow(actionfirstName, actionlastName,actionrelationship,actionidNumber,actionbirthPlace,actionbirthDate);
            $('.firstName-1').val('');
            $('.lastName-1').val('');
            $('.relationship-1').val('');
            $('.idNumber-1').val('');
            $('.birthPlace-1').val('');
            $('.birthDate-1').val('');

        });
        
        function addRow(actionfirstName, actionlastName,actionrelationship,actionidNumber,actionbirthPlace,actionbirthDate){
            var template = rowTemplate;
            template = replaceAll(template, '{{firstName}}', actionfirstName);
            template = replaceAll(template, '{{lastName}}', actionlastName);
            template = replaceAll(template, '{{relationship}}', actionrelationship);
            template = replaceAll(template, '{{idNumber}}', actionidNumber);
            template = replaceAll(template, '{{birthPlace}}', actionbirthPlace);
            template = replaceAll(template, '{{birthDate}}', actionbirthDate);        
            template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
            $('.Family-Detail-Table .actionBody').append(template);  

			$('.datepickerBirthDay').kvDatepicker({
				autoclose: true,
				format: 'dd-mm-yyyy'
			})			
		}
	
		function getMaximumCounter() {
				var maximum = 0;
				 $('.firstName').each(function(){
						value = parseInt($(this).attr('data-key'));
						if(value > maximum){
								maximum = value;
						}
				});
				return maximum;
		}

		function replaceAll(string, find, replace) {
			return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
		}

		function escapeRegExp(string) {
			return string.replace(/([.*+?^=!:\${}()|\[\]\/\\\\])/g, "\\\\$1");
		}
			
			$('.Family-Detail-Table').on('click', '.btnDelete', function (e) {
				var self = this;
				e.preventDefault();
				yii.confirm('Are you sure you want to delete this data ?',deleteRow);
				function deleteRow(){
				$(self).parents('tr').remove();
				var countData = $('.Family-Detail-Table tbody tr').length;
				console.log(countData);
			}
			
		});
          
        
        
//CONTRACT GRID VIEW
		var initValue2 = $contractDetail;
		var rowTemplate2 = "" +
        "<tr class='tr-action'>" +
		"   <td class='text-left'>" +
		"		<div class='input-group date'>" +
		"			<div class='input-group-addon'>" +
        "				<span class='glyphicon glyphicon-calendar'></span>" +
		"			</div>" +
		"			<input id='datepickerStartWorking' data-key='{{Count}}' type='' class='startWorking form-control datepickerStartWorking' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][startWorking]' value='{{startWorking}}'>" +
		"		</div>" +	
		"   </td>" +
		"   <td class='text-left'>" +
		"		<div class='input-group date'>" +
		"			<div class='input-group-addon'>" +
        "				<span class='glyphicon glyphicon-calendar'></span>" +
		"			</div>" +
		"			<input id='datepickerStartContract' data-key='{{Count}}' type='' class='startDate form-control datepickerStartContract' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][startContract]' value='{{startContract}}'>" +
		"		</div>" +	
		"   </td>" +
		"   <td class='text-left'>" +
		"		<div class='input-group date'>" +
		"				<div class='input-group-addon'>" +
        "					<span class='glyphicon glyphicon-calendar'></span>" +
		"				</div>" +
		"			<input id='datepickerEndContract' type='text' class='endDate form-control datepickerEndContract' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][endContract]' value='{{endContract}}'>" +
		"		</div>" +
		"   </td>" +
        "   <td class='text-left'>" +
        "       <input type='' class='docNo form-control' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][docNo]' value='{{docNo}}' > " +
        "   </td>" +
		"   <td class='text-left'>" +
		"		<div class=''>" +
        "       	<select class='js-example-data-array-status' style='width: 100%;' class='status form-control' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][status]' value='{{status}}'> " +
		"				<option value='{{status}}' selected='selected'>{{actionstatusDescription}}</option> " +		
		"			</select> " +
		"		</div> "+
        "   </td>" +
		"   <td class='text-left'>" +
		"		<div class=''>" +
        "       	<select class='js-example-data-array-selected' style='width: 100%;'  class='status form-control' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][position]' value='{{position}}'> " +
		"				<option value='{{position}}' selected='selected'>{{actionPositionDescription}}</option> " +
		"			</select> " +
		"		</div> "+
        "   </td>" +
        $deleteRow
        "</tr>";

        if (initValue2 != null) {
            initValue2.forEach(function(entry) {
			addRow2(entry.startWorking.toString(),entry.startContract.toString(), entry.endContract.toString(),entry.docNo.toString(),entry.status.toString(),'',entry.position.toString(),'');
            });
        }
 
        
        
    $('.Contract-Detail-Table .btnAdd').on('click', function (e) {
        e.preventDefault();
		var actionStartWorking= $('.actionStartWorking').val();
        var actionStartContract= $('.actionStartContract').val();
        var actionEndContract= $('.actionEndContract').val();
        var actionDocNo = $('.actionDocNo').val();
		var actionStatus = $('.actionStatus').val(); 
		var actionStatusDescription = $('.actionStatus option:selected').text();
		var actionPosition = $('.actionPosition').val();
		var actionPositionDescription = $('.actionPosition option:selected').text();


        if(actionStartContract=="" || actionStartContract==undefined){
            bootbox.alert("Fill Start Contract");
            return false;
        }
        
        addRow2(actionStartWorking,actionStartContract, actionEndContract,actionDocNo,actionStatus,actionStatusDescription,actionPosition,actionPositionDescription);
            $('.actionStartContract').val('');
			$('.actionStartWorking').val('');
            $('.actionEndContract').val('');
            $('.actionDocNo').val('');
			$('.actionPosition').val('').trigger('change');
			$('.actionStatus').val('').trigger('change');
			
			$(".js-example-data-array-status").select2({
				//data: dataStatus,
				theme: "krajee"
			})
					
			$(".js-example-data-array-selected").select2({
				//data: dataPosition,
				theme: "krajee"
			})
			
			$('.datepickerStartWorking').kvDatepicker({
				autoclose: true,
				format: 'dd-mm-yyyy'
			})
			
			$('.datepickerStartContract').kvDatepicker({
				autoclose: true,
				format: 'dd-mm-yyyy'
			})
			
			$('.datepickerEndContract').kvDatepicker({
				autoclose: true,
				format: 'dd-mm-yyyy'
			})
			
			
			$("[name='MsPersonnelHead[joinPersonnelContract][" + (getMaximumCounter2()) + "][status]']").select2({
				data: dataStatus,
				theme: "krajee"
			})
			
			$("[name='MsPersonnelHead[joinPersonnelContract][" + (getMaximumCounter2()) + "][position]']").select2({
				data: dataPosition,
				theme: "krajee"
			})
			
			$("[name='MsPersonnelHead[joinPersonnelContract][" + (getMaximumCounter2()) + "][startContract]']").kvDatepicker({
				autoclose: true,
			})
			
			$("[name='MsPersonnelHead[joinPersonnelContract][" + (getMaximumCounter2()) + "][endContract]']").kvDatepicker({
				autoclose: true,
				format: 'dd-mm-yyyy'
			})
			
        });
        
        function addRow2(actionStartWorking,actionStartContract, actionEndContract,actionDocNo,actionStatus,actionStatusDescription,actionPosition,actionPositionDescription){
            var template = rowTemplate2;
			template = replaceAll(template, '{{startWorking}}', actionStartWorking);
            template = replaceAll(template, '{{startContract}}', actionStartContract);
            template = replaceAll(template, '{{endContract}}', actionEndContract);
            template = replaceAll(template, '{{docNo}}', actionDocNo);       
            template = replaceAll(template, '{{Count}}', getMaximumCounter2() + 1);
			template = replaceAll(template, '{{position}}', actionPosition);  
			template = replaceAll(template, '{{actionPositionDescription}}', actionPositionDescription); 
			template = replaceAll(template, '{{status}}', actionStatus);  
			template = replaceAll(template, '{{actionstatusDescription}}', actionStatusDescription);
			$('.Contract-Detail-Table .actionBody').append(template); 	
		
			$('.datepickerStartContract').kvDatepicker({
				autoclose: true,
				format: 'dd-mm-yyyy'
			})
			
			$('.datepickerEndContract').kvDatepicker({
				autoclose: true,
				format: 'dd-mm-yyyy'
			})
		
			
			$(".js-example-data-array-status").select2({
				//data: dataStatus,
				theme: "krajee"
			})
			
			
			$(".js-example-data-array-selected").select2({
				//data: dataPosition,
				theme: "krajee"
			})
			
		}
	
	function getMaximumCounter2() {
            var maximum = 0;
             $('.startDate').each(function(){
                    value = parseInt($(this).attr('data-key'));
                    if(value > maximum){
                            maximum = value;
                    }
            });
            return maximum;
	}

	function replaceAll(string, find, replace) {
		return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}

	function escapeRegExp(string) {
		return string.replace(/([.*+?^=!:\${}()|\[\]\/\\\\])/g, "\\\\$1");
	}
        
        $('.Contract-Detail-Table').on('click', '.btnDelete', function (e) {
            var self = this;
            e.preventDefault();
            yii.confirm('Are you sure you want to delete this data ?',deleteRow);
            function deleteRow(){
            $(self).parents('tr').remove();
			}
        
		});
                
        
//MAIN FUNCTION        

	var x =  $('#mspersonnelhead-divisionid').val();
	$('#mspersonnelhead-bankdetail').attr('readonly', true);
		if (x != ''){ 
		$('#description').prop('disabled',false);
		}
		else
		{        
		$('#description').prop('disabled',true);
		$('#description').empty();
		}
		  
	$('#mspersonnelhead-divisionid').change(function(){
		$('#select2-description-container').html("Select Department");
		$('#select2-description-container').attr("title", "Select Department");
		var x =  $('#mspersonnelhead-divisionid').val();

		if (x != ''){ 
		$('#description').prop('disabled',false);
		}
		else
		{
		$('#description').select2('val', '');
		$('#description').prop('disabled',true);
		$('#select2-description-container').html("Select Department");
		$('#select2-description-container').attr("title", "Select Department");
		}
	});
      
        $('.bankHiddenInput').change(function(){
            $.pjax.reload({container:"#bankdropdown"});
        });

        $('.divHiddenInput').change(function(){
            $.pjax.reload({container:"#divdropdown"});
        });   

   
        $('.depHiddenInput').change(function(){
            $.pjax.reload({container:"#depdropdown"});  
			
			var x =  $('#mspersonnelhead-divisionid').val();
			var url_department = "$getDepAjaxURL";
			url_department = url_department + '?id=' + x;
			
			$.post(url_department, function(data) {
				$( "select#description" ).html(data);
			});
			
        });
			

        $('.posHiddenInput').change(function(){
			$.pjax.reload({container:"#posdetail", async:false});				
        });
		
		$('.taxHiddenInput').change(function(){
             $.pjax.reload({container:"#taxdropdown"});   
        });
		
		$('.jamsostekHiddenInput').change(function(){
             $.pjax.reload({container:"#jamsostekdropdown"});   
        });
		
		$('.actionImageKTP .fileinput-remove-button').on('click', function (e) {
			$('.imageGalleryKTPMode').val('DELETED');
		});
		
		$('.actionImagePhoto .fileinput-remove-button').on('click', function (e) {
			$('.imageGalleryPhotoMode').val('DELETED');
		});
		
		$('.actionImageNpwp .fileinput-remove-button').on('click', function (e) {
			$('.imageGalleryNPWPMode').val('DELETED');
		});
		
        
        $('.btneditbank').click(function(){
			var selectedBank = $('#mspersonnelhead-bankname').val();
			if(selectedBank=="" || selectedBank==undefined || selectedBank==''){
					bootbox.alert("Select Bank Code");
					return false;
			}
		});
        
        $('.btneditdiv').click(function(){
			var selectedDiv = $('#mspersonnelhead-divisionid').val();
			if(selectedDiv=="" || selectedDiv==undefined){
					bootbox.alert("Select Division Code");
					return false;
			}
		});
        
		$('.btneditpos').click(function(){
			var selectedPos = $('#mspersonnelhead-position').val();
			if(selectedPos=="" || selectedPos==undefined){
					bootbox.alert("Select Position Code");
					return false;
			}
		});

        $('.btneditdepartment').click(function(){
			var selectedDep = $('#description').val();
			if(selectedDep=="" || selectedDep==undefined){
					bootbox.alert("Select Department Code");
					return false;
			}
		});   
		
		$('.btneditTax').click(function(){
			var selectedTax = $('#mspersonnelhead-taxid').val();
			if(selectedTax=="" || selectedTax==undefined){
					bootbox.alert("Select Tax Id");
					return false;
			}
		}); 
		
		$('.btneditjamsostek').click(function(){
			var selectedJamsostek = $('#mspersonnelhead-jamsostekparm').val();
			if(selectedJamsostek=="" || selectedJamsostek==undefined){
					bootbox.alert("Select Jamsostek Id");
					return false;
			}
		}); 		
        
        $('form').on("beforeValidate", function(){  
            var startPayroll =  $('.actionStartPayroll').val() + '/01';
            var endPayroll =  $('.actionEndPayroll').val() + '/01';
            var date1 = new Date (startPayroll);
            var date2 = new Date (endPayroll);
        
            var firstName =  $('#mspersonnelhead-firstname').val();
            var division =  $('#mspersonnelhead-divisionid').val();
            var position =  $('#mspersonnelhead-position').val();
            var gender =  $('#mspersonnelhead-gender').val();
            var bank =  $('#mspersonnelhead-bankname').val();
        
            var jamsostekParm = $('#mspersonnelhead-jamsostekparm').val();
            var dependent = $('#mspersonnelhead-dependent').val();
			var maritalStatus = $('#mspersonnelhead-maritalstatus').val();
			
			debugger;

			var counter = getMaximumCounter2();
			console.log(counter);
			if (counter == 0) {
				bootbox.alert("At Least Must Fill 1 Row Contract");
				$('#myTab a[href="#menu1"]').tab('show');
				return false;
			}
           
        
            if (firstName=="" || division=="" || gender == ""){
                //$('.nav-tabs a[href="#home"]').tab('show');
                $("#myTab li:eq(0) a").tab('show');
            }
        
            if (bank=="" && firstName!="" && division!="" && gender!= ""){
                //$('.nav-tabs a[href="#menu2"]').tab('show');
                $("#myTab li:eq(2) a").tab('show');
            }

            if ((dependent=="" || maritalStatus == "") && bank!="" && firstName!="" && division!=""  && gender!= ""){
                //$('.nav-tabs a[href="#menu4"]').tab('show');
                $('#myTab a[href="#menu4"]').tab('show');
            }   
                    
            if (startPayroll >  endPayroll && endPayroll != ""){
                bootbox.alert("End Payroll Must Greater Than Start Payroll");
                $('#myTab a[href="#home"]').tab('show');
                return false;
			}
			
		
        });
        
        var i = 1;        
        $('.actionEndPayroll').change(function(){
            console.log(i);
            console.log(i%3 == 0); 
            var startPayroll =  $('.actionStartPayroll').val() + '/01';
            var endPayroll =  $('.actionEndPayroll').val() + '/01';
    
            i++;
        
            if(endPayroll<startPayroll && i%3 == 0 && endPayroll != ""){
            bootbox.alert("Start Payroll Must Bigger Than End Payroll");
            return false;
            }
        });
          
        $('.btnSameAsId').click(function(){
            var npwpNo = $('#mspersonnelhead-npwpno').val();
            var firstName1 = $('#mspersonnelhead-firstname').val();
            var lastName1 = $('#mspersonnelhead-lastname').val();
            var fullname1 = firstName1+' '+lastName1;
        
            var address1 = $('#mspersonnelhead-address').val();
            var city1 = $('#mspersonnelhead-city').val();
            var fulladress = address1 +' '+city1
        
            $('#mspersonnelhead-npwpname').val(fullname1);
            $('#mspersonnelhead-npwpaddress').val(fulladress);      
        });
		 
		$('#mspersonnelhead-npwpname').focusout(function(){
			var npwpNo = $('#mspersonnelhead-npwpno').val();
			if (npwpNo==''){
				$('#mspersonnelhead-npwpno').val('000000000000000');
			}
		});
		
		$('#mspersonnelhead-npwpaddress').focusout(function(){
			var npwpNo = $('#mspersonnelhead-npwpno').val();
			if (npwpNo==''){
				$('#mspersonnelhead-npwpno').val('000000000000000');
			}
		});
		
		 //NAVIGASI
		 
		 $('.btnNextHome').click(function(e){
			 e.preventDefault();
			$('#myTab a[href="#menu1"]').tab('show');
         });
		 
		$('.btnNext1').click(function(e){
			e.preventDefault();
			$('#myTab a[href="#menu2"]').tab('show');
         });
		 
		 $('.btnNext2').click(function(e){
			 e.preventDefault();
			$('#myTab a[href="#menu3"]').tab('show');
         });
		 
		 $('.btnNext3').click(function(e){
			 e.preventDefault();
			$('#myTab a[href="#menu4"]').tab('show');
         });
		 
		 $('.btnNext4').click(function(e){
			e.preventDefault();
			$('#myTab a[href="#menu5"]').tab('show');
         });
		 
		 $('.btnNext5').click(function(e){
			 e.preventDefault();
			$('#myTab a[href="#menu6"]').tab('show');
         });
		 
		 
		$('#datepicker1').kvDatepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
		})
		
		$('.datepickerStartWorking').kvDatepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
		})
		
		$('.datepickerStartContract').kvDatepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
		})
		
		$('.datepickerEndContract').kvDatepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
		})
			
		$(".js-example-data-array-selected").select2({
			data: dataPosition,
			theme: "krajee"
		})
		
		$(".js-example-data-array-status").select2({
			data: dataStatus,
			theme: "krajee"
		})
		
		//Tooltip
		
		$(".select2-selection span").attr('title', '');
		      
});
        
        
$(document).on('pjax:end', function() {
						
		var dataPosition = $positionDetailPjax;
		var dataStatus = $statusDetail;
        var initValue = $familyDetail;
        var rowTemplate = "" +
        "<tr>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='firstName form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][firstName]' data-key='{{Count}}' value='{{firstName}}'" +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='lastName form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][lastName]' value='{{lastName}}'  " +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='relationship form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][relationship]' value='{{relationship}}'  " +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='idNumber form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][idNumber]' value='{{idNumber}}' " +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='text' class='birthPlace form-control' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][birthPlace]' value='{{birthPlace}}' " +
        "   </td>" +
		"   <td class='text-left'>" +
		"	<div class='input-group date'>" +
		"			<div class='input-group-addon'>" +
        "				<span class='glyphicon glyphicon-calendar'></span>" +
		"			</div>" +
		"		<input id='datepicker1' type='text' class='birthDate form-control datepicker datepickerBirthDay' name='MsPersonnelHead[joinPersonnelfamily][{{Count}}][birthDate]' value='{{birthDate}}'>" +
		"	</div>" +
		"   </td>" +
        $deleteRow
        "</tr>";
		
					   
        $('.Family-Detail-Table .btnAdd').on('click', function (e) {
        e.preventDefault();
        var actionfirstName = $('.firstName-1').val();
        var actionlastName = $('.lastName-1').val();
        var actionrelationship = $('.relationship-1').val();
        var actionidNumber =  $('.idNumber-1').val();
        var actionbirthPlace =  $('.birthPlace-1').val();
        var actionbirthDate = $('.birthDate-1').val();

        if(actionfirstName=="" || actionfirstName==undefined){
        console.log(actionfirstName);
            bootbox.alert("Fill Name");
            return false;
        }

        if(actionrelationship=="" || actionrelationship==undefined){
            bootbox.alert("Fill Relationship");
            return false;
        }   
        
        addRow(actionfirstName, actionlastName,actionrelationship,actionidNumber,actionbirthPlace,actionbirthDate);
            $('.firstName-1').val('');
            $('.lastName-1').val('');
            $('.relationship-1').val('');
            $('.idNumber-1').val('');
            $('.birthPlace-1').val('');
            $('.birthDate-1').val('');

        });
        
        function addRow(actionfirstName, actionlastName,actionrelationship,actionidNumber,actionbirthPlace,actionbirthDate){
            var template = rowTemplate;
            template = replaceAll(template, '{{firstName}}', actionfirstName);
            template = replaceAll(template, '{{lastName}}', actionlastName);
            template = replaceAll(template, '{{relationship}}', actionrelationship);
            template = replaceAll(template, '{{idNumber}}', actionidNumber);
            template = replaceAll(template, '{{birthPlace}}', actionbirthPlace);
            template = replaceAll(template, '{{birthDate}}', actionbirthDate);        
            template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
            $('.Family-Detail-Table .actionBody').append(template);  

			$('.datepickerBirthDay').kvDatepicker({
				autoclose: true,
				format: 'dd-mm-yyyy'
			})			
		}
	
		function getMaximumCounter() {
				var maximum = 0;
				 $('.firstName').each(function(){
						value = parseInt($(this).attr('data-key'));
						if(value > maximum){
								maximum = value;
						}
				});
				return maximum;
		}

		function replaceAll(string, find, replace) {
			return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
		}

		function escapeRegExp(string) {
			return string.replace(/([.*+?^=!:\${}()|\[\]\/\\\\])/g, "\\\\$1");
		}
			
			$('.Family-Detail-Table').on('click', '.btnDelete', function (e) {
				var self = this;
				e.preventDefault();
				yii.confirm('Are you sure you want to delete this data ?',deleteRow);
				function deleteRow(){
				$(self).parents('tr').remove();
				var countData = $('.Family-Detail-Table tbody tr').length;
				console.log(countData);
			}
			
		});
          
        
        
//CONTRACT GRID VIEW
		var initValue2 = $contractDetail;
		var rowTemplate2 = "" +
        "<tr class='tr-action'>" +
		"   <td class='text-left'>" +
		"		<div class='input-group date'>" +
		"			<div class='input-group-addon'>" +
        "				<span class='glyphicon glyphicon-calendar'></span>" +
		"			</div>" +
		"			<input id='datepickerStartWorking' data-key='{{Count}}' type='' class='startWorking form-control datepickerStartWorking' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][startWorking]' value='{{startWorking}}'>" +
		"		</div>" +	
		"   </td>" +
		"   <td class='text-left'>" +
		"	<div class='input-group date'>" +
		"			<div class='input-group-addon'>" +
        "				<span class='glyphicon glyphicon-calendar'></span>" +
		"			</div>" +
		"		<input id='datepickerStartContract' data-key='{{Count}}' type='text' class='startDate form-control datepicker datepickerStartContract' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][startContract]' value='{{startContract}}'>" +
		"	</div>" +	
		"   </td>" +
		"   <td class='text-left'>" +
		"		<div class='input-group date'>" +
		"				<div class='input-group-addon'>" +
        "					<span class='glyphicon glyphicon-calendar'></span>" +
		"				</div>" +
		"			<input id='datepickerEndContract' type='text' class='endDate form-control datepicker datepickerEndContract' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][endContract]' value='{{endContract}}'>" +
		"		</div>" +
		"   </td>" +
        "   <td class='text-left'>" +
        "       <input type='' class='docNo form-control' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][docNo]' value='{{docNo}}' > " +
        "   </td>" +
		"   <td class='text-left'>" +
		"		<div class=''>" +
        "       	<select class='js-example-data-array-status' style='width: 100%;' class='status form-control' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][status]' value='{{status}}'> " +
		"				<option value='{{status}}' selected='selected'>{{actionstatusDescription}}</option> " +		
		"			</select> " +
		"		</div> "+
        "   </td>" +
		"   <td class='text-left'>" +
		"		<div class=''>" +
        "       	<select class='js-example-data-array-selected' style='width: 100%;'  class='status form-control' name='MsPersonnelHead[joinPersonnelContract][{{Count}}][position]' value='{{position}}'> " +
		"				<option value='{{position}}' selected='selected'>{{actionPositionDescription}}</option> " +
		"			</select> " +
		"		</div> "+
        "   </td>" +
        $deleteRow
        "</tr>";		
		
        if (initValue2 != null) {
			$('.tr-action').remove(); 
			initValue2.forEach(function(entry) {
			addRow2(entry.startWorking.toString(),entry.startContract.toString(), entry.endContract.toString(),entry.docNo.toString(),entry.status.toString(),'',entry.position.toString(),'');
           });
        }
 
        
        
    $('.Contract-Detail-Table .btnAdd').on('click', function (e) {
        e.preventDefault();
        var actionStartContract= $('.actionStartContract').val();
        var actionEndContract= $('.actionEndContract').val();
        var actionDocNo = $('.actionDocNo').val();
		var actionStatus = $('.actionStatus').val(); 
		var actionStatusDescription = $('.actionStatus option:selected').text();
		var actionPosition = $('.actionPosition').val();
		var actionPositionDescription = $('.actionPosition option:selected').text();


        if(actionStartContract=="" || actionStartContract==undefined){
            bootbox.alert("Fill Start Contract");
            return false;
        }

        if(actionEndContract=="" || actionEndContract==undefined){
            bootbox.alert("Fill End Contract");
            return false;
        }   
		
        addRow2(actionStartContract, actionEndContract,actionDocNo,actionStatus,actionStatusDescription,actionPosition,actionPositionDescription);
		$('.actionStartContract').val('');
		$('.actionEndContract').val('');
		$('.actionDocNo').val('');
		$('.actionPosition').val('').trigger('change');
		$('.actionStatus').val('').trigger('change');
		
		$(".js-example-data-array-status").select2({
			//data: dataStatus,
			theme: "krajee"
		})
				
		$(".js-example-data-array-selected").select2({
			//data: dataPosition,
			theme: "krajee"
		})
		
		$('.datepickerStartContract').kvDatepicker({
			autoclose: true,
			format: 'dd-mm-yyyy'
		})
		
		$('.datepickerEndContract').kvDatepicker({
			autoclose: true,
			format: 'dd-mm-yyyy'
		})
		
		
		$("[name='MsPersonnelHead[joinPersonnelContract][" + (getMaximumCounter2()) + "][status]']").select2({
			data: dataStatus,
			theme: "krajee"
		})
		
		$("[name='MsPersonnelHead[joinPersonnelContract][" + (getMaximumCounter2()) + "][position]']").select2({
			data: dataPosition,
			theme: "krajee"
		})
		
		$("[name='MsPersonnelHead[joinPersonnelContract][" + (getMaximumCounter2()) + "][startContract]']").kvDatepicker({
			autoclose: true,
		})
		
		$("[name='MsPersonnelHead[joinPersonnelContract][" + (getMaximumCounter2()) + "][endContract]']").kvDatepicker({
			autoclose: true,
			format: 'dd-mm-yyyy'
		})
			
	});
        
	function addRow2(actionStartWorking,actionStartContract, actionEndContract,actionDocNo,actionStatus,actionStatusDescription,actionPosition,actionPositionDescription){
		var template = rowTemplate2;
		template = replaceAll(template, '{{startWorking}}', actionStartWorking);
		template = replaceAll(template, '{{startContract}}', actionStartContract);
		template = replaceAll(template, '{{endContract}}', actionEndContract);
		template = replaceAll(template, '{{docNo}}', actionDocNo);       
		template = replaceAll(template, '{{Count}}', getMaximumCounter2() + 1);
		template = replaceAll(template, '{{position}}', actionPosition);  
		template = replaceAll(template, '{{actionPositionDescription}}', actionPositionDescription); 
		template = replaceAll(template, '{{status}}', actionStatus);  
		template = replaceAll(template, '{{actionstatusDescription}}', actionStatusDescription);
		$('.Contract-Detail-Table .actionBody').append(template); 		
	
		$('.datepickerStartContract').kvDatepicker({
			autoclose: true,
			format: 'dd-mm-yyyy'
		})
		
		$('.datepickerEndContract').kvDatepicker({
			autoclose: true,
			format: 'dd-mm-yyyy'
		})
	
		
		$(".js-example-data-array-status").select2({
			//data: dataStatus,
			theme: "krajee"
		})
		
		
		$(".js-example-data-array-selected").select2({
			//data: dataPosition,
			theme: "krajee"
		})
		
	}
	
	function getMaximumCounter2() {
            var maximum = 0;
             $('.startDate').each(function(){
                    value = parseInt($(this).attr('data-key'));
                    if(value > maximum){
                            maximum = value;
                    }
            });
            return maximum;
	}

	function replaceAll(string, find, replace) {
		return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}

	function escapeRegExp(string) {
		return string.replace(/([.*+?^=!:\${}()|\[\]\/\\\\])/g, "\\\\$1");
	}
        
        $('.Contract-Detail-Table').on('click', '.btnDelete', function (e) {
            var self = this;
            e.preventDefault();
            yii.confirm('Are you sure you want to delete this data ?',deleteRow);
            function deleteRow(){
            $(self).parents('tr').remove();
        }
        
	});
                
        
//MAIN FUNCTION     

	var x =  $('#mspersonnelhead-divisionid').val();
	$('#mspersonnelhead-bankdetail').attr('readonly', true);
		if (x != ''){ 
		$('#description').prop('disabled',false);
		}
		else
		{        
		$('#description').prop('disabled',true);
		$('#description').empty();
		}
		  
	
	$('#mspersonnelhead-divisionid').change(function(){
		$('#select2-description-container').html("Select Department");
		$('#select2-description-container').attr("title", "Select Department");
		var x =  $('#mspersonnelhead-divisionid').val();

		if (x != ''){ 
		$('#description').prop('disabled',false);
		}
		else
		{
		$('#description').select2('val', '');
		$('#description').prop('disabled',true);
		$('#select2-description-container').html("Select Department");
		$('#select2-description-container').attr("title", "Select Department");
		}
	});
	

	$('.actionImageKTP .fileinput-remove-button').on('click', function (e) {
		$('.imageGalleryKTPMode').val('DELETED');
	});
	
	$('.actionImagePhoto .fileinput-remove-button').on('click', function (e) {
		$('.imageGalleryPhotoMode').val('DELETED');
	});
	
	$('.actionImageNpwp .fileinput-remove-button').on('click', function (e) {
		$('.imageGalleryNPWPMode').val('DELETED');
	});
	
	
	$('.btneditbank').click(function(){
		var selectedBank = $('#mspersonnelhead-bankname').val();
		if(selectedBank=="" || selectedBank==undefined || selectedBank==''){
				bootbox.alert("Select Bank Code");
				return false;
		}
	});
	
	$('.btneditdiv').click(function(){
		var selectedDiv = $('#mspersonnelhead-divisionid').val();
		if(selectedDiv=="" || selectedDiv==undefined){
				bootbox.alert("Select Division Code");
				return false;
		}
	});
	
	$('.btneditpos').click(function(){
		var selectedPos = $('#mspersonnelhead-position').val();
		if(selectedPos=="" || selectedPos==undefined){
				bootbox.alert("Select Position Code");
				return false;
		}
	});

	$('.btneditdepartment').click(function(){
		var selectedDep = $('#description').val();
		if(selectedDep=="" || selectedDep==undefined){
				bootbox.alert("Select Department Code");
				return false;
		}
	});   
	
	$('.btneditTax').click(function(){
		var selectedTax = $('#mspersonnelhead-taxid').val();
		if(selectedTax=="" || selectedTax==undefined){
				bootbox.alert("Select Tax Id");
				return false;
		}
	}); 
	
	$('.btneditjamsostek').click(function(){
		var selectedJamsostek = $('#mspersonnelhead-jamsostekparm').val();
		if(selectedJamsostek=="" || selectedJamsostek==undefined){
				bootbox.alert("Select Jamsostek Id");
				return false;
		}
	}); 		
	
	$('form').on("beforeValidate", function(){  
		var startPayroll =  $('.actionStartPayroll').val() + '/01';
		var endPayroll =  $('.actionEndPayroll').val() + '/01';
		var date1 = new Date (startPayroll);
		var date2 = new Date (endPayroll);
	
		var firstName =  $('#mspersonnelhead-firstname').val();
		var division =  $('#mspersonnelhead-divisionid').val();
		var position =  $('#mspersonnelhead-position').val();
		var gender =  $('#mspersonnelhead-gender').val();
		var bank =  $('#mspersonnelhead-bankname').val();
	
		var jamsostekParm = $('#mspersonnelhead-jamsostekparm').val();
		var dependent = $('#mspersonnelhead-dependent').val();
		var maritalStatus = $('#mspersonnelhead-maritalstatus').val();
	   
	
		if (firstName=="" || division=="" || position =="" || gender == ""){
			//$('.nav-tabs a[href="#home"]').tab('show');
			$("#myTab li:eq(0) a").tab('show');
		}
	
		if (bank=="" && firstName!="" && division!="" && position!="" && gender!= ""){
			//$('.nav-tabs a[href="#menu2"]').tab('show');
			$("#myTab li:eq(2) a").tab('show');
		}
	
		if (jamsostekParm=="" && bank!="" && firstName!="" && division!="" && position!="" && gender!= ""){
			$('#myTab a[href="#menu3"]').tab('show');
		}

		if ((dependent=="" || maritalStatus == "") && jamsostekParm!="" && bank!="" && firstName!="" && division!="" && position!="" && gender!= ""){
			//$('.nav-tabs a[href="#menu4"]').tab('show');
			$('#myTab a[href="#menu4"]').tab('show');
		}   
				
		if (startPayroll >  endPayroll && endPayroll != ""){
			bootbox.alert("End Payroll Must Greater Than Start Payroll");
			$('#myTab a[href="#home"]').tab('show');
			return false;
		}        



	});
	
	var i = 1;        
	$('.actionEndPayroll').change(function(){
		console.log(i);
		console.log(i%3 == 0); 
		var startPayroll =  $('.actionStartPayroll').val() + '/01';
		var endPayroll =  $('.actionEndPayroll').val() + '/01';

		i++;
	
		if(endPayroll<startPayroll && i%3 == 0 && endPayroll != ""){
		bootbox.alert("Start Payroll Must Bigger Than End Payroll");
		return false;
		}
	});
	  
	$('.btnSameAsId').click(function(){
		var npwpNo = $('#mspersonnelhead-npwpno').val();
		var firstName1 = $('#mspersonnelhead-firstname').val();
		var lastName1 = $('#mspersonnelhead-lastname').val();
		var fullname1 = firstName1+' '+lastName1;
	
		var address1 = $('#mspersonnelhead-address').val();
		var city1 = $('#mspersonnelhead-city').val();
		var fulladress = address1 +' '+city1
	
		$('#mspersonnelhead-npwpname').val(fullname1);
		$('#mspersonnelhead-npwpaddress').val(fulladress);      
	 });
	 
	 //NAVIGASI
	 
	 $('.btnNextHome').click(function(e){
		 e.preventDefault();
		$('#myTab a[href="#menu1"]').tab('show');
	 });
	 
	$('.btnNext1').click(function(e){
		e.preventDefault();
		$('#myTab a[href="#menu2"]').tab('show');
	 });
	 
	 $('.btnNext2').click(function(e){
		 e.preventDefault();
		$('#myTab a[href="#menu3"]').tab('show');
	 });
	 
	 $('.btnNext3').click(function(e){
		 e.preventDefault();
		$('#myTab a[href="#menu4"]').tab('show');
	 });
	 
	 $('.btnNext4').click(function(e){
		e.preventDefault();
		$('#myTab a[href="#menu5"]').tab('show');
	 });
	 
	 $('.btnNext5').click(function(e){
		 e.preventDefault();
		$('#myTab a[href="#menu6"]').tab('show');
	 });
	 
	 
	$('#datepicker1').kvDatepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
	})
	
	$('.datepickerStartWorking').kvDatepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
	})
	
	$('.datepickerStartContract').kvDatepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
	})
	
	$('.datepickerEndContract').kvDatepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
	})
	


	var url_position = "$url_position";
	$.post(url_position, function(position) {
		$(".js-example-data-array-selected").select2({
			data: position,
			theme: "krajee"
		})
			
	});	
	
	
	$(".js-example-data-array-status").select2({
		data: dataStatus,
		theme: "krajee"
	})

 	
});
                
SCRIPT;
$this->registerJs($js);
?>