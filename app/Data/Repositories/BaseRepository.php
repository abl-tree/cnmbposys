<?php

namespace App\Data\Repositories;

use App\Data\Models\BaseModel;
use Common\Traits\Response;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    use \Common\Traits\Response;

    protected $no_sort = [];

    /**
     * @param $data
     * @param \App\Data\Models\BaseModel $model
     * @return null
     */
    protected function fetchGeneric($data, &$model)
    {
        $result = null;

        if (isset($data['columns'])) {
            if (is_array($data['columns']) ||
                ($data['columns'] !== "" && !is_array($data['columns']))) {
                $model = $model->select($data['columns']);
            }
        }

        // Start WHERE clauses
        if (isset($data['where'])) {
            foreach ((array) $data['where'] as $key => $conditions) {
                $model = $model->where($conditions['target'], $conditions['operator'], $conditions['value']);
            }
        }

        //End WHERE Clauses

        if (isset($data['limit']) && $data['limit'] && is_numeric($data['limit'])) {
            $model = $model->take($data['limit']);
        }

        if (isset($data['offset']) && $data['offset'] && is_numeric($data['offset'])) {
            $model = $model->offset($data['offset']);
        }

        if (isset($data['sort']) && !in_array( $data['sort'], $this->no_sort )) {
            $model = $model->orderBy($data["sort"], $data['order']);
        }

        if( isset( $data['relations'] ) ){
            $model = $model->with( $data['relations'] );
        }

        // dd( dump_query ( $model) );

        if (isset($data['count']) && $data['count'] === true) {
            return $model->get()->count();
        }

        if (isset($data['single']) && $data['single'] === true) {
            $result = $model->first();
        } else if (isset($data['no_all_method']) && $data['no_all_method'] === true) {
            $result = $model->get();
        } else {
            $result = $model->get()->all();
        }

        return $result;
    }

    public function countData($data, $model)
    {
        $remove = [
            'single',
            'offset',
            'limit',
        ];

        foreach ($data as $key => $value) {
            if (in_array($key, $remove)) {
                unset($data[$key]);
            }
        }

        $data['count'] = true;

        return $this->fetchGeneric($data, $model);
    }

    /**
     * Builds on top of existing Builder query and returns appropriate "search-like" query
     * Accepts params of `target` for specifying which columns to search
     * Note that you should define the `searchable` columns inside your model's $searchable property
     * Also accepts params of `order` for ordering based on a specific column
     * Also accepts `terms[column]` for specifying search term type
     *
     * @param $data
     * @param Builder|BaseModel $model
     * @return Builder
     */
    protected function genericSearch($data, $model)
    {

        $model = $model->where(function ($query) use ($data, $model) {
            if (isset($data['target'])) {
                foreach ((array) $data['target'] as $column) {
                    if ($query->getModel()->isSearchable($column)) {
                        if (str_contains($column, ".")) {
                            $search_components = explode(".", $column);

                            $query = $query->with($search_components[0]);

                            $query = $query->orWhereHas($search_components[0], function ($q) use ($data, $column, $search_components) {
                                $q->where($search_components[1], "LIKE", $this->generateSearchTerm($data, $column));
                            });
                        } else {
                            $query = $query->orWhere($column, "LIKE", $this->generateSearchTerm($data, $column));
                        }
                    }
                }
            }

            if (isset($data['order'])) {
                foreach ((array) $data['order'] as $column => $order) {
                    $query = $query->orderBy($column, $order);
                }
            }

        });

        if (isset($data['where'])) {
            foreach ((array) $data['where'] as $key => $conditions) {
                $model = $model->where($conditions['target'], $conditions['operator'], $conditions['value']);
            }
        }

        if (isset($data['limit']) && $data['limit'] && is_numeric($data['limit'])) {
            $model = $model->take($data['limit']);
        }

        if (isset($data['offset']) && $data['offset'] && is_numeric($data['offset'])) {
            $model = $model->offset($data['offset']);
        }

        if (isset($data['sort']) && !in_array( $data['sort'], $this->no_sort )) {
            $model = $model->orderBy($data["sort"], $data['order']);
        }

        if( isset( $data['relations'] ) ){
            $model = $model->with( $data['relations'] );
        }

        // dd( dump_query ( $model), $data );
        return $model;
    }

    /**
     * @param array $data
     * @param string $column
     * @return string
     */
    protected function generateSearchTerm($data, $column = "")
    {
        $term = "%" . $data['query'] . "%";

        if (isset($data['term'][$column])) {
            switch ($data['term'][$column]) {
                case "left":
                    $term = "%" . $data['query'];
                    break;
                case "right":
                    $term = $data['query'] . "%";
                    break;
                case "none":
                    $term = $data['query'];
                    break;
            }
        }

        return $term;
    }
}
