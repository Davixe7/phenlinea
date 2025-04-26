<?php

namespace Tests\Unit;

use App\Admin;
use App\Porteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PorteriaControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_list_all_porterias()
    {
        $porterias = Porteria::factory()->count(3)->create();

        $response = $this->getJson(route('api.porterias.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'admin_id',
                    ],
                ],
            ]);

        foreach ($porterias as $porteria) {
            $response->assertJsonFragment([
                'id' => $porteria->id,
                'name' => $porteria->name,
                'email' => $porteria->email,
                'admin_id' => $porteria->admin_id,
            ], 'data.*');
        }
    }

    /** @test */
    public function it_can_show_a_single_porteria()
    {
        $porteria = Porteria::factory()->create();

        $response = $this->getJson(route('api.porterias.show', $porteria));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'admin_id',
                ],
            ])
            ->assertJsonFragment([
                'id' => $porteria->id,
                'name' => $porteria->name,
                'email' => $porteria->email,
                'admin_id' => $porteria->admin_id,
            ]);
    }

    /** @test */
    public function it_can_store_a_new_porteria()
    {
        $admin = Admin::factory()->create(); // Asegúrate de que exista un admin_id válido
        $porteriaData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123456',
            'admin_id' => $admin->id,
        ];

        $response = $this->postJson(route('api.porterias.store'), $porteriaData);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'admin_id',
                ],
            ])
            ->assertJsonFragment([
                'name' => $porteriaData['name'],
                'email' => $porteriaData['email'],
                'admin_id' => $porteriaData['admin_id'],
            ]);

        $this->assertDatabaseHas('porterias', [
            'name' => $porteriaData['name'],
            'email' => $porteriaData['email'],
            'admin_id' => $porteriaData['admin_id'],
        ]);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        $response = $this->postJson(route('api.porterias.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'admin_id']);
    }

    /** @test */
    public function it_validates_unique_email_on_store()
    {
        $existingPorteria = Porteria::factory()->create();
        $admin = Admin::factory()->create();

        $porteriaData = [
            'name' => $this->faker->name,
            'email' => $existingPorteria->email,
            'password' => '123456',
            'admin_id' => $admin->id,
        ];

        $response = $this->postJson(route('api.porterias.store'), $porteriaData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_update_an_existing_porteria()
    {
        $porteria = Porteria::factory()->create();
        $admin = Admin::factory()->create();

        $updatedData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'admin_id' => $admin->id,
        ];

        $response = $this->putJson(route('api.porterias.update', $porteria), $updatedData);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'admin_id',
                    
                ],
            ])
            ->assertJsonFragment([
                'id' => $porteria->id,
                'name' => $updatedData['name'],
                'email' => $updatedData['email'],
                'admin_id' => $updatedData['admin_id'],
            ]);

        $this->assertDatabaseHas('porterias', [
            'id' => $porteria->id,
            'name' => $updatedData['name'],
            'email' => $updatedData['email'],
            'admin_id' => $updatedData['admin_id'],
        ]);
    }

    /** @test */
    public function it_validates_unique_email_on_update()
    {
        $porteria = Porteria::factory()->create();
        $anotherPorteria = Porteria::factory()->create();
        $admin = Admin::factory()->create();

        $updatedData = [
            'name'     => 'whatever',
            'email'    => $anotherPorteria->email,
            'admin_id' => $admin->id,
        ];

        $response = $this->putJson(route('api.porterias.update', $porteria), $updatedData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_delete_an_porteria()
    {
        $porteria = Porteria::factory()->create();

        $response = $this->deleteJson(route('api.porterias.destroy', $porteria));

        $response->assertNoContent();

        $this->assertDatabaseMissing('porterias', ['id' => $porteria->id]);
    }

    /** @test */
    public function it_returns_404_if_porteria_not_found_on_show()
    {
        $response = $this->getJson(route('api.porterias.show', 999));

        $response->assertNotFound();
    }

    /** @test */
    public function it_returns_404_if_porteria_not_found_on_update()
    {
        $response = $this->putJson(route('api.porterias.update', 999), []);

        $response->assertNotFound();
    }

    /** @test */
    public function it_returns_404_if_porteria_not_found_on_destroy()
    {
        $response = $this->deleteJson(route('api.porterias.destroy', 999));

        $response->assertNotFound();
    }
}

