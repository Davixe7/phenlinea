<?php

namespace Tests\Unit;

use App\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_list_all_admins()
    {
        $admins = Admin::factory()->count(3)->create();

        $response = $this->getJson(route('api.admins.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['data' => [
                '*' => [
                    'id',
                    'email',
                    'contact_email',
                    'name',
                    'nit',
                    'phone',
                    'address',
                    'status'
                ],
            ]]);

        foreach ($admins as $admin) {
            $response->assertJsonFragment([
                'id' => $admin->id,
                'email' => $admin->email,
                'name' => $admin->name,
                'nit' => $admin->nit,
                'phone' => $admin->phone,
                'status' => $admin->status,
            ]);
        }
    }

    /** @test */
    public function it_can_show_a_single_admin()
    {
        $admin = Admin::factory()->create();

        $response = $this->getJson(route('api.admins.show', $admin));

        $response->assertOk()
            ->assertJsonStructure(['data'=>[
                'id',
                'email',
                'contact_email',
                'name',
                'nit',
                'phone',
                'address',
                'status',
            ]])
            ->assertJsonFragment([
                'id' => $admin->id,
                'email' => $admin->email,
                'name' => $admin->name,
                'nit' => $admin->nit,
                'phone' => $admin->phone,
                'address' => $admin->address,
                'status' => $admin->status,
            ]);
    }

    /** @test */
    public function it_can_store_a_new_admin()
    {
        $adminData = [
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secret123',
            'contact_email' => $this->faker->safeEmail,
            'name' => $this->faker->name,
            'nit' => $this->faker->unique()->numerify('##########'),
            'phone' => $this->faker->numerify('3#########'),
            'address' => $this->faker->address,
            'status' => $this->faker->randomElement([0, 1]),
        ];

        $response = $this->postJson(route('api.admins.store'), $adminData);

        $response->assertCreated()
            ->assertJsonStructure(['data'=>[
                'id',
                'email',
                'contact_email',
                'name',
                'nit',
                'phone',
                'address',
            ]])
            ->assertJsonFragment([
                'email' => $adminData['email'],
                'name' => $adminData['name'],
                'nit' => $adminData['nit'],
                'phone' => $adminData['phone'],
                'address' => $adminData['address'],
            ]);

        $this->assertDatabaseHas('admins', [
            'email' => $adminData['email'],
            'name' => $adminData['name'],
            'nit' => $adminData['nit'],
        ]);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        $response = $this->postJson(route('api.admins.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password', 'name', 'nit', 'phone', 'address', 'contact_email']);
    }

    /** @test */
    public function it_validates_unique_email_and_nit_on_store()
    {
        $existingAdmin = Admin::factory()->create();

        $adminData = [
            'email' => $existingAdmin->email,
            'password' => 'anothersecret',
            'name' => $this->faker->name,
            'nit' => $existingAdmin->nit,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
        ];

        $response = $this->postJson(route('api.admins.store'), $adminData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'nit']);
    }

    /** @test */
    public function it_can_update_an_existing_admin()
    {
        $admin = Admin::factory()->create();

        $updatedData = [
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'contact_email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement([0, 1]),
            'email' => $this->faker->unique()->safeEmail,
        ];

        $response = $this->putJson(route('api.admins.update', $admin), $updatedData);

        $response->assertOk()
            ->assertJsonStructure(['data'=>[
                'id',
                'email',
                'contact_email',
                'name',
                'nit',
                'phone',
                'address',
                'status',
            ]])
            ->assertJsonFragment([
                'id' => $admin->id,
                'email' => $updatedData['email'],
                'name' => $updatedData['name'],
                'phone' => $updatedData['phone'],
                'address' => $updatedData['address'],
                'status' => $updatedData['status'],
            ]);

        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'email' => $updatedData['email'],
            'name' => $updatedData['name'],
            'phone' => $updatedData['phone'],
        ]);
    }

    /** @test */
    public function it_validates_unique_email_on_update()
    {
        $admin = Admin::factory()->create();
        $anotherAdmin = Admin::factory()->create();

        $updatedData = [
            'email' => $anotherAdmin->email,
        ];

        $response = $this->putJson(route('api.admins.update', $admin), $updatedData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_delete_an_admin()
    {
        $admin = Admin::factory()->create();

        $response = $this->deleteJson(route('api.admins.destroy', $admin));

        $response->assertNoContent();

        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }

    /** @test */
    public function it_returns_404_if_admin_not_found_on_show()
    {
        $response = $this->getJson(route('api.admins.show', 999));

        $response->assertNotFound();
    }

    /** @test */
    public function it_returns_404_if_admin_not_found_on_update()
    {
        $response = $this->putJson(route('api.admins.update', 999), []);

        $response->assertNotFound();
    }

    /** @test */
    public function it_returns_404_if_admin_not_found_on_destroy()
    {
        $response = $this->deleteJson(route('api.admins.destroy', 999));

        $response->assertNotFound();
    }
}
