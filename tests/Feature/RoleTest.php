<?php

namespace Tests\Feature;

use Spatie\Permission\Models\Permission;
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


    public function testCanSoftDeleteMultipleRoles()
    {
        $roles = factory(Role::class, 10)->create();
        $ids = $roles->pluck('id')->toArray();
        $this->post(route('role-delete-multi'), ['ids' => $ids])
            ->assertOk();

        foreach ($ids as $id) {
            $this->assertDatabaseMissing('roles', [
                'id' => $id
            ]);
        }
    }


    public function testCanAssignPermissionsToRole()
    {
        $role = factory(Role::class)->create();

        $permissions_ids = factory(Permission::class, 10)->create()
            ->pluck('id')->toArray();

        $this->post(route('set-role-permissions',['role'=>$role->id]), ['ids' => $permissions_ids])
            ->assertStatus(201);

        foreach ($permissions_ids as $id) {
            $this->assertDatabaseHas('role_has_permissions', [
                'permission_id' => $id,
                'role_id' => $role->id
            ]);
        }

    }
}
