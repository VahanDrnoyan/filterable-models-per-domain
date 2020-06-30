<?php

namespace Vdrnoyan\TenantModelFilter;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];
}
