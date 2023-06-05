<?php
/**
 * /app/runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace dmstr\modules\contact\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dmstr\modules\contact\models\ContactTemplate as ContactTemplateModel;

/**
 * ContactTemplate represents the model behind the search form about `dmstr\modules\contact\models\ContactTemplate`.
 */
class ContactTemplate extends ContactTemplateModel
{

	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function rules() {
		return [
			[['id'], 'integer'],
			[['name', 'from_email', 'reply_to_email', 'to_email', 'email_subject', 'form_schema', 'return_path', 'reply_to_schema_property','created_at', 'updated_at'], 'safe'],
		];
	}


	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}


	/**
	 * Creates data provider instance with search query applied
	 *
	 *
	 * @param array   $params
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = ContactTemplateModel::find();

		$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere([
				'id' => $this->id,
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at,
			]);

		$query->andFilterWhere(['like', 'name', $this->name])
		->andFilterWhere(['like', 'from_email', $this->from_email])
		->andFilterWhere(['like', 'reply_to_email', $this->reply_to_email])
		->andFilterWhere(['like', 'to_email', $this->to_email])
        ->andFilterWhere(['like', 'email_subject', $this->email_subject])
        ->andFilterWhere(['like', 'return_path', $this->return_path])
        ->andFilterWhere(['like', 'reply_to_schema_property', $this->reply_to_schema_property])
		->andFilterWhere(['like', 'form_schema', $this->form_schema]);

		return $dataProvider;
	}


}
