<?php

namespace App\Models\Traits\Globals;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait Searchable
{
    public function scopeWhereAnyColumnLike($query, $term = '', $relations = array(), $morph_classes = array(), $is_morph = false)
    {
        $query->where(function ($query) use ($term) {
            $columns = [];
            if (isset($this->searchable_columns) && !empty($this->searchable_columns)) $columns = $this->searchable_columns;
            if (!$columns) $columns = Schema::getColumnListing($this->getTable());

            foreach ($columns as $column) {
                $query->orWhere($column, 'ILIKE', '%' . $term . '%');
            }
        });

        if (is_array($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->whereRelatedColumnsLike($relation, $term, $morph_classes, $is_morph);
            }
        } else if ($relations) {
            $query->whereRelatedColumnsLike($relations, $term, $morph_classes, $is_morph);
        }
        return $query;
    }


    public function scopeWhereRelatedColumnsLike($query, $relation, $term = '', $morph_classes = array(), $is_morph = false, $columns = array())
    {
        if($is_morph){

            $builded_relation_class = [];
            if (is_array($morph_classes) && !empty($morph_classes)) {
                foreach ($morph_classes as $morph_class) {
                    $relation_class =  'App\Models\\' . ucfirst(Str::singular($morph_class));
                    array_push($builded_relation_class,$relation_class);
                }
            } else if ($morph_classes) {
                $relation_class =  'App\Models\\' . ucfirst(Str::singular($morph_classes));
                array_push($builded_relation_class,$relation_class);
            }

            foreach($builded_relation_class as $instance)
            {
                $model = new $instance();
                $columns = [];
                if (method_exists($model, 'getSearchableColumns')) $columns = $model->getSearchableColumns();
                if (!$columns) $columns = Schema::getColumnListing($model->getTable());

                $query->orwhere(function ($query) use ($term, $columns, $relation,$instance) {
                    foreach ($columns as $column) {
                        if($column == 'id' || $column == 'created_by' || $column == 'updated_by' || $column == 'created_at' || $column == 'updated_at') continue;
                        $query->orWhereHasMorph($relation, $instance, function ($q)  use ($term,$column) {
                            $q->where($column, 'ILIKE', '%' . $term . '%');
                        });
                    }
                });
            }


        } else{

            $model_str = $this->{$relation}()->getRelated();
            $model = new $model_str();

            // $columns = empty($columns) ? Schema::getColumnListing($model->getTable()) : $columns;
            if (!$columns & method_exists($model, 'getSearchableColumns')) $columns = $model->getSearchableColumns();
            if (!$columns) $columns = Schema::getColumnListing($model->getTable());

            $query->orwhere(function ($query) use ($term, $columns, $relation) {
                foreach ($columns as $column) {
                    if($column == 'id' || $column == 'created_by' || $column == 'updated_by' || $column == 'created_at' || $column == 'updated_at') continue;
                    $query->orWhereHas($relation, function ($q) use ($column, $term) {
                        $q->where($column, 'ILIKE', '%' . $term . '%');
                    });
                }
            });
        }
        return $query;
    }

    public function scopeWhereAnyColumnStartWith($query, $term = '', $relations = array())
    {
        $query->where(function ($query) use ($term) {
            $columns = [];
            if (isset($this->searchable_columns) && !empty($this->searchable_columns)) $columns = $this->searchable_columns;
            if (!$columns) $columns = Schema::getColumnListing($this->getTable());

            foreach ($columns as $column) {
                $query->orWhere($column, 'ILIKE', $term . '%');
            }
        });

        if (is_array($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->whereRelatedColumnsLike($relation, $term);
            }
        } else if ($relations) {
            $query->whereRelatedColumnsLike($relations, $term);
        }
        return $query;
    }
}
