<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Vdrnoyan\TenantModelFilter\Contracts\PerDomainFilterableInterface;
use Vdrnoyan\TenantModelFilter\Domain;

if(!function_exists('currentDomain')) {
    function currentDomain() {
        return app()->make('currentDomain');
    }
}
