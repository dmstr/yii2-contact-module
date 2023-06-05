<?php
/**
 * /app/runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */


use dmstr\activeRecordPermissions\AccessInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\contact\models\ContactTemplate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="contact-template-form">

    <?php $form = ActiveForm::begin([
            'id' => 'ContactTemplate',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    //'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]
    );
    ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>


            <!-- attribute name -->
            <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <!-- attribute from_email -->
            <?php echo $form->field($model, 'from_email')->textInput(['maxlength' => true]) ?>

            <!-- attribute to_email -->
            <?php echo $form->field($model, 'to_email')->textInput(['maxlength' => true]) ?>

            <!-- attribute captcha -->
            <?php echo $form->field($model, 'captcha')->dropDownList(
                dmstr\modules\contact\models\ContactTemplate::optscaptcha()
            ); ?>

            <!-- attribute reply_to_email -->
            <?php echo $form->field($model, 'reply_to_email')->textInput(['maxlength' => true]) ?>

            <!-- attribute reply_to_schema_property -->
            <?php echo $form->field($model, 'reply_to_schema_property')->textInput(['maxlength' => true]) ?>

            <!-- attribute return_path -->
            <?php echo $form->field($model, 'return_path')->textInput(['maxlength' => true]) ?>

            <!-- attribute email_subject -->
            <?php echo $form->field($model, 'email_subject')->textInput(['maxlength' => true]) ?>

            <!-- attribute form_schema -->
            <?php echo $form->field($model, 'form_schema')->textarea(['rows' => 6]) ?>

            <!-- attribute created_at -->
            <?php echo $form->field($model, 'created_at')->textInput() ?>

            <!-- attribute updated_at -->
            <?php echo $form->field($model, 'updated_at')->textInput() ?>

            <!-- attribute reply_to_email -->
            <?php echo $form->field($model, 'reply_to_email')->textInput(['maxlength' => true]) ?>

            <!-- attribute email_subject -->
            <?php echo $form->field($model, 'email_subject')->textInput(['maxlength' => true]) ?>

            <?php echo AccessInput::widget([
                'form' => $form,
                'model' => $model
            ]) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?php echo
        Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => Yii::t('models', 'ContactTemplate'),
                        'content' => $this->blocks['main'],
                        'active' => true,
                    ],
                ]
            ]
        );
        ?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?php echo Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> ' .
            ($model->isNewRecord ? Yii::t('cruds', 'Create') : Yii::t('cruds', 'Save')),
            [
                'id' => 'save-' . $model->formName(),
                'class' => 'btn btn-success'
            ]
        );
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
