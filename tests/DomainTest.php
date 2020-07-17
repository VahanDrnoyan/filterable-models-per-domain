<?php

namespace Vdrnoyan\TenantModelFilter\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;
use Vdrnoyan\TenantModelFilter\Contracts\DomainMakerInterface;
use Vdrnoyan\TenantModelFilter\Domain;
use Vdrnoyan\TenantModelFilter\TenantModelFilterServiceProvider;
use Vdrnoyan\TenantModelFilter\Tests\Models\Item;


class DomainTest extends TestCase
{
    use RefreshDatabase;

    protected $baseUrl = 'tenant-model-1.test';

    protected function getPackageProviders($app)
    {
        return [TenantModelFilterServiceProvider::class];
    }
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');


    }

    /** @test */
    public function it_can_create_domain()
    {
        $this->withoutExceptionHandling();

        Domain::create(['domain'=>'testdomain.test', 'data'=>123]);


        $domain = Domain::get()->first();


        $this->assertEquals('testdomain.test', $domain['domain']);
        $this->assertEquals(123, $domain['data']);
    }

    /** @test */
    public function it_has_domains_in_database(){
        Domain::create(['domain' =>'tenant-filters-1', 'data'=>123]);
        Domain::create(['domain'=>'tenant-filters-2', 'data'=>123]);

        $this->assertDatabaseHas('domains', [
            'domain' => 'tenant-filters-1',
        ]);
        $this->assertDatabaseHas('domains', [
            'domain' => 'tenant-filters-2',
        ]);
    }
    /** @test */
    public function it_has_access_to_current_domain(){
        $this->withoutExceptionHandling();
        $domain = Domain::create(['domain'=>'tenant-model-1.test', 'data'=>123]);
        app()->make(DomainMakerInterface::class)->makeCurrent($domain->id);


        $this->assertSame("tenant-model-1.test", currentDomain()->domain);

    }
    /** @test */
    public function it_vsits_correct_domain()
    {
        $domain = Domain::create(['domain'=>'tenant-model-1.test', 'data'=>123]);
        Route::get('/', function () {
            return currentDomain();
        });

        $responce = $this->get('http://tenant-model-1.test');

        $responce->assertSessionHas('domain_id', $domain->id);
        $responce->assertSeeText($domain->domain);

    }
    /** @test */
    public function it_creates_filterable_record()
    {
        $this->withoutExceptionHandling();

        $domain = Domain::create(['id'=>1567,'domain'=>'tenant-model-1.test', 'data'=>123]);
        $domain2 = Domain::create(['id'=>4534543,'domain'=>'tenant-model-2.test', 'data'=>123]);


        app()->make(DomainMakerInterface::class)->makeCurrent($domain->id);




        $item = Item::create(['name'=>'tester']);
        $this->assertDatabaseHas('filterables', [
            'filterables_id' => $item->id,
            'filterables_type' => get_class($item),
            'domain_id' =>  1567
        ]);
        $this->assertDatabaseMissing('filterables',[
            'domain_id'=>4534543
        ]);

    }
    /** @test */
    public function it_asserts_domains_filters_work()
    {
        $this->withoutExceptionHandling();

        $domain = Domain::create(['domain'=>'tenant-model-1.test', 'data'=>123]);
        app()->make(DomainMakerInterface::class)->makeCurrent($domain->id);
        $item1 = Item::create(['name'=>'tester1']);
        $item2 = Item::create(['name'=>'tester2']);


        $items1 = Item::all()->onlyForThisDomain();
        $this->assertEquals('tester1', $items1->first()->name);
        $this->assertEquals('tester2', $items1->last()->name);

        $domain2 = Domain::create(['domain'=>'tenant-model-2.test', 'data'=>123]);
        app()->make(DomainMakerInterface::class)->makeCurrent($domain2->id);

        $item3 = Item::create(['name'=>'tester3']);
        $item4 = Item::create(['name'=>'tester4']);

        $items2 = Item::all()->onlyForThisDomain();

        $this->assertEquals('tester3', $items2->first()->name);
        $this->assertEquals('tester4', $items2->last()->name);
        $this->assertCount(2, $items2);

    }
    /** @test */
    public function it_asserts_paginate_returns_only_domain_specific_values()
    {
        $this->withoutExceptionHandling();

        $domain = Domain::create(['domain'=>'tenant-model-1.test', 'data'=>123]);
        app()->make(DomainMakerInterface::class)->makeCurrent($domain->id);
        $item1 = Item::create(['name'=>'tester1']);
        $item2 = Item::create(['name'=>'tester2']);


        $items1 = Item::paginate(2);
        $this->assertEquals('tester1', $items1->first()->name);
        $this->assertEquals('tester2', $items1->last()->name);

        $domain2 = Domain::create(['domain'=>'tenant-model-2.test', 'data'=>123]);
        app()->make(DomainMakerInterface::class)->makeCurrent($domain2->id);

        $item3 = Item::create(['name'=>'tester3']);
        $item4 = Item::create(['name'=>'tester4']);

        $items2 = Item::withDomain()->paginate(2);


        $this->assertEquals('tester3', $items2->first()->name);
        $this->assertEquals('tester4', $items2->last()->name);
        $this->assertCount(2, $items2);

    }
    /** @test */
    public function it_can_store_domains_data_as_empty_collumn()
    {
        Domain::create(['domain'=>'tenant-model-1.test', 'data'=>NULL]);

        $this->assertDatabaseHas('domains', [
            'data' => NULL
        ]);
    }

}
