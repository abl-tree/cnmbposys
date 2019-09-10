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
     * A generic fetch function to retrieve data
     * based on provided parameters
     *
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
                if (is_array($conditions['value']) && $conditions['operator'] == '=') {
                    $model = $model->whereIn($conditions['target'], $conditions['value']);
                } else if (is_array($conditions['value']) && $conditions['operator'] == '!=') {
                    $model = $model->whereNotIn($conditions['target'], $conditions['value']);
                } else {
                    $model = $model->where($conditions['target'], $conditions['operator'], $conditions['value']);
                }
            }
        }

        // Start ORWHERE clauses
        if (isset($data['orwhere'])) {
            foreach ((array) $data['orwhere'] as $key => $conditions) {
                if (is_array($conditions['value']) && $conditions['operator'] == '=') {
                    $model = $model->orWhereIn($conditions['target'], $conditions['value']);
                } else if (is_array($conditions['value']) && $conditions['operator'] == '!=') {
                    $model = $model->orWhereNotIn($conditions['target'], $conditions['value']);
                } else {
                    $model = $model->orWhere($conditions['target'], $conditions['operator'], $conditions['value']);
                }
            }
        }

        // Start WHERENULL clauses
        if (isset($data['where_null'])) {
            foreach ((array) $data['where_null'] as $key => $conditions) {
                $model = $model->whereNull($conditions);
            }
        }

        // Start WHERENOTNULL clauses
        if (isset($data['where_not_null'])) {
            foreach ((array) $data['where_not_null'] as $key => $conditions) {
                $model = $model->whereNotNull($conditions);
            }
        }

        if (isset($data['wherehas_by_relations'])) {
            $model = $model->whereHas($data['wherehas_by_relations']['target'], $data['wherehas_by_relations']['query']);
        }

        // Start WHEREHAS clauses
        if (isset($data['wherehas'])) {
            foreach ((array) $data['wherehas'] as $key => $conditions) {
                $model = $model->whereHas($conditions['relation'], function ($q) use ($conditions) {
                    foreach ((array) $conditions['target'] as $key => $value) {
                        if (isset($value['operator'])) {
                            $q->where($value['column'], $value['operator'], $value['value']);
                        } else {
                            $q->where($value['column'], $value['value']);
                        }
                    }
                });
            }
        }

        // Start ORWHEREHAS clauses
        if (isset($data['orwherehas'])) {
            foreach ((array) $data['orwherehas'] as $key => $conditions) {
                $model = $model->orWhereHas($conditions['relation'], function ($q) use ($conditions) {
                    foreach ($conditions['target'] as $key => $value) {
                        $q->where($value['column'], $value['value']);
                    }
                });
            }
        }

        if (isset($data['where_year'])) {
            foreach ((array) $data['where_year'] as $key => $conditions) {
                $model = $model->whereYear($conditions['target'], $conditions['operator'], $conditions['value']);
            }
        }

        if (isset($data['where_between'])) {
            foreach ((array) $data['where_between'] as $key => $conditions) {
                $model = $model->whereBetween($conditions['target'], $conditions['value']);
            }
        }

        //End WHERE Clauses

        if (isset($data['limit']) && $data['limit'] && is_numeric($data['limit'])) {
            $model = $model->take($data['limit']);
        }

        if (isset($data['offset']) && $data['offset'] && is_numeric($data['offset'])) {
            $model = $model->offset($data['offset']);
        }

        if (isset($data['sort']) && !in_array($data['sort'], $this->no_sort)) {
            $model = $model->orderBy($data["sort"], $data['order']);
        }

        if (isset($data['with_count'])) {
            $model = $model->withCount($data['with_count']);
        }

        if (isset($data['relations'])) {
            $model = $model->with($data['relations']);
        }

        if (isset($data['groupby'])) {
            foreach ((array) $data['groupby'] as $key => $value) {
                $model = $model->groupBy($value);
            }
        }

        // dd( dump_query ( $model) );

        if (isset($data['count']) && $data['count'] === true) {
            return $model->get()->count();
        }

        if (isset($data['single']) && $data['single'] === true) {
            $result = $model->get()->first();
        } else if (isset($data['no_all_method']) && $data['no_all_method'] === true) {
            $result = $model->get();
        } else if (isset($data['sort']) && in_array($data['sort'], $this->no_sort)) {
            $result = $model->get();

            if (in_array($data['sort'], $this->no_sort)) {
                if (isset($data['order']) && $data['order'] == 'desc') {
                    $result = $result->sortByDesc($data['sort'])->values()->all();
                } else {
                    $result = $result->sortBy($data['sort'])->values()->all();
                }
            }
        } else {
            $result = $model->get()->all();
        }

        return $result;
    }

    /**
     * Counts the number of elements in a given data set.
     *
     * @param $data
     * @param $model
     * @return null
     */
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

        if (isset($data['search']) && $data['search'] == true) {
            return $this->genericSearch($data, $model);
        } else {
            return $this->fetchGeneric($data, $model);
        }

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
            $key = null;
            if (isset($data['target'])) {
                foreach ((array) $data['target'] as $column) {
                    if (array_key_exists(1, $data['target'])) {
                        $key = json_encode($data['target'][1]);
                    }

                    if ($query->getModel()->isSearchable($column)) {

                        if (strpos($key, 'firstname') !== false) {
                            if (str_contains($column, ".")) {
                                $search_components = explode(".", $column);

                                $query = $query->with($search_components[0]);

                                $query = $query->orWhereHas($search_components[0], function ($q) use ($data, $column, $search_components) {
                                    $q->whereRaw("CONCAT(firstname,' ',middlename,' ',lastname) like ?", ["%{$data['query']}%"])->orWhereRaw("CONCAT(firstname,' ',lastname) like ?", ["%{$data['query']}%"]);
                                });
                            } else {
                                try {
                                    $query = $query->whereRaw("CONCAT(firstname,' ',middlename,' ',lastname) like ?", ["%{$data['query']}%"])->orWhereRaw("CONCAT(firstname,' ',lastname) like ?", ["%{$data['query']}%"]);
                                } catch (\Illuminate\Database\QueryException $ex) {
                                    echo ($ex->getMessage());

                                }
                            }

                        } else if (str_contains($column, ".")) {
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
                if (is_array($conditions['value']) && $conditions['operator'] == '=') {
                    $model = $model->whereIn($conditions['target'], $conditions['value']);
                } else if (is_array($conditions['value']) && $conditions['operator'] == '!=') {
                    $model = $model->whereNotIn($conditions['target'], $conditions['value']);
                } else {
                    $model = $model->where($conditions['target'], $conditions['operator'], $conditions['value']);
                }
            }
        }

        // Start WHEREHAS clauses
        if (isset($data['wherehas'])) {
            foreach ((array) $data['wherehas'] as $key => $conditions) {
                $model = $model->whereHas($conditions['relation'], function ($q) use ($conditions) {
                    foreach ((array) $conditions['target'] as $key => $value) {
                        if (isset($value['operator'])) {
                            $q->where($value['column'], $value['operator'], $value['value']);
                        } else {
                            $q->where($value['column'], $value['value']);
                        }
                    }
                });
            }
        }

        // Start WHERENULL clauses
        if (isset($data['where_null'])) {
            foreach ((array) $data['where_null'] as $key => $conditions) {
                $model = $model->whereNull($conditions);
            }
        }

        // Start WHERENOTNULL clauses
        if (isset($data['where_not_null'])) {
            foreach ((array) $data['where_not_null'] as $key => $conditions) {
                $model = $model->whereNotNull($conditions);
            }
        }

        if (isset($data['limit']) && $data['limit'] && is_numeric($data['limit'])) {
            $model = $model->take($data['limit']);
        }

        if (isset($data['offset']) && $data['offset'] && is_numeric($data['offset'])) {
            $model = $model->offset($data['offset']);
        }

        if (isset($data['sort']) && !in_array($data['sort'], $this->no_sort)) {
            $model = $model->orderBy($data["sort"], $data['order']);
        }

        if (isset($data['relations'])) {
            $model = $model->with($data['relations']);
        }

        // dd( dump_query ( $model) );

        if (isset($data['count']) && $data['count'] === true) {
            return $model->get()->count();
        }

        // dd( dump_query ( $model), $data );
        return $model;
    }

    /**
     * Creates search term used by genericSearch
     *
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
