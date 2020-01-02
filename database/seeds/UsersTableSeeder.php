<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'staff']);
        Role::create(['name' => 'admin']);
        factory(App\User::class, 1)
            ->create([
                'email' => 'owner@mail.com'
            ])
            ->each(function ($user) {
                $user->assignRole('owner');
            });
        factory(App\User::class, 1)
            ->create([
                'email' => 'staff@mail.com'
            ])
            ->each(function ($user) {
                $user->assignRole('staff');
            });
        factory(App\User::class, 1)
            ->create([
                'email' => 'admin@mail.com'
            ])
            ->each(function ($user) {
                $user->assignRole('admin');
            });
    }
}
