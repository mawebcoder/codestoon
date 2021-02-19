<?php

namespace Tests\Feature;

use App\models\TeacherInformation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function testCanStoreUser()
    {
        $user_role = factory(Role::class)->create(['name' => 'user']);
        $file = UploadedFile::fake()->image('user.jpg');
        $cell = Str::random(11);
        $email = Str::random(9) . '@gmail.com';
        $data = [
            'name' => 'mohammad',
            'family' => 'amiri',
            'cell' => $cell,
            'password' => 'mohammadeng',
            'email' => $email,
            'file' => $file,
            'role_id' => $user_role->id,
        ];
        $this->post(route('admin-user-store'), $data)
            ->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => 'mohammad',
            'family' => 'amiri',
            'cell' => $cell,
        ]);
        $last_user_id = User::query()->orderBy('id', 'desc')->first()->id;

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $user_role->id,
            'model_id' => $last_user_id
        ]);
        $profile_image_storage_path = 'app/public/images/users/profile-image/' . $last_user_id . '/' . $file->getClientOriginalName();
        $this->assertFileExists(storage_path($profile_image_storage_path));
    }

    public function testCanStoreTeacher()
    {
        $role = factory(Role::class)->create(['name' => 'teacher']);

        $front_nationality_card_image = UploadedFile::fake()->image('front_code_melli.jpg');

        $back_nationality_card_image = UploadedFile::fake()->image('back_code_melli.jpg');

        $profile_image = UploadedFile::fake()->image('user.jpg');

        $resume_pdf_file = UploadedFile::fake()->create('resume.pdf');

        $cell = Str::random(11);

        $email = Str::random(9) . '@gmail.com';

        $data = [
            'name' => 'mohammad',
            'family' => 'amiri',
            'cell' => $cell,
            'password' => 'mohammadeng',
            'email' => $email,
            'nationality_code' => '2580746056',
            'description' => 'description',
            'address' => 'address',
            'role_id' => $role->id,
            'status' => 'active',
            'front_nationality_card_image' => $front_nationality_card_image,
            'back_nationality_card_image' => $back_nationality_card_image,
            'file' => $profile_image,
            'resume_pdf_file' => $resume_pdf_file
        ];

        $this->post(route('admin-user-store'), $data)
            ->assertStatus(201);


        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => 'mohammad',
            'family' => 'amiri',
            'cell' => $cell
        ]);


        $this->assertDatabaseHas('teacher_information', [
            'address' => 'address',
            'description' => 'description',
            'status' => 'active',
            'nationality_card_back' => $back_nationality_card_image->getClientOriginalName(),
            'nationality_card_front' => $front_nationality_card_image->getClientOriginalName()
        ]);

        $last_user_id = User::query()->orderBy('id', 'desc')->first()->id;
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $last_user_id
        ]);
        $profile_image_storage_path = 'app/public/images/users/profile-image/' . $last_user_id . '/' . $profile_image->getClientOriginalName();

        $this->assertFileExists(storage_path($profile_image_storage_path));

        $front_nationality_card_image_path = storage_path('app/documents/nationality-card-images/' . $last_user_id . '/back-image/' . $back_nationality_card_image->getClientOriginalName());

        $this->assertFileExists($front_nationality_card_image_path);

        $back_nationality_card_image_path = storage_path('app/documents/nationality-card-images/' . $last_user_id . '/front-image/' . $front_nationality_card_image->getClientOriginalName());

        $this->assertFileExists($back_nationality_card_image_path);

        $resume_pdf_file_path = storage_path('app/documents/resumes/' . $last_user_id . '/' . $resume_pdf_file->getClientOriginalName());

        $this->assertFileExists($resume_pdf_file_path);

    }

    public function testCanUpdateUser()
    {

        $user = factory(User::class)->create();

        $file = UploadedFile::fake()->image('user.jpg');

        $role = factory(Role::class)->create(['name' => 'admin']);

        $role2 = factory(Role::class)->create(['name' => 'super_admin']);


        $user->syncRoles([$role->id]);

        $cell = Str::random(10);

        $email = Str::random(10) . '@gmail.com';

        $data = [
            'name' => 'mohammad',
            'family' => 'amiri',
            'username' => $cell,
            'cell' => $cell,
            'email' => $email,
            'file' => $file,
            'password' => Str::random(10),
            'role_id' => $role2->id
        ];
        $this->post(route('admin-users-update-user', ['user' => $user->id]), $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => 'mohammad',
            'email' => $email,
            'cell' => $cell
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role2->id,
            'model_id' => $user->id
        ]);

        $last_user_id = $user->id;

        $profile_image_storage_path = 'app/public/images/users/profile-image/' . $last_user_id . '/' . $file->getClientOriginalName();

        $this->assertFileExists(storage_path($profile_image_storage_path));

    }

    public function testCanUpdateTeacher()
    {
        $user = factory(User::class)->create();

        $role = factory(Role::class)->create(['name' => 'teacher']);

        $user->syncRoles([$role->id]);

        factory(TeacherInformation::class)->create(['teacher_id' => $user->id]);


        $front_nationality_card_image = UploadedFile::fake()->image('front_code_melli.jpg');

        $back_nationality_card_image = UploadedFile::fake()->image('back_code_melli.jpg');

        $profile_image = UploadedFile::fake()->image('user.jpg');

        $resume_pdf_file = UploadedFile::fake()->create('resume.pdf');

        $cell = Str::random(11);

        $email = Str::random(9) . '@gmail.com';

        $data = [
            'name' => 'mohammad',
            'family' => 'amiri',
            'cell' => $cell,
            'password' => 'mohammadeng',
            'email' => $email,
            'nationality_code' => '2580746056',
            'description' => 'description',
            'address' => 'address',
            'status' => 'active',
            'front_nationality_card_image' => $front_nationality_card_image,
            'back_nationality_card_image' => $back_nationality_card_image,
            'file' => $profile_image,
            'resume_pdf_file' => $resume_pdf_file
        ];
        $this->post(route('admin-users-update-teacher', ['user' => $user->id]), $data)
            ->assertStatus(201);


        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => 'mohammad',
            'family' => 'amiri',
            'cell' => $cell
        ]);


        $this->assertDatabaseHas('teacher_information', [
            'address' => 'address',
            'description' => 'description',
            'status' => 'active',
            'nationality_card_back' => $back_nationality_card_image->getClientOriginalName(),
            'nationality_card_front' => $front_nationality_card_image->getClientOriginalName()
        ]);

        $last_user_id = $user->id;

        $profile_image_storage_path = 'app/public/images/users/profile-image/' . $last_user_id . '/' . $profile_image->getClientOriginalName();

        $this->assertFileExists(storage_path($profile_image_storage_path));

        $front_nationality_card_image_path = storage_path('app/documents/nationality-card-images/' . $last_user_id . '/back-image/' . $back_nationality_card_image->getClientOriginalName());

        $this->assertFileExists($front_nationality_card_image_path);

        $back_nationality_card_image_path = storage_path('app/documents/nationality-card-images/' . $last_user_id . '/front-image/' . $front_nationality_card_image->getClientOriginalName());

        $this->assertFileExists($back_nationality_card_image_path);

        $resume_pdf_file_path = storage_path('app/documents/resumes/' . $last_user_id . '/' . $resume_pdf_file->getClientOriginalName());

        $this->assertFileExists($resume_pdf_file_path);

    }


    public function testCanSoftDeleteUsers()
    {
        $users = factory(User::class, 10)->create();

        $ids = $users->pluck('id')->toArray();

        $this->post(route('admin-users-delete'), ['ids' => $ids])
            ->assertOk();

        foreach ($ids as $id) {
            $this->assertSoftDeleted('users', [
                'id' => $id
            ]);
        }
    }

    public function testCanForceDeleteTheUsers()
    {
        $users = factory(User::class, 10)->create(['profile_image_name' => 'user.jpg']);

        $ids = $users->pluck('id')->toArray();

//        create profile images
        foreach ($ids as $id) {
            Storage::disk('public')->put('images/users/profile-image/' . $id . '/user.jpg', 'hello');
        }

        //soft delete users
        foreach ($users as $user) {
            $user->delete();
        }

        $this->post(route('admin-users-force-delete'), ['ids' => $ids])
            ->assertOk();

        foreach ($ids as $id) {
            $this->assertDatabaseMissing('users', [
                'id' => $id
            ]);
        }
        foreach ($ids as $id) {
            $this->assertDirectoryDoesNotExist(storage_path('app/public/images/users/profile-image/' . $id));
        }

    }

    public function testCanRestoreUsers()
    {
        $users = factory(User::class, 10)->create(['profile_image_name' => 'user.jpg']);

        $ids = $users->pluck('id')->toArray();


        //soft delete users
        foreach ($users as $user) {
            $user->delete();
        }

        $this->post(route('admin-users-restore'), ['ids' => $ids])
            ->assertOk();

        foreach ($ids as $id) {
            $this->assertDatabaseHas('users', [
                'id' => $id
            ]);
        }
    }


}
