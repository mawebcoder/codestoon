<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoleTest extends TestCase
{
    public function testCanStoreNewRole()
    {
        $data = [
            'name' => 'super_admin'
        ];
        $this->post(route('role-store'),$data)
            ->assertStatus(201);
        $this->assertDatabaseHas('roles',[
            'name'=>'super_admin'
        ]);
    }
}
