<?php

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
        $types = array(
            [
                'tax' => 15.00,
            ]
        );

        foreach ($types as $type) {
            $data = \App\Tax::firstOrNew($type);
            $data->fill($type);
            $data->save();
        }
    }
}
