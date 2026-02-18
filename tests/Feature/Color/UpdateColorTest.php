<?php

use App\Packages\Color\Models\Color;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pode atualizar uma cor', function () {
    $color = Color::factory()->create([
        'name' => 'Original Name',
        'active' => true
    ]);

    $response = $this->putJson(route('v1.colors.update', ['id' => $color->id]), [
        'name' => 'Updated Name',
        'active' => false
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Cor atualizada com sucesso!',
            'data' => [
                'id' => $color->id,
                'name' => 'Updated Name',
                'active' => false
            ]
        ]);

    $this->assertDatabaseHas('color', [
        'id' => $color->id,
        'name' => 'Updated Name',
        'active' => false
    ]);
});

test('retorna erro ao tentar atualizar cor inexistente', function () {
    $response = $this->putJson(route('v1.colors.update', ['id' => 999]), [
        'name' => 'Some Name',
        'active' => true
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Cor não encontrada!'
        ]);
});

test('valida campos obrigatorios na atualizacao', function () {
    $color = Color::factory()->create();

    $response = $this->putJson(route('v1.colors.update', ['id' => $color->id]), []);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'errors' => [
                    'name',
                    'active'
                ]
            ]
        ]);
});
