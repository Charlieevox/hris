<?php

namespace app\components;

use Yii;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\validators\DateValidator;
use app\models\MsTransNumber;
use app\models\TrTransactionLog;

class AppHelper {

    public static $activeStatus = ['1' => 'Active', '0' => 'Not Active'];
    public static $vatStatus = ['1' => 'Yes', '0' => 'No'];
    public static $finishedStatus = ['1' => 'Finished', '0' => 'Not Finished'];
    public static $activePaid = ['1' => 'Paid', '0' => 'Unpaid'];
    public static $overnight = ['1' => 'Yes', '0' => 'No'];
    public static $type = ['1' => 'Fix', '2' => 'Non Fix', '4' => 'Formula'];
    public static $parm = ['1' => 'Deduction', '2' => 'Allowance'];
    public static $ProrateType = ['1' => 'Fix Day', '2' => 'Working Day', '3' => 'Calendar Day'];
    public static $taxType = ['1' => 'Gross', '2' => 'Nett', '3' => 'Gross Up'];

    public static function getDatePickerConfigMonthYear($additional = []) {
        $config = [
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'minViewMode' => 1,
                'format' => 'yyyy/mm',
                'autoWidget' => true,
                'autoclose' => true,
                'startDate' => '-150y',
                'todayHighlight' => true,
            ]
        ];

