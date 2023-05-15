<?php

use Illuminate\Database\Eloquent\Builder;

Builder::macro('deleteOrCreate', function ($attributes, $values) {
    if (!is_null($instance = $this->where($attributes)->first())) {
        $instance->forceDelete();
        $instance->action_performed = "REMOVED";
        return $instance;
    }

    return tap($this->newModelInstance(array_merge($attributes, $values)), function ($instance) {
        $instance->save();
        $instance->action_performed = "INSERTED";
    });
});
