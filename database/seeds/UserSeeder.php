<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Yeasir Arafat',
            'email' => 'arafat@gmail.com',
            'email_verified_at' => Carbon\Carbon::now()->toDateTimeString(),
            'password' => Hash::make('password'),
            'status' => true,
            'created_at' => Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon\Carbon::now()->toDateTimeString(),
        ]);
        DB::table('role_user')->insert([
            'role_id' => 1,
            'user_id' => 1
        ]);
    }
}
