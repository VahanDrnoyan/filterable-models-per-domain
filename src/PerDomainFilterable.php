<?php


namespace Vdrnoyan\TenantModelFilter;


use Illuminate\Database\Eloquent\Builder;

trait PerDomainFilterable
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->domains()->attach(currentDomain()->id);
        });

        static::addGlobalScope('domains', function (Builder $builder) {
            $builder->with(['domains'=>function($query){
                $query->where('domains.id', '=', currentDomain()->id);
            }]);
        });
    }
    public function domains()
    {
        return $this->morphToMany(Domain::class, 'filterables');
    }
}
