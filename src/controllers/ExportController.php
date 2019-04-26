<?php

namespace dmstr\modules\contact\controllers;

use dmstr\modules\contact;
use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\models\ContactModel;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Export controller for the `contact` module
 */
class ExportController extends Controller
{

    protected $schemaSettings = [];

    /**
     * Restrict access permissions to users with Editor role
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'         => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->can('Editor');
                        }
                    ],
                ]
            ]
        ];
    }

    public function init()
    {
        $this->layout = '@backend/views/layouts/main';
        parent::init();
    }

    /**
     * @param string $schema
     *
     * @return string
     */
    public function actionIndex($schema = false)
    {

        $schemaValues = yii\helpers\ArrayHelper::map(
            ContactLog::find()->select('schema')->distinct()->all(),
            'schema',
            'schema'
        );
        in_array($schema, $schemaValues) ? $schemaSelected = $schema : $schemaSelected = false;
        $models = ContactLog::find()->where(['schema' => $schemaSelected])->orderBy('id')->asArray()->all();


        $dataDecoded = [];
        $columns     = ['id'];
        foreach ($models as $model) {
            if (empty($model['json'])) {
                continue;
            }

            $flat          = $this->flattenDataArray(json_decode($model['json'], 1));
            $columns       = array_unique(array_merge($columns, array_keys($flat)));
            $dataDecoded[] = array_merge(['id' => $model['id']], $flat);

        }

        $dataProvider = new yii\data\ArrayDataProvider(
            [
                'allModels'  => $dataDecoded,
                'pagination' => [
                    'pageSize' => 25,
                ],
            ]
        );

        return $this->render(
            'form_list',
            [
                'dataProvider'   => $dataProvider,
                'columns'        => $columns,
                'schemaValues'   => $schemaValues,
                'schemaSelected' => $schemaSelected,
            ]
        );

    }

    /**
     * @param array $data
     * @param string $parent
     *
     * @return array
     */
    protected function flattenDataArray($data, $parent = '')
    {
        $return = [];

        foreach ($data as $key => $value) {

            # TODO: init array ONCE ;-)
            $tmpKeys = [];
            if (!empty($parent)) {
                $tmpKeys[] = $parent;
            }

            $tmpKeys[] = $key;
            $longKey   = implode('.', $tmpKeys);

            if (is_array($value)) {
                $return = array_merge($return, $this->flattenDataArray($value, $longKey));
            } else {
                $return[$longKey] = trim($value);
            }
        }

        return $return;
    }

}
