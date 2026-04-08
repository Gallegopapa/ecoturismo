<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EcohotelSeeder extends Seeder
{
    public function run()
    {
        DB::table('ecohotels')->insertOrIgnore(array (
  0 => 
  array (
    'id' => 3,
    'name' => 'casa campestre natural',
    'description' => 'hospedaje cerca a las chorros de don lolo',
    'location' => 'Santa rosa, Risaralda',
    'image' => '/storage/ecohotels/1771309892_69940b445e078.png',
    'latitude' => '4.8818675',
    'longitude' => '-81.5879421',
    'telefono' => '322 5367890',
    'email' => 'roooo@gmail.com',
    'sitio_web' => 'https://micasaconstructora.com/casas/casa-campestre-176-m%C2%B2-modelo-natural/',
    'created_at' => '2026-02-17 06:31:32',
    'updated_at' => '2026-02-17 06:31:32',
  ),
  1 => 
  array (
    'id' => 4,
    'name' => 'finca cortaderal santa rosa de cabal Rda',
    'description' => 'hospedaje que queda cerca al parque la laufa del otun',
    'location' => 'Pereira,Risaralda',
    'image' => '/storage/ecohotels/1771310670_69940e4e10eae.webp',
    'latitude' => '4.8611337',
    'longitude' => '-76.4795346',
    'telefono' => NULL,
    'email' => NULL,
    'sitio_web' => 'https://mapcarta.com/',
    'created_at' => '2026-02-17 06:44:30',
    'updated_at' => '2026-02-17 06:44:30',
  ),
  2 => 
  array (
    'id' => 5,
    'name' => 'termales de san vicente',
    'description' => 'hospedaje de los termales de san vicene',
    'location' => 'Dosquebradas,pereira',
    'image' => '/storage/ecohotels/1771332101_6994620567628.jpg',
    'latitude' => '4.8827323',
    'longitude' => '-75.5725715',
    'telefono' => NULL,
    'email' => NULL,
    'sitio_web' => NULL,
    'created_at' => '2026-02-17 12:41:41',
    'updated_at' => '2026-02-17 12:41:41',
  ),
));
    }
}
