<?php


namespace Vdrnoyan\TenantModelFilter\Contracts;


use Illuminate\Database\Eloquent\Model;

interface DomainMakerInterface
{
    public function makeCurrent($domain_id);
    public function getCurrent():Model;

}
