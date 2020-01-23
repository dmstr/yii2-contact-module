<?php

namespace dmstr\modules\contact\controllers;

use dmstr\modules\contact\models\ContactLog;
use dmstr\modules\contact\models\ContactTemplate;
use dmstr\modules\contact\Module;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * @property Module $module
 */
class ExportController extends Controller
{

    protected $schemaSettings = [];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['Editor']
                ]
            ]
        ];
        return $behaviors;
    }

    public function init()
    {
        parent::init();

        if (!Yii::$app->hasModule('gridview')) {
            throw new NotFoundHttpException('Page not found.');
        }
        $this->layout = $this->module->backendLayout;
    }

    /**
     * @param string $schema
     *
     * @return string
     */
    public function actionIndex($schema = null)
    {
        $models = ContactLog::find()
            ->alias('l')
            ->leftJoin(['t' => ContactTemplate::tableName()], 'contact_template_id = t.id')
            ->andFilterWhere(['name' => $schema])
            ->orderBy('l.id')
            ->asArray()
            ->all();

        $dataDecoded = [];
        $columns = ['id'];
        foreach ($models as $model) {
            if (empty($model['json'])) {
                continue;
            }

            $flat = $this->flattenDataArray(json_decode($model['json'], 1));
            $columns = array_unique(array_merge($columns, array_keys($flat)));
            $dataDecoded[] = array_merge(['id' => $model['id']], $flat);

        }

        $dataProvider = new ArrayDataProvider(
            [
                'allModels' => $dataDecoded,
                'pagination' => [
                    'pageSize' => 25,
                ],
            ]
        );

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'columns' => $columns,
                'schemaValues' => ArrayHelper::map(ContactTemplate::find()->all(), 'name', 'name'),
                'schemaSelected' => $schema ?: false,
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

            $tmpKeys = [];
            if (!empty($parent)) {
                $tmpKeys[] = $parent;
            }

            $tmpKeys[] = $key;
            $longKey = implode('.', $tmpKeys);

            if (is_array($value)) {
                $return = ArrayHelper::merge($return, $this->flattenDataArray($value, $longKey));
            } else {
                $return[$longKey] = trim($value);
            }
        }

        return $return;
    }

}
