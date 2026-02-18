<?php

use App\Packages\Color\Models\Color;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pode deletar uma cor', function () {
    $color = Color::factory()->create();

    $response = $this->deleteJson(route('v1.colors.destroy', ['id' => $color->id]));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Cor removida com sucesso!',
            'data' => true
        ]);

    $this->assertDatabaseMissing('color', [
        'id' => $color->id
    ]);
});

test('retorna erro ao tentar deletar cor inexistente', function () {
    $response = $this->deleteJson(route('v1.colors.destroy', ['id' => 999]));

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Cor não encontrada!'
        ]);
});