        $config = array_merge($config, $additional);
        return $config;
    }

    public static function getOvernight() {
        return [
            'attribute' => 'overnight',
            'value' => function ($data) {
                $overnightStatus = self::$overnight;
                return $overnightStatus[$data->overnight];
            },
            'filter' => self::$overnight,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function getPayrollComponentType() {
        return [
            'attribute' => 'type',
            'value' => function ($data) {
                $ComponentType = self::$type;
                return $ComponentType[$data->type];
            },
            'filter' => self::$type,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function getPayrollComponentParm() {
        return [
            'attribute' => 'parameter',
            'value' => function ($data) {
                $ComponentParm = self::$parm;
                return $ComponentParm[$data->parameter];
            },
            'filter' => self::$parm,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function getPayrollProrateType() {
        return [
            'attribute' => 'type',
            'value' => function ($data) {
                $ComponentParm = self::$ProrateType;
                return $ComponentParm[$data->type];
            },
            'filter' => self::$ProrateType,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function getPayrollProrateType2() {
        return [
            'attribute' => 'prorateSetting',
            'value' => function ($data) {
                $ComponentParm = self::$ProrateType;
                return $ComponentParm[$data->prorateSetting];
            },
            'filter' => self::$ProrateType,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function getPayrollTaxType() {
        return [
            'attribute' => 'taxSetting',
            'value' => function ($data) {
                $ComponentParm = self::$taxType;
                return $ComponentParm[$data->taxSetting];
            },
            'filter' => self::$taxType,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function ExcelToPHP($dateValue = 0, $ExcelBaseDate = 0) {
        if ($ExcelBaseDate == 0) {
            $myExcelBaseDate = 25569;
            //  Adjust for the spurious 29-Feb-1900 (Day 60)
            if ($dateValue < 60) {
                --$myExcelBaseDate;
            }
        } else {
            $myExcelBaseDate = 24107;
        }

        // Perform conversion
        if ($dateValue >= 1) {
            $utcDays = $dateValue - $myExcelBaseDate;
            $returnValue = round($utcDays * 86400);
            if (($returnValue <= PHP_INT_MAX) && ($returnValue >= -PHP_INT_MAX)) {
                $returnValue = (integer) $returnValue;
            }
        } else {
            $hours = round($dateValue * 24);
            $mins = round($dateValue * 1440) - round($hours * 60);
            $secs = round($dateValue * 86400) - round($hours * 3600) - round($mins * 60);
            $returnValue = (integer) gmmktime($hours, $mins, $secs);
        }

        // Return
        return $returnValue;
    }

    public static function getDatePickerConfig($additional = []) {
        $config = [
            'type' => DatePicker::TYPE_INPUT,
			'options' => ['placeholder' => 'ex: 01-01-1990'],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoWidget' => true,
                'autoclose' => true,
                'startDate' => '-150y',
                'todayHighlight' => true
            ]
        ];

        $config = array_merge($config, $additional);
        return $config;
    }

    public static function getDateTimePickerConfig($additional = []) {
        $config = [
            'type' => DateTimePicker::TYPE_INPUT,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy hh:ii',
                'autoWidget' => true,
                'autoclose' => true,
                'todayBtn' => true,
                'startDate' => '-150y',
                'todayHighlight' => true
            ]
        ];

        $config = array_merge($config, $additional);
        return $config;
    }

    public static function convertDateTimeFormat($date, $formatFrom = "d-m-Y H:i", $formatTo = "Y-m-d H:i") {
        if (!empty($date)) {
            if (self::isValidDate($date, $formatFrom)) {
                $myDateTime = \DateTime::createFromFormat($formatFrom, $date);
                return $myDateTime->format($formatTo);
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    public static function getCurrentDateTime() {
        return date('d-m-Y H:i', strtotime("+7 hours", strtotime(date('d-m-Y H:i'))));
    }

    public static function createTransactionNumber($transType, $tempTransNum) {
        $newTransNum = "";
        $transModel = MsTransNumber::find()
                ->where(['transType' => $transType])
                ->one();
        if (!empty($transModel)) {
            $newTransNum = $transModel->transAbbreviation . $tempTransNum;
        }

        return $newTransNum;
    }

    public static function insertTransactionLog($transDesc, $transNum) {
        $newTransNum = "";
        $transModel = new TrTransactionLog();
        $transModel->transactionLogDate = date('Y-m-d H:i:s');
        $transModel->transactionLogDesc = $transDesc;
        $transModel->refNum = $transNum;
        $transModel->username = Yii::$app->user->identity->username;
        $transModel->save();
    }

    public static function isValidDate($date, $format) {
        $validator = new DateValidator();
        $validator->format = "php:" . $format;
        return $validator->validate($date);
    }

    public static function getIsActiveColumn() {
        return [
            'attribute' => 'flagActive',
            'value' => function ($data) {
                $activeStatus = self::$activeStatus;
                return $activeStatus[$data->flagActive];
            },
            'filter' => self::$activeStatus,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function getVATColumn() {
        return [
            'attribute' => 'vat',
            'value' => function ($data) {
                $vatStatus = self::$vatStatus;
                return $vatStatus[$data->vat];
            },
            'filter' => self::$vatStatus,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function getIsPaidColumn() {
        return [
            'attribute' => 'status',
            'value' => function ($data) {
                $activePaid = self::$activePaid;
                return $activePaid[$data->status];
            },
            'filter' => self::$activePaid,
            'filterInputOptions' => [
                'prompt' => '- All -'
            ],
            'contentOptions' => ['class' => 'text-center'],
        ];
    }

    public static function getMasterActionColumn($template = '{view} {update} {delete}') {
        return [
            'class' => 'kartik\grid\ActionColumn',
            'template' => $template,
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'header' => '',
            'buttons' => [
                'delete' => function ($url, $model) {
                    if ($model->flagActive == 0) {
                        $url = ['restore', 'id' => $model->primaryKey];
                        $icon = 'repeat';
                        $label = 'Cancel Delete';
                        $confirm = 'Are you sure you want to activate this data ?';
                    } else {
                        $url = ['delete', 'id' => $model->primaryKey];
                        $icon = 'trash';
                        $label = 'Delete';
                        $confirm = 'Are you sure you want to delete this data ?';
                    }
                    return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
                                'title' => $label,
                                'aria-label' => $label,
                                'data-confirm' => $confirm,
                                'data-method' => 'post',
                                'data-pajax' => '0'
						]);
					},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getMasterActionColumn2($template = '{view} {update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					$confirm = 'Are you sure you want to delete this data ?';
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getMasterActionColumn3($template = '{view} {update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					$url = ['close', 'id' => $model->primaryKey];
					$icon = 'off';
					$label = 'Close Period';
					$confirm = 'Are you sure you want to close period ?';
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getMasterActionPrint($template = '{view} {Print} {update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					if ($model->flagActive == 0) {
						$url = ['restore', 'id' => $model->primaryKey];
						$icon = 'repeat';
						$label = 'Cancel Delete';
						$confirm = 'Are you sure you want to activate this data ?';
					} else {
						$url = ['delete', 'id' => $model->primaryKey];
						$icon = 'trash';
						$label = 'Delete';
						$confirm = 'Are you sure you want to delete this data ?';
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				},
				'print' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-print'/>", ['printpage', 'id' => $model->primaryKey], [
								'title' => 'Print',
								'class' => 'open-modal-btn',
					]);
				}
			]
		];
	}

	public static function getMasterEditColumn($template = '{update}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getPayableReceivableColumn($template) {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'view' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-eye-open'/>", ['view', 'id' => get_class($model) == 'app\models\TrAccountReceivable' ? $model->clientID : $model->supplierID], [
								'title' => 'view',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getActionColumn($template = '{view} {update} {delete} {approve}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					$confirm = 'Are you sure you want to delete this data ?';
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				},
				'approve' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-check'/>", ['approve', 'id' => $model->primaryKey], [
								'title' => 'Approve',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getFlagFinished($template = '{check}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'check' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-check'/>", [
								'process',
								'id' => $model->primaryKey,
								'flag' => ($model->flagFinished == 1 ? 0 : 1),
								], [
								'title' => 'Process',
								'class' => 'open-modal-btn'
						]);
					}
			]
		];
	}

	public static function getIsFinishedColumn() {
		return [
			'attribute' => 'flagFinished',
			'value' => function ($data) {
				$finishedStatus = self::$finishedStatus;
				return $finishedStatus[$data->flagFinished];
			},
			'filter' => self::$finishedStatus,
			'filterInputOptions' => [
				'prompt' => '- All -'
			],
			'contentOptions' => ['class' => 'text-center'],
		];
	}

	public static function getCancelButton() {
		$url = ['index'];
		$label = 'Cancel';
		$confirm = 'Unsaved data will be discarded. Are you sure ?';
		return Html::a("<span class='glyphicon glyphicon-remove'> Cancel </span>", $url, [
					'class' => 'btn btn-danger',
					'title' => $label,
					'aria-label' => $label,
					'data-confirm' => $confirm
		]);
	}

	public static function getActionPurchase($template = '{view} {update} {delete} {approve}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {

					$connection = Yii::$app->db;
					$sql = "SELECT purchaseNum 
					FROM tr_supplierpaymentdetail
					WHERE purchaseNum = '" . $model->purchaseNum . "' ";
					$temp = $connection->createCommand($sql);
					$headResult = $temp->queryAll();
					$count = count($headResult);

					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					if ($count > 0) {
						$confirm = 'Data cannot be deleted because it has been created for other transactions !';
					} else {
						$confirm = 'Are you sure you want to delete this data ?';
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {

					$connection = Yii::$app->db;
					$sql = "SELECT purchaseNum 
					FROM tr_supplierpaymentdetail
					WHERE purchaseNum = '" . $model->purchaseNum . "' ";
					$temp = $connection->createCommand($sql);
					$headResult = $temp->queryAll();
					$count = count($headResult);
					if ($count > 0) {
						$url = ['update', 'id' => $model->primaryKey];
						$icon = 'pencil';
						$label = 'Update';
						$confirm = 'Data cannot be updated because it has been created for other transactions !';
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
									'title' => 'Update',
									'class' => 'open-modal-btn'
						]);
					}
				},
				'approve' => function ($url, $model) {
					if ($model->status > 1) {
						$url = ['approve', 'id' => $model->primaryKey];
						$icon = 'check';
						$label = 'Approve';
						$confirm = 'Data cannot be approved because status it is not new !';
						return Html::a("<span class='glyphicon glyphicon-check'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-check'/>", ['approve', 'id' => $model->primaryKey], [
									'title' => 'Approve',
									'class' => 'open-modal-btn'
						]);
					}
				}
			]
		];
	}

	public static function getActionSales($template = '{view} {update} {delete} {approve}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					$connection = Yii::$app->db;
					$sql = "SELECT salesNum 
					FROM tr_clientsettlementdetail
					WHERE salesNum = '" . $model->salesNum . "' ";
					$temp = $connection->createCommand($sql);
					$headResult = $temp->queryAll();
					$count = count($headResult);

					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					if ($count > 0) {
						$confirm = 'Data cannot be deleted because it has been created for other transactions !';
					} else {
						$confirm = 'Are you sure you want to delete this data ?';
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					$connection = Yii::$app->db;
					$sql = "SELECT salesNum 
					FROM tr_clientsettlementdetail
					WHERE salesNum = '" . $model->salesNum . "' ";
					$temp = $connection->createCommand($sql);
					$headResult = $temp->queryAll();
					$count = count($headResult);
					if ($count > 0) {
						$url = ['update', 'id' => $model->primaryKey];
						$icon = 'pencil';
						$label = 'Update';
						$confirm = 'Data cannot be updated because it has been created for other transactions !';
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
									'title' => 'Update',
									'class' => 'open-modal-btn'
						]);
					}
				},
				'approve' => function ($url, $model) {
					if ($model->status > 1) {
						$url = ['approve', 'id' => $model->primaryKey];
						$icon = 'check';
						$label = 'Approve';
						$confirm = 'Data cannot be approved because status it is not new !';
						return Html::a("<span class='glyphicon glyphicon-check'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-check'/>", ['approve', 'id' => $model->primaryKey], [
									'title' => 'Approve',
									'class' => 'open-modal-btn'
						]);
					}
				}
			]
		];
	}

	public static function getMasterAssetColumn($template = '{view} {check} {update} {maintenance} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'width' => '10%',
			'header' => '',
			'buttons' => [
				'check' => function ($url, $model) {
					$url = ['check', 'id' => $model->primaryKey];
					$icon = 'check';
					$label = 'Active';
					if ($model->currentValue == 0) {
						//return Yii::$app->getSession()->setFlash('bootbox', 'Data cannot is Active because current Value 0!');
						$confirm = 'Data cannot is Active because current Value = 0!';
					} else {
						$confirm = 'Are you sure you want to Active this data ?';
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					if ($model->flagActive == 1 || $model->currentValue == 0) {
						$url = ['update', 'id' => $model->primaryKey];
						$icon = 'pencil';
						$label = 'Edit';
						$confirm = 'Data cannot be Edited because current value = 0 OR status active!';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
									'title' => 'Edit',
									'class' => 'open-modal-btn'
						]);
					}
				},
				'maintenance' => function ($url, $model) {
					if ($model->flagActive == 0 || $model->currentValue == 0) {
						$url = ['maintenance', 'id' => $model->primaryKey];
						$icon = 'wrench';
						$label = 'Maintenance';
						$confirm = 'Data cannot be Maintained because current value = 0 OR status not active!';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-wrench'/>", ['maintenance', 'id' => $model->primaryKey], [
									'title' => 'Maintenance',
									'class' => 'open-modal-btn'
						]);
					}
				},
				'delete' => function ($url, $model) {

					if ($model->currentValue == 0 && $model->flagActive == 0) {
						$icon = 'trash';
						$label = 'Dispose';
						$confirm = 'Data already to Dispose because current value = 0 and status not active!';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'data-confirm' => $confirm,
									'title' => $label,
									'aria-label' => $label,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						$url = ['delete', 'id' => $model->primaryKey];
						$icon = 'trash';
						$label = 'Dispose';
						$confirm = 'Are you sure you want to Dispose this data ?';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					}
				}
			]
		];
	}

	public static function getTopUpColumn($template = '{view} {confirmation} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					if ($model->status == 1) {
						$confirm = 'Data cannot be deleted because status is paid !';
					} else {
						$confirm = 'Are you sure you want to delete this data ?';
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
						'confirmation' => function ($url, $model) {
					if ($model->status == 1) {
						$url = ['confirmation', 'id' => $model->primaryKey];
						$icon = 'check';
						$label = 'Confirmation';
						$confirm = 'Data cannot be confirmed because status is paid !';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-check'/>", ['confirmation', 'id' => $model->primaryKey], [
									'title' => 'Confirmation',
									'class' => 'open-modal-btn'
						]);
					}
				}
			]
		];
	}

	public static function getProcessColumn($template = '{processconfirmation}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'processconfirmation' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-check'/>", ['processconfirmation', 'id' => $model->primaryKey], [
								'title' => 'Process Confirmation',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getIndexColumn($template = '{check}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => ('{check}'),
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'check' => function ($url, $model) {
					$url = ['check', 'id' => $model['id'],'filter'=> $model['allertId']];
					$icon = 'check';
					$label = 'Process';
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				}
			]
		];
	}

	public static function getCancelProcessButton() {
		$url = ['process'];
		$label = 'Cancel';
		$confirm = 'Cancel process confirmation. Are you sure ?';
		return Html::a("<span class='glyphicon glyphicon-remove'> Cancel </span>", $url, [
					'class' => 'btn btn-danger',
					'title' => $label,
					'aria-label' => $label,
					'data-confirm' => $confirm
		]);
	}

	public static function getCompanyBalanceColumn($template = '{view}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'view' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-eye-open'/>", ['view', 'id' => get_class($model) == 'app\models\TrCompanyBalance' ? $model->companyID : $model->companyID], [
								'title' => 'view',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getMasterProductColumn($template = '{view} {update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					if ($model->flagActive == 0) {
						$url = ['restore', 'id' => $model->primaryKey];
						$icon = 'repeat';
						$label = 'Cancel Delete';
						$confirm = 'Are you sure you want to activate this data ?';
					} else {
						$connection = Yii::$app->db;
						$sql = "SELECT b.barcodeNumber,a.productName
						FROM ms_product a
						JOIN ms_productdetail b on a.productID = b.productID
						JOIN tr_purchaseorderdetail c on b.barcodeNumber = c.barcodeNumber
						JOIN tr_salesorderdetail d on b.barcodeNumber = d.barcodeNumber
						WHERE a.productID = '" . $model->productID . "' ";
						$temp = $connection->createCommand($sql);
						$headResult = $temp->queryAll();
						$count = count($headResult);

						$url = ['delete', 'id' => $model->primaryKey];
						$icon = 'trash';
						$label = 'Delete';
						if ($count > 0) {
							$confirm = 'Data Cannot be deleted because Product Name has been created for other transactions ?';
						} else {
							$confirm = 'Are you sure you want to delete this data ?';
						}
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getActionJob($template = '{view} {update} {delete} {budget} {finish}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'width' => '10%',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {

					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Reject';
					if ($model->status > 1) {
						$confirm = 'Data cannot be rejected because it has been created for other transactions !';
					} else {
						$confirm = 'Are you sure you want to reject this data ?';
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					if ($model->status > 1) {
						$url = ['update', 'id' => $model->primaryKey];
						$icon = 'pencil';
						$label = 'Update';
						$confirm = 'Data cannot be updated because it has been created for other transactions !';
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
									'title' => 'Update',
									'class' => 'open-modal-btn'
						]);
					}
				},
				'budget' => function ($url, $model) {
					if ($model->status > 1) {
						$url = ['budget', 'id' => $model->primaryKey];
						$icon = 'level-up';
						$label = 'Budget';
						$confirm = 'Data cannot be created budget because status job it is not New !';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-level-up'/>", ['budget', 'id' => $model->primaryKey], [
									'title' => 'Budget',
									'class' => 'open-modal-btn'
						]);
					}
				},
				'finish' => function ($url, $model) {
					$url = ['finish', 'id' => $model->primaryKey];
					$icon = 'check';
					$label = 'Finished';
					if ($model->status < 5 || $model->status > 6) {
						$confirm = 'Data cannot be finished because status job it is not half invoice and full invoice !';
					} else {
						$confirm = 'Are you sure you want to finished this data ?';
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				}
			]
		];
	}

	public static function getActionProposal($template = '{view} {update} {approve} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'width' => '10%',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					$connection = Yii::$app->db;
					$sql = "SELECT a.proposalNum, b.jobID
					FROM tr_proposalhead a
					JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
					where a.proposalNum = '" . $model->proposalNum . "' ";
					$command = $connection->createCommand($sql);
					$command->execute();
					$headResult = $command->queryAll();

					foreach ($headResult as $detailMenu) {
						$model->jobIDs = $detailMenu['jobID'];
					}

					if ($model->status > 0) {
						$url = ['delete', 'id' => $model->primaryKey];
						$icon = 'trash';
						$label = 'Delete';
						$confirm = 'proposal cannot be deleted because other proposal status is approve!';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					}

					$connection = Yii::$app->db;
					$sql = "  
					SELECT a.proposalNum, b.jobID, a.status
					FROM tr_proposalhead a
					JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
					WHERE a.proposalNum <> '" . $model->proposalNum . "'
					AND b.jobID = '" . $model->jobIDs . "' AND a.status = 1 ";
					$temp = $connection->createCommand($sql);
					$headResult = $temp->queryAll();
					$count = count($headResult);

					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					if ($count > 0) {
						$confirm = 'proposal cannot be deleted because other proposal status is approve!';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						$confirm = 'Are you sure you want to delete this data ?';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					}
				},
				'update' => function ($url, $model) {
					if ($model->status == 1) {
						$connection = Yii::$app->db;
						$sql = "SELECT a.proposalNum, b.jobID
						FROM tr_proposalhead a
						JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
						where a.proposalNum = '" . $model->proposalNum . "' ";
						$command = $connection->createCommand($sql);
						$command->execute();
						$headResult = $command->queryAll();

						foreach ($headResult as $detailMenu) {
							$model->jobIDs = $detailMenu['jobID'];
						}

						$connection = Yii::$app->db;
						$sql = "  
						SELECT b.proposalNum, c.projectName, c.status
						FROM tr_proposalhead a
						JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
						JOIN tr_job c on b.jobID = c.jobID
						WHERE b.proposalNum <> '" . $model->proposalNum . "' AND 
						c.jobID = " . $model->jobIDs . " AND c.status IN (5,6,7) ";
						$temp = $connection->createCommand($sql);
						$headResult = $temp->queryAll();
						$count = count($headResult);
						$url = ['revision', 'id' => $model->primaryKey];
						$icon = 'file';
						$label = 'Revision';
						if ($count > 0) {
							$confirm = 'proposal cannot be edited because other job has been invoice!';
							return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
										'title' => $label,
										'aria-label' => $label,
										'data-confirm' => $confirm,
										'data-method' => 'post',
										'data-pajax' => '0'
							]);
						} else {
							return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
										'title' => $label,
										'aria-label' => $label,
										'data-method' => 'post',
										'data-pajax' => '0'
							]);
						}
					} elseif ($model->status == 0) {
						$connection = Yii::$app->db;
						$sql = "SELECT a.proposalNum, b.jobID
						FROM tr_proposalhead a
						JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
						where a.proposalNum = '" . $model->proposalNum . "' ";
						$command = $connection->createCommand($sql);
						$command->execute();
						$headResult = $command->queryAll();

						foreach ($headResult as $detailMenu) {
							$model->jobIDs = $detailMenu['jobID'];
						}

						$connection = Yii::$app->db;
						$sql = "  
						SELECT a.proposalNum, b.jobID, a.status
						FROM tr_proposalhead a
						JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
						WHERE a.proposalNum <> '" . $model->proposalNum . "'
						AND b.jobID = '" . $model->jobIDs . "' AND a.status = 1 ";
						$temp = $connection->createCommand($sql);
						$headResult = $temp->queryAll();
						$count = count($headResult);
						$url = ['update', 'id' => $model->primaryKey];
						$icon = 'pencil';
						$label = 'Update';
						if ($count > 0) {
							$confirm = 'proposal cannot be edited because other propsal status is approve!';
							return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
										'title' => $label,
										'aria-label' => $label,
										'data-confirm' => $confirm,
										'data-method' => 'post',
										'data-pajax' => '0'
							]);
						} else {
							return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
										'title' => $label,
										'aria-label' => $label,
										'data-method' => 'post',
										'data-pajax' => '0'
							]);
						}
					} else {
						
					}
				},
				'approve' => function ($url, $model) {
					$connection = Yii::$app->db;
					$sql = "SELECT a.proposalNum, b.jobID
					FROM tr_proposalhead a
					JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
					where a.proposalNum = '" . $model->proposalNum . "' ";
					$command = $connection->createCommand($sql);
					$command->execute();
					$headResult = $command->queryAll();

					foreach ($headResult as $detailMenu) {
						$model->jobIDs = $detailMenu['jobID'];
					}

					if ($model->status > 0) {
						$url = ['approve', 'id' => $model->primaryKey];
						$icon = 'check';
						$label = 'Approve';
						$confirm = 'proposal cannot be approved because other job has been invoice!';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					}

					$connection = Yii::$app->db;
					$sql = "  
					SELECT b.proposalNum, c.projectName, c.status
					FROM tr_proposalhead a
					JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
					JOIN tr_job c on b.jobID = c.jobID
					WHERE b.proposalNum <> '" . $model->proposalNum . "' AND 
					c.jobID = " . $model->jobIDs . " AND c.status IN (5,6,7) ";
					$temp = $connection->createCommand($sql);
					$headResult = $temp->queryAll();
					$count = count($headResult);

					$url = ['approve', 'id' => $model->primaryKey];
					$icon = 'check';
					$label = 'Approve';
					if ($count > 0) {
						$confirm = 'proposal cannot be approved because other job has been invoice!';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					}
				}
			]
		];
	}

	public static function getActionBudget($template = '{view} {update} {delete} {proposal}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {

					$connection = Yii::$app->db;
					$sql = "SELECT *
					FROM tr_budgethead a
					JOIN tr_job  b on a.jobID =b.jobID
					WHERE a.jobID = '" . $model->jobID . "' AND b.status > 2";
					$command = $connection->createCommand($sql);
					$command->execute();
					$headResult = $command->queryAll();
					$count = count($headResult);

					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					if ($count > 0) {
						$confirm = 'Data cannot be deleted because it has been created for other transactions !';
					} else {
						$confirm = 'Are you sure you want to delete this data ?';
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {

					$connection = Yii::$app->db;
					$sql = "SELECT *
					FROM tr_budgethead a
					JOIN tr_job  b on a.jobID =b.jobID
					WHERE a.jobID = '" . $model->jobID . "' AND b.status > 2";
					$command = $connection->createCommand($sql);
					$command->execute();
					$headResult = $command->queryAll();
					$count = count($headResult);
					if ($count > 0) {
						$url = ['update', 'id' => $model->primaryKey];
						$icon = 'pencil';
						$label = 'Update';
						$confirm = 'Data cannot be updated because it has been created for other transactions !';
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
									'title' => 'Update',
									'class' => 'open-modal-btn'
						]);
					}
				},
				'proposal' => function ($url, $model) {

					$connection = Yii::$app->db;
					$sql = "SELECT b.clientID, b.status
					FROM tr_budgethead a
					JOIN tr_job b on a.jobID = b.jobID
					JOIN ms_client c on b.clientID = c.clientID
					where a.jobID = " . $model->jobID . " ";
					$command = $connection->createCommand($sql);
					$command->execute();
					$headResult = $command->queryAll();

					foreach ($headResult as $detailMenu) {
						$model->statusData = $detailMenu['status'];
					}

					if ($model->statusData > 2) {
						$url = ['proposal', 'id' => $model->primaryKey];
						$icon = 'level-up';
						$label = 'Proposal';
						$confirm = 'Data cannot be created proposal because status job it is not budget !';
						return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-level-up'/>", ['proposal', 'id' => $model->primaryKey], [
									'title' => 'Proposal',
									'class' => 'open-modal-btn'
						]);
					}
				}
			]
		];
	}

	public static function getActionPayment($template = '{view} {update} {delete} {approve}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					$confirm = 'Are you sure you want to delete this data ?';
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
						'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Update',
								'class' => 'open-modal-btn'
					]);
				},
						'approve' => function ($url, $model) {
					if ($model->status > 1) {
						$url = ['approve', 'id' => $model->primaryKey];
						$icon = 'check';
						$label = 'Approve';
						$confirm = 'Data cannot be approved because status it is not new !';
						return Html::a("<span class='glyphicon glyphicon-check'/>", $url, [
									'title' => $label,
									'aria-label' => $label,
									'data-confirm' => $confirm,
									'data-method' => 'post',
									'data-pajax' => '0'
						]);
					} else {
						return Html::a("<span class='glyphicon glyphicon-check'/>", ['approve', 'id' => $model->primaryKey], [
									'title' => 'Approve',
									'class' => 'open-modal-btn'
						]);
					}
				}
			]
		];
	}

	public static function getMasterClientColumn($template = '{view} {update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {
					if ($model->flagActive == 0) {
						$url = ['restore', 'id' => $model->primaryKey];
						$icon = 'repeat';
						$label = 'Cancel Delete';
						$confirm = 'Are you sure you want to activate this data ?';
					} else {
						$connection = Yii::$app->db;
						$sql = "SELECT a.clientID, b.picClientID
						FROM ms_client a
						JOIN ms_picclient b on a.clientID = b.clientID
						JOIN tr_job c on b.picClientID = c.picClientID
						WHERE a.clientID = '" . $model->clientID . "' ";
						$temp = $connection->createCommand($sql);
						$headResult = $temp->queryAll();
						$count = count($headResult);

						$url = ['delete', 'id' => $model->primaryKey];
						$icon = 'trash';
						$label = 'Delete';
						if ($count > 0) {
							$confirm = 'Data Cannot be deleted because has been created for other transactions ?';
						} else {
							$confirm = 'Are you sure you want to delete this data ?';
						}
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Update',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getCompanyColumn($template = '{update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {

					$connection = Yii::$app->db;
					$sql = "SELECT *FROM ms_user 
					WHERE companyID = '" . $model->companyID . "' ";
					$temp = $connection->createCommand($sql);
					$headResult = $temp->queryAll();
					$count = count($headResult);

					$url = ['delete', 'id' => $model->primaryKey];
					$icon = 'trash';
					$label = 'Delete';
					if ($count > 0) {
						$confirm = 'Data Cannot be deleted because Data Company has been created for other transactions ?';
					} else {
						$confirm = 'Are you sure you want to delete this data ?';
					}

					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getCategoryColumn($template = '{view} {update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {

					if ($model->flagActive == 0) {
						$url = ['restore', 'id' => $model->primaryKey];
						$icon = 'repeat';
						$label = 'Cancel Delete';
						$confirm = 'Are you sure you want to activate this data ?';
					} else {
						$connection = Yii::$app->db;
						$sql = "SELECT *FROM ms_product 
						WHERE categoryID = '" . $model->categoryID . "' ";
						$temp = $connection->createCommand($sql);
						$headResult = $temp->queryAll();
						$count = count($headResult);

						$url = ['delete', 'id' => $model->primaryKey];
						$icon = 'trash';
						$label = 'Delete';
						if ($count > 0) {
							$confirm = 'Data Cannot be deleted because has been created for other transactions ?';
						} else {
							$confirm = 'Are you sure you want to delete this data ?';
						}
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getAssetCategoryColumn($template = '{view} {update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {

					if ($model->flagActive == 0) {
						$url = ['restore', 'id' => $model->primaryKey];
						$icon = 'repeat';
						$label = 'Cancel Delete';
						$confirm = 'Are you sure you want to activate this data ?';
					} else {
						$connection = Yii::$app->db;
						$sql = "SELECT *FROM tr_assetpurchasedetail 
						WHERE assetCategoryID = '" . $model->assetCategoryID . "' ";
						$temp = $connection->createCommand($sql);
						$headResult = $temp->queryAll();
						$count = count($headResult);

						$url = ['delete', 'id' => $model->primaryKey];
						$icon = 'trash';
						$label = 'Delete';
						if ($count > 0) {
							$confirm = 'Data Cannot be deleted because has been created for other transactions ?';
						} else {
							$confirm = 'Are you sure you want to delete this data ?';
						}
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
					]);
				}
			]
		];
	}

	public static function getDocumentColumn($template = '{view} {update} {delete}') {
		return [
			'class' => 'kartik\grid\ActionColumn',
			'template' => $template,
			'hAlign' => 'center',
			'vAlign' => 'middle',
			'header' => '',
			'buttons' => [
				'delete' => function ($url, $model) {

					if ($model->flagActive == 0) {
						$url = ['restore', 'id' => $model->primaryKey];
						$icon = 'repeat';
						$label = 'Cancel Delete';
						$confirm = 'Are you sure you want to activate this data ?';
					} else {
						$connection = Yii::$app->db;
						$sql = "SELECT *FROM tr_documenttrackinghead 
								WHERE documentID = '" . $model->documentID . "' ";
						$temp = $connection->createCommand($sql);
						$headResult = $temp->queryAll();
						$count = count($headResult);

						$url = ['delete', 'id' => $model->primaryKey];
						$icon = 'trash';
						$label = 'Delete';
						if ($count > 0) {
							$confirm = 'Data Cannot be deleted because has been created for other transactions ?';
						} else {
							$confirm = 'Are you sure you want to delete this data ?';
						}
					}
					return Html::a("<span class='glyphicon glyphicon-$icon'/>", $url, [
								'title' => $label,
								'aria-label' => $label,
								'data-confirm' => $confirm,
								'data-method' => 'post',
								'data-pajax' => '0'
					]);
				},
				'update' => function ($url, $model) {
					return Html::a("<span class='glyphicon glyphicon-pencil'/>", ['update', 'id' => $model->primaryKey], [
								'title' => 'Edit',
								'class' => 'open-modal-btn'
						]);
					}
				]
			];
		}
	}
                                                                                                                                                                                                        