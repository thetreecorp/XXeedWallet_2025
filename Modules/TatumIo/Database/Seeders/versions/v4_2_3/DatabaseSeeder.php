<?php

namespace Modules\TatumIo\Database\Seeders\versions\v4_2_3;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(UpdateTatumIoAliasSeeder::class);
    }
}
