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

        //if role is user so store a user
        if ($role->name == 'teacher') {

            $result = $this->storeTeacher($role);

            return response(['message' => $result['message'], 'data' => null], $result['code']);

        } else {

            $result = $this->storeUser($role);

            return response(['message' => $result['message'], 'data' => null], $result['code']);

        }

    }

    public function storeUser($role)
    {
        $data = request()->only('name', 'family', 'cell', 'email', 'password');

        $data['username'] = $data['cell'];

        DB::beginTransaction();

        try {

            $user = User::query()->create($data);


            $assign_role_result = $this->assignRole($user, $role);

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
        DB::beginTransaction();

        try {
            $data = request()->only('name', 'family', 'cell', 'email', 'password');

            $data['username'] = $data['cell'];

            $user = User::query()->create($data);

            $this->assignRole($user, $role);

            $this->uploadProfileImage($user);

            $this->storeTeacherInformation($user);

            DB::commit();
            return ['message' => 'success', 'code' => 201];
        } catch (\Exception $exception) {
            DB::rollBack();
            DB::commit();
            return ['message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }


    }

    public function storeTeacherInformation($user)
    {
        $data = request()->only('description', 'nationality_code', 'status', 'address');

        $user->TeacherInfo()->create($data);

        $this->uploadTeacherDocuments($user);

    }

    public function uploadTeacherDocuments($user)
    {
        $this->uploadTeacherFrontNationalityCardImage($user);

        $this->uploadTeacherBackNationalityCardImage($user);

        $this->uploadTeacherResume($user);
    }

    public function uploadTeacherFrontNationalityCardImage($user)
    {
        if (request()->hasFile('front_nationality_card_image')) {

            $front_nationality_card_image_directory = $user->id . '/front-image';

            $file_name = request()->file('front_nationality_card_image')->getClientOriginalName();

            //if file before exists, so remove it
            if (Storage::disk('nationality_card')->exists($front_nationality_card_image_directory)) {
                Storage::disk('nationality_card')->deleteDirectory($front_nationality_card_image_directory);
            }

            request()->file('front_nationality_card_image')->storeAs($front_nationality_card_image_directory, $file_name, 'nationality_card');

            $user->TeacherInfo()->update(['nationality_card_front' => $file_name]);

        }
    }

    public function uploadTeacherBackNationalityCardImage($user)
    {
        if (request()->hasFile('back_nationality_card_image')) {

            $back_nationality_card_image_directory = $user->id . '/back-image';

            $file_name = request()->file('back_nationality_card_image')->getClientOriginalName();

            //if file before exists,so remove it
            if (Storage::disk('nationality_card')->exists($back_nationality_card_image_directory)) {
                Storage::disk('nationality_card')->deleteDirectory($back_nationality_card_image_directory);
            }
            request()->file('back_nationality_card_image')->storeAs($back_nationality_card_image_directory, $file_name, 'nationality_card');

            $user->TeacherInfo()->update(['nationality_card_back' => $file_name]);

        }
    }

    public function uploadTeacherResume($user)
    {
        if (request()->hasFile('resume_pdf_file')) {

            $resume_directory = (string)$user->id;

            $file_name = request()->file('resume_pdf_file')->getClientOriginalName();

            //if file exists,so remove it
            if (Storage::disk('resumes')->exists($resume_directory)) {

                Storage::disk('resumes')->deleteDirectory($resume_directory);

            }

            request()->file('resume_pdf_file')->storeAs($resume_directory, $file_name, 'resumes');

            $user->TeacherInfo()->update(['resume' => $file_name]);
        }
    }

    public function assignRole($user, $role)
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
        if (request()->hasFile('file')) {

            //uploading directory in storage public path
            $uploading_directory = 'images/users/profile-image/' . $user->id;

            $file_name = request()->file('file')->getClientOriginalName();

            if (Storage::disk('public')->exists($uploading_directory)) {

                Storage::disk('public')->deleteDirectory($uploading_directory);

            }

            request()->file('file')->storeAs($uploading_directory, $file_name, 'public');

            $user->update([
                'profile_image_name' => $file_name
            ]);

        }
    }

    public function getAdminsList()
    {

        $admins = User::query()->whereHas('roles', function ($q) {
            $q->where('name', 'like', '%admin%');
        })->with('roles')->paginate(30);

        return $admins->isNotEmpty() ?
            response(['message' => 'success', 'data' => $admins]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getUsersList()
    {
        $admins = User::query()->whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->with('roles')->paginate(30);

        return $admins->isNotEmpty() ?
            response(['message' => 'success', 'data' => $admins]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getTeachersList()
    {
        $admins = User::query()->whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })->paginate(30);

        return $admins->isNotEmpty() ?
            response(['message' => 'success', 'data' => $admins]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getActiveTeachersList()
    {
        $admins = User::query()->whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })->whereHas('TeacherInfo', function ($q) {
            $q->whereStatus('active');
        })->paginate(30);

        return $admins->isNotEmpty() ?
            response(['message' => 'success', 'data' => $admins]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getDeActiveTeachersList()
    {
        $admins = User::query()->whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })->whereHas('TeacherInfo', function ($q) {
            $q->whereStatus('inactive');
        })->paginate(30);

        return $admins->isNotEmpty() ?
            response(['message' => 'success', 'data' => $admins]) :
            response(['message' => 'success', 'data' => null], 204);
    }




}
