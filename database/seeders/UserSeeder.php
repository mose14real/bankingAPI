<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
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
        User::insert(
            [
                [
                    'uuid' => Str::orderedUuid(),
                    'name' => "Oluwasegun Moses Olopade",
                    'email' => "oluwasegun.olopade@disreybankplc.com",
                    'phone' => '08101560019',
                    'password' => Hash::make('Oluwasegun1234'),
                    'role' => "acct officer",
                    'remember_token' => Str::random(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                [
                    'uuid' => Str::orderedUuid(),
                    'name' => "Unwana Bright Ekpo",
                    'email' => "unwana.ekpo@disreybankplc.com",
                    'phone' => '08147512594',
                    'password' => Hash::make('Unwana1234'),
                    'role' => "acct officer",
                    'remember_token' => Str::random(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                [
                    'uuid' => Str::orderedUuid(),
                    'name' => "Oluwastunmise Abigail Obafunmilayo",
                    'email' => "tunmise.obafunmilayo@disreybankplc.com",
                    'phone' => '08165220993',
                    'password' => Hash::make('Oluwatunmise1234'),
                    'role' => "acct officer",
                    'remember_token' => Str::random(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                [
                    'uuid' => Str::orderedUuid(),
                    'name' => "Thandiwe Abigail Sakala",
                    'email' => "thandiwe.sakala@disreybankplc.com",
                    'phone' => '08178341622',
                    'password' => Hash::make('Thandiwe1234'),
                    'role' => "acct officer",
                    'remember_token' => Str::random(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ]
        );
    }
}
