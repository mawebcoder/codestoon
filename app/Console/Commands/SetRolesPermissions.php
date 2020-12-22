<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
     * @return int
     */
    public function handle()
    {

    }
}
