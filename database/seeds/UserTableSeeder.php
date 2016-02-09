<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   		DB::table('users')->delete();

        $adminRole = Role::whereName('administrator')->first();
        $userRole = Role::whereName('customer')->first();

        $user = User::create(array(
            'name'      => 'hendri',
            'email'     => 'hendrilara@gmail.com',
            'password'  => Hash::make('password')
        ));

        $user->assignRole($adminRole);

        $user = User::create(array(
            'name'     => 'han',
            'email'         => 'admin@admin.com',
            'password'      => Hash::make('admin123')
        ));

        $user->assignRole($userRole);
    }
}
