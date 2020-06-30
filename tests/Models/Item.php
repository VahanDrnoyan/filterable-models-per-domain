<?php

namespace Vdrnoyan\TenantModelFilter\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Vdrnoyan\TenantModelFilter\Contracts\PerDomainFilterableInterface;
use Vdrnoyan\TenantModelFilter\PerDomainFilterable;

class Item extends Model
{
    use PerDomainFilterable;

    protected $guarded = [];

    public $timestamps = false;
}
