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

        if (isset($data['join'])) {
            foreach ((array) $data['join'] as $key => $conditions) {
                $model = $model->join($conditions['table1'], $conditions['table1.column'], $conditions['operator'], $conditions['table2.column'], $conditions['join_clause']);
            }
        }

        // Start WHERE clauses
        if (isset($data['where'])) {
            foreach ((array) $data['where'] as $key => $conditions) {
                $model = $model->where($conditions['target'], $conditions['operator'], $conditions['value']);
            }
        }

        if (isset($data['whereNull'])) {
            foreach ((array) $data['whereNull'] as $key => $conditions) {
                $model = $model->whereNull($conditions);
            }
        }

        if (isset($data['whereNotNull'])) {
            foreach ((array) $data['whereNotNull'] as $key => $conditions) {
                $model = $model->whereNotNull($conditions);
            }
        }

        if (isset($data['whereBetween'])) {
            foreach ((array) $data['whereBetween'] as $key => $conditions) {
                $model = $model->whereBetween($conditions['target'], [$conditions['from'], $conditions['to']]);
            }
        }

        if (isset($data['whereNotBetween'])) {
            foreach ((array) $data['whereNotBetween'] as $key => $conditions) {
                $model = $model->whereNotBetween($conditions['target'], [$conditions['from'], $conditions['to']]);
            }
        }

        if (isset($data['advanceWhere'])) {
            foreach ((array) $data['advanceWhere'] as $key => $conditions) {
                $model = $model->where(function ($query) use ($conditions){
                    if(isset($conditions['orderby'])) {
                        $query->whereBetween($conditions['target'], [$conditions['from'], $conditions['to']])
                                    ->orWhereNull($conditions['target'])
                                    ->orderBy($conditions['target'], $conditions['orderby']);
                    } else {
                        $query->whereBetween($conditions['target'], [$conditions['from'], $conditions['to']])
                                    ->orWhereNull($conditions['target']);
                    }
                });
            }
        }

        if (isset($data['whereDate'])) {
            foreach ((array) $data['whereDate'] as $key => $conditions) {
                $model = $model->whereDate($conditions['target'], $conditions['value']);
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

        if (isset($data['orderBy'])) {
            foreach ((array) $data['orderBy'] as $key => $conditions) {
                $model = $model->orderBy($conditions['target'], $conditions['option']);
            }
        }

        if (isset($data['groupby'])) {
            foreach ((array) $data['groupby'] as $key => $conditions) {
                $model = $model->groupBy($conditions);
            }
        }


        // dd( dump_query ( $model) );

        if (isset($data['count']) && $data['count'] === true) {
            return $model->get()->count();
        }

        if (isset($data['single']) && $data['single'] === true) {
            $result = $model->first();
        } else {
            $result = $model->get();
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
