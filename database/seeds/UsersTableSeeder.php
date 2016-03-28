<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'testrunner',
            'email' => 'testrunner@opentimetracker.org',
            'password' => Hash::make('tester123'),
            'timezone' => 'Europe/Berlin',
        ]);
    }
}
