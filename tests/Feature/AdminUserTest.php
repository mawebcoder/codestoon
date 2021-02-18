<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
//    use RefreshDatabase;

    public function testCanStoreUser()
    {
        $user_role = factory(Role::class)->create(['name' => 'user']);
        $file = UploadedFile::fake()->image('user.jpg');
        $data = [
            'name' => 'mohammad',
            'family' => 'amiri',
            'cell' => '09367989856',
            'password' => 'mohammadeng',
            'email' => 'mohammad@gmail.com',
            'file' => $file,
            'role_id' => $user_role->id,
        ];
        $this->post(route('admin-user-store'), $data)
            ->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'mohammad',
            'family' => 'amiri',
            'cell' => '09367989856',
        ]);
        $profile_image_storage_path = 'app/public/images/users/profile-image/1/' . $file->getClientOriginalName();
        $this->assertFileExists(storage_path($profile_image_storage_path));
    }

    public function testCanStoreTeacher()
    {

    }

    public function testCanStoreSuperAdmin()
    {

    }

}
