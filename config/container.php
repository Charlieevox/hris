<?php

Yii::$container->set('kartik\grid\GridView', [
    'pjax' => true,
    'pjaxSettings' => [
        'options' => [
            'enablePushState' => false
        ]
    ]
]);