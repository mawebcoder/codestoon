<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetRolesPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate project permissions and the super admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $super_admin_role = Role::updateOrCreate(['name' => 'super_admin']);
        if (!$super_admin_role) {
            $this->error('creating the super admin encountered error');
        }
        $permissions = [
            'create_article',
            'see_article',
            'delete_article',
            'update_article',
            'create_article_category',
            'update_article_category',
            'delete_article_category',
            'see_article_category'
        ];
        $permissions_ids = [];
        foreach ($permissions as $permission) {
            $result = Permission::updateOrCreate(['name' => $permission]);
            if (!$result) {
                $this->error('creating the permissions encountered error');
            }
            array_push($permissions_ids, $result->id);
        }
        $result = $super_admin_role->syncPermissions($permissions_ids);
        if (!$result) {
            $this->error('can not syn the permissions to the super admin role');
        }
        $super_admin = User::updateOrCreate(
            ['username' => '09367989856',],
            [
                'name' => 'mohammad',
                'family' => 'amiri',
                'password' => 'mohammadeng37313731',
                'email' => 'mawebcoder@gmail.com',
                'cell' => '09367989856',
                'nationality_code' => '2580746056',
                'profile_image_url' => null
            ]);
        if (!$super_admin) {
            $this->error('can not create the super admin user');
        }
        $result2 = $super_admin->syncRoles($super_admin_role->id);
        if (!$result2) {
            $this->error('can not sync the super admin to the super_admin_role');
        }
        $result3 = $super_admin->syncPermissions($permissions_ids);
        if (!$result3) {
            $this->error('can not sync the super admin to the all permissions');
        }
      $this->info('successfully done!');
    }
}
