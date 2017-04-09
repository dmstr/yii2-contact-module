<?php
/**
 * /app/src/../runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace dmstr\modules\contact\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dmstr\modules\contact\models\ContactLog as ContactLogModel;

/**
 * ContactLog represents the model behind the search form about `dmstr\modules\contact\models\ContactLog`.
 */
class ContactLog extends ContactLogModel
{

	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function rules() {
		return [
			[['id'], 'integer'],
			[['schema', 'json', 'created_at'], 'safe'],
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
		$query = ContactLogModel::find();

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
			]);

		$query->andFilterWhere(['like', 'schema', $this->schema])
		->andFilterWhere(['like', 'json', $this->json]);

		return $dataProvider;
	}


}
