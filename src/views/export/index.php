<?php

use kartik\export\ExportMenu;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\i18n\Formatter;

/**
 * @var string $schemaSelected
 * @var array $schemaValues
 * @var string $folder
 */
?>
<div class="box box-default">
    <div class="box-body">
        <h4><?= Yii::t('contact', 'Filter contact form schema:') ?></h4>
        <?php
        echo Html::beginForm(Url::to('/' . Yii::$app->requestedRoute), 'GET');
        echo Html::dropDownList(
            'schema',
            $schemaSelected,
            $schemaValues,
            ['onchange' => 'this.form.submit()']
        );
        echo Html::endForm();
        ?>
    </div>
</div>

<div class="box box-default">
    <div class="box-body">

        <?php
        echo ExportMenu::widget(
            [
                'dataProvider' => $dataProvider,
                'folder' => '@runtime/contact-export',
                'columns' => $columns,
                'formatter' => ['class' => Formatter::class, 'nullDisplay' => ''],
                'folder' => $folder,
                'exportConfig' => [
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_HTML => false,
                ]
            ]
        );


        echo GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'columns' => $columns,
                'layout' => "{pager}\n{summary}\n{items}\n{pager}",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover list-export'],
                'formatter' => ['class' => Formatter::class, 'nullDisplay' => '-'],
                'rowOptions' => function ($model, $key, $index) {
                    return ['key' => $key, 'index' => $index, 'class' => ($index % 2) ? 'odd' : 'even'];
                },

            ]
        );


        ?>
    </div>
</div>


