<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class BaseModel extends Model
{
    use SoftDeletes, CascadeSoftDeletes; //use softdelete traits

    protected $rules       = [];
    protected $searchable  = [];
    protected $conversions = [];
    private $errors;

    public function searchableColumns()
    {
        return (array) $this->searchable;
    }

    public function isSearchable($target)
    {
        return in_array($target, $this->searchableColumns());
    }

    public function init($data = [], $model = null)
    {
        $model = $model == null ? $this : $model;

        $class     = $model->getClass();
        $new_model = new $class($data);

        if (!$new_model instanceof BaseModel) {
            return false;
        }

        return $new_model;
    }

    public function getClass()
    {
        return static::class;
    }

    public function pullFillable($data = [])
    {
        if (!$this->fillable || (is_array($this->fillable) && empty($this->fillable))) {
            $data = [];
        } else {
            $temp = [];

            foreach ((array) $data as $column => $value) {
                if (in_array($column, $this->fillable)) {
                    $temp[$column] = $value;
                }
            }

            $data = $temp;
        }

        return $data;
    }

    public function getRawSql($builder)
    {
        $sql = $builder->toSql();
        foreach ($builder->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $sql   = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }

    public function save(array $options = [])
    {
        if (!$this->validate($options)) {
            return false;
        }

        try {
            parent::fill($options);
            parent::save();

            return true;
        } catch (\Exception $e) {

            $this->errors = $e->getMessage();
            return false;
        }
    }

    public function validate($data)
    {
        // make a new validator object
        $validate = Validator::make($data, $this->rules);

        if ($validate->fails()) {
            // set errors and return false
            $this->errors = $validate->errors()->first();
            return false;
        }

        // validation pass

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    /**
     * Converts a user-end column to actual model column
     */
    public function convertFrom($from)
    {
        if (array_key_exists($from, $this->conversions)) {
            return $this->conversions[$from];
        }

        return $from;
    }

    /**
     * Converts a model-based column to user-end column
     */
    public function convertTo($to)
    {
        foreach ($this->conversions as $key => $value) {
            if ($value === $to) {
                return $key;
            }
        }
    }



    /**
     * @param \App\Data\Models\BaseModel $model
     * @param array $data
     * @return mixed
     */
    public function refreshModel( $model=null, $data=[] ){
        $model = $model === null ? $this : $model;

        return refresh_model( $model, $data );
    }
}
