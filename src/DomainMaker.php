<?php


namespace Vdrnoyan\TenantModelFilter;


use Illuminate\Database\Eloquent\Model;

class DomainMaker implements \Vdrnoyan\TenantModelFilter\Contracts\DomainMakerInterface
{

    public function makeCurrent($domain_id)
    {;
        app()->singleton('currentDomain', function () use ($domain_id) {
            $domain = Domain::where('id', $domain_id)->get()->first();
            $this->addToSession($domain);
            return $domain;
        });
    }
    public function getCurrent():Model
    {
        $host = request()->getHost();
         $domain = Domain::where('domain', $host)->get()->first();

        $this->addToSession($domain);
        return $domain;

    }
    public function addToSession($domain){
        session()->flash('domain_id', $domain->id);
    }
}
