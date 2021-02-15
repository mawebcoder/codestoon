<?php

namespace Tests\Feature;

use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function testCanStoreNewRole()
    {
        $data = [
            'name' => 'super_admin'
        ];
        $this->post(route('role-store'), $data)
            ->assertStatus(201);
        $this->assertDatabaseHas('roles', [
            'name' => 'super_admin'
        ]);
    }

    public function testCanUpdateRole()
    {
        $role = factory(Role::class)->create();
        $this->put(route('role-update', ['role' => $role->id]), ['name' => 'new_name'])
            ->assertOk();

        $this->assertDatabaseHas('roles', [
            'name' => 'new_name',
            'id' => $role->id
        ]);
    }


    public function testCanDeleteMultipleRoles()
    {

    }
}
