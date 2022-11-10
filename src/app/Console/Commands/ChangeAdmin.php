<?php

namespace erfan_kateb_saber\admin_panel\app\Console\Commands;

use erfan_kateb_saber\admin_panel\app\Extensions\Xml\XML_Manager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ChangeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /*protected $signature = 'admin:change
    {--u|username=null : Change the admin username}
    {--p|password=null : Change the admin password}';*/

    protected $signature = 'admin:change
    {--p|password= : Change the admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the admin info';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $username = $this->argument('username')??null;
        $username = 'admin';
        $password = $this->option('password') ?? null;

        if ($password === null) {
            $this->error('Argument value is not valid');
            return Command::INVALID;
        }

        $data = [
            "numberRowId1"=>[
                'username' => $username,
                'password' => Hash::make($password)
            ]
        ];
        XML_Manager::arrayToXml($data, '/admin_panel/db_admin_users.xml', '<admin_users/>');
        $this->info('The change admin info was successful!');
        return Command::SUCCESS;

    }
}
