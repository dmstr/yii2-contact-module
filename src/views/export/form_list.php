<?php

use dmstr\modules\contact\controllers;
use kartik\export\ExportMenu;
//use kartik\grid\GridView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 */
?>
<div class="box box-default" style="overflow-y: auto;">
    <div class="box-body">
        <h4><?= Yii::t('contact', 'Filter contact form schema:') ?></h4>
        <?php
        $url = Url::to('/' . \Yii::$app->requestedRoute);
        echo Html::beginForm($url, 'GET');
        echo HTML::dropDownList(
            'schema',
            $schemaSelected,
            $schemaValues,
            ['onchange' => 'this.form.submit()']
        );
        echo Html::endForm();
        ?>
    </div>
</div>

<div class="box box-default" style="overflow-y: auto;">
    <div class="box-body">

        <?php
        echo ExportMenu::widget(
            [
                'dataProvider'       => $dataProvider,
                'columns'            => $columns,
                # don't use, value != 0 generate undefined key error...
                #'batchSize' => 0,
                'formatter'          => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                'exportConfig'       => [
                    ExportMenu::FORMAT_PDF     => Yii::$app->settings->get('export', 'contact'),
                    ExportMenu::FORMAT_EXCEL   => Yii::$app->settings->get('export', 'contact'),
                    ExportMenu::FORMAT_EXCEL_X => Yii::$app->settings->get('export', 'contact'),
                    ExportMenu::FORMAT_TEXT    => Yii::$app->settings->get('export', 'contact'),
                    ExportMenu::FORMAT_HTML    => Yii::$app->settings->get('export', 'contact'),
                ],
                'showColumnSelector' => Yii::$app->settings->get('toggleExportCol', 'contact'),
            ]
        );


        echo GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'columns'      => $columns,
                'layout'       => "{pager}\n{summary}\n{items}\n{pager}",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover list-export'],
                'formatter'    => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
                'rowOptions'   => function ($model, $key, $index, $grid) {
                    $rowStyle = $index % 2 ? 'odd' : 'even';
                    return array('key' => $key, 'index' => $index, 'class' => $rowStyle);
                },

            ]
        );


        ?>
    </div>
</div>


