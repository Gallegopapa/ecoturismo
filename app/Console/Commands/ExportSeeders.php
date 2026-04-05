<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportSeeders extends Command
{
    protected $signature = 'export:seeders';
    protected $description = 'Exporta los datos actuales de lugares, ecohoteles y usuarios a seeders';

    public function handle()
    {
        $this->exportTable('places', 'PlaceSeeder');
        $this->exportTable('ecohotels', 'EcohotelSeeder');
        $this->exportTable('usuarios', 'UsuarioSeeder');
        $this->info('Seeders exportados correctamente.');
    }

    private function exportTable($table, $seederName)
    {
        $data = DB::table($table)->get()->toArray();
        $array = json_decode(json_encode($data), true);
        $seederPath = database_path("seeders/{$seederName}.php");
        $content = "<?php\n\nnamespace Database\Seeders;\n\nuse Illuminate\Database\Seeder;\nuse Illuminate\Support\Facades\DB;\n\nclass {$seederName} extends Seeder\n{\n    public function run()\n    {\n        DB::table('{$table}')->insertOrIgnore(" . var_export($array, true) . ");\n    }\n}\n";
        File::put($seederPath, $content);
    }
}
