<?php

use App\Domains\ProductionHouses\Actions\AssignUserProductionHouse;
use App\Domains\ProductionHouses\Actions\CreateProductionHouse;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('production house action create a production house', function () {
    $user = User::factory()->create();
    new CreateProductionHouse()->execute($user, "ARTE", website: "https://arte.tv", remark: "Source grand publique mais beaucoup de docus disponibles");
    $production_house = ProductionHouse::first();
    $this->assertEquals($user->id, 1);
});

test('event created during production house creation', function () {
    $user = User::factory()->create();
    new CreateProductionHouse()->execute($user, "ARTE");
    $production_house = ProductionHouse::first();
    $this->assertEquals($production_house->events->count(), 1);
});


test('user is assigned to the production house', function () {
    $user = User::factory()->create();
    new CreateProductionHouse()->execute($user, "ARTE");
    $production_house = ProductionHouse::first();
    new AssignUserProductionHouse()->execute($user, $production_house, $user);
    $this->assertTrue($production_house->assignee->contains($user));
});
