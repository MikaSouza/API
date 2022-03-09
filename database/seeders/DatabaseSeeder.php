<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Module::create([
            'name' => 'zoho desk',
            'slug' => Str::slug('zoho desk', '-'),
            'active' => true
        ]);
        Company::create([
            'name' => 'CCM',
        ]);

        User::create(
            [
                'name' => 'admin',
                'surname' => 'admin',
                'email' => 'admin@example.com',
                'modulo' => array('Financeiro', 'Investimentos', 'Monitoramento', 'Tickets'),
                'password' => bcrypt('admin'),
                'is_admin' => true,
                'company_id' => 1
            ]
        );
    }
}
