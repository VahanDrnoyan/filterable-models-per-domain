<?php

namespace Vdrnoyan\TenantModelFilter;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];
    public function filterOwn($className){
        return $this->morphedByMany($className, 'filterables');
    }
}
