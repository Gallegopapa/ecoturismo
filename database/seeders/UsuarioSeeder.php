<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        DB::table('usuarios')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'TestUser',
    'email' => 'test@example.com',
    'telefono' => NULL,
    'foto_perfil' => NULL,
    'password' => '$2y$10$sGorXz1Y05U6pqJBbGaD.eO4B9pKrawiK7ULqoC1eaepsU9LIx69O',
    'is_admin' => 1,
    'tipo_usuario' => 'normal',
    'fecha_registro' => '2025-12-15 01:06:29',
    'remember_token' => 'OfqnAZr7Ty',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'Robin',
    'email' => 'robinejemplo@gmail.com',
    'telefono' => '3217085550',
    'foto_perfil' => '/storage/profiles/1765767144_693f77e8cb5d5.png',
    'password' => '$2y$10$sGorXz1Y05U6pqJBbGaD.eO4B9pKrawiK7ULqoC1eaepsU9LIx69O',
    'is_admin' => 1,
    'tipo_usuario' => 'normal',
    'fecha_registro' => '2025-12-15 01:07:28',
    'remember_token' => NULL,
  ),
  2 => 
  array (
    'id' => 4,
    'name' => 'simon',
    'email' => 'simon.23051997@gmail.com',
    'telefono' => NULL,
    'foto_perfil' => NULL,
    'password' => '$2y$10$sGorXz1Y05U6pqJBbGaD.eO4B9pKrawiK7ULqoC1eaepsU9LIx69O',
    'is_admin' => 0,
    'tipo_usuario' => 'normal',
    'fecha_registro' => '2026-02-03 12:41:24',
    'remember_token' => NULL,
  ),
  3 => 
  array (
    'id' => 5,
    'name' => 'adrianagmailcomcom',
    'email' => 'adriana@gmail.com.com',
    'telefono' => NULL,
    'foto_perfil' => NULL,
    'password' => '$2y$10$sGorXz1Y05U6pqJBbGaD.eO4B9pKrawiK7ULqoC1eaepsU9LIx69O',
    'is_admin' => 0,
    'tipo_usuario' => 'normal',
    'fecha_registro' => '2026-02-03 13:42:24',
    'remember_token' => NULL,
  ),
  4 => 
  array (
    'id' => 6,
    'name' => 'JeycobAdmin',
    'email' => 'goldarmony8776@gmail.com',
    'telefono' => NULL,
    'foto_perfil' => NULL,
    'password' => '$2y$10$sGorXz1Y05U6pqJBbGaD.eO4B9pKrawiK7ULqoC1eaepsU9LIx69O',
    'is_admin' => 1,
    'tipo_usuario' => 'normal',
    'fecha_registro' => '2026-02-10 21:17:17',
    'remember_token' => NULL,
  ),
  5 => 
  array (
    'id' => 7,
    'name' => 'Rodrigo',
    'email' => 'rodri@gmail.com',
    'telefono' => NULL,
    'foto_perfil' => NULL,
    'password' => '$2y$10$sGorXz1Y05U6pqJBbGaD.eO4B9pKrawiK7ULqoC1eaepsU9LIx69O',
    'is_admin' => 1,
    'tipo_usuario' => 'normal',
    'fecha_registro' => '2026-02-11 00:49:07',
    'remember_token' => NULL,
  ),
  6 => 
  array (
    'id' => 10,
    'name' => 'juanjo',
    'email' => 'juanjolopin@gmail.com',
    'telefono' => NULL,
    'foto_perfil' => NULL,
    'password' => '$2y$10$sGorXz1Y05U6pqJBbGaD.eO4B9pKrawiK7ULqoC1eaepsU9LIx69O',
    'is_admin' => 1,
    'tipo_usuario' => 'normal',
    'fecha_registro' => '2026-02-15 20:17:03',
    'remember_token' => NULL,
  ),
  7 => 
  array (
    'id' => 11,
    'name' => 'sarai',
    'email' => 'sarai@gmail.com',
    'telefono' => NULL,
    'foto_perfil' => NULL,
    'password' => '$2y$10$sGorXz1Y05U6pqJBbGaD.eO4B9pKrawiK7ULqoC1eaepsU9LIx69O',
    'is_admin' => 0,
    'tipo_usuario' => 'normal',
    'fecha_registro' => '2026-02-16 00:18:47',
    'remember_token' => NULL,
  ),
));
    }
}
