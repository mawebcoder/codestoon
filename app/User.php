<?php

namespace App;

use App\models\Article;
use App\models\Course;
use App\models\TeacherInformation;
use App\models\UserLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * mutations of the password in the system(encrypting the password in the database)
     */
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = Hash::make($value);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id', 'id');
    }

    public function confirmerCourses()
    {
        return $this->hasMany(Course::class, 'admin_id', 'id');
    }

    public function TeacherInfo()
    {
        return $this->hasOne(TeacherInformation::class, 'teacher_id', 'id');
    }

    public function userActivities()
    {
        return $this->hasMany(UserLog::class);
    }

    public function lastActivity()
    {
        return
            $this->hasMany(UserLog::class)
            ->orderBy('created_at', 'desc')
            ->select('created_at', 'user_id', 'route');
    }

}
