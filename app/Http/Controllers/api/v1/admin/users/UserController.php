<?php

namespace App\Http\Controllers\api\v1\admin\users;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function store()
    {

        $role_id = request()->role_id;

        $role = Role::query()->find($role_id);

        if ($role->name == 'user') {

            $result = $this->storeUser($role);

            return response(['message' => $result['message'], 'data' => null], $result['code']);

        } elseif ($role->name == 'teacher') {

            $this->storeTeacher($role);

        } else {

            $this->storeAdmin($role);

        }

    }

    public function storeUser($role)
    {
        $data = request()->only('name', 'family', 'cell', 'email', 'password');

        DB::beginTransaction();

        try {

            $user = User::query()->create([
                'name' => $data['name'],
                'family' => $data['family'],
                'email' => $data['email'],
                'password' => $data['password'],
                'cell' => $data['cell'],
                'username' => $data['cell']
            ]);


            $assign_role_result = $this->assignRoles($user, $role);

            $upload_image_result = $this->uploadProfileImage($user);

            DB::commit();

            return ['code' => 201, 'message' => 'success'];

        } catch (\Exception $e) {
            DB::rollBack();

            DB::commit();

            return ['code' => $e->getCode(), 'message' => $e->getMessage()];

        }


    }

    public function storeTeacher($role)
    {

    }

    public function storeAdmin($role)
    {

    }

    public function assignRoles($user, $role)
    {
        //assign role to user
        $user->syncRoles([$role->id]);
        //get permissions of the role
        $permissions = $role->permissions->pluck('id')->toArray();
        //assign the permissions of the role to the user
        $user->syncPermissions($permissions);
    }

    public function uploadProfileImage($user)
    {
        if (request()->has('file')) {

            //uploading directory in storage public path
            $uploading_directory = 'images/users/profile-image/' . $user->id;

            $file_name = request()->file('file')->getClientOriginalName();

            $file_path = $uploading_directory . '/' . $file_name;

            if (Storage::disk('public')->exists($uploading_directory)) {

                Storage::disk('public')->deleteDirectory($uploading_directory);
            }

            request()->file('file')->storeAs($uploading_directory, $file_name, 'public');

            $user->update([
                'profile_image_name' => $file_name
            ]);
        }
    }

}
