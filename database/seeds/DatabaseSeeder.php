<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */
    public function run()
    {
        create(\App\Models\Tip::class, 18);
        # This one is only for the show collection of post man
        create(\App\Models\Tip::class, null,
            ['guid' => \App\Models\Tip::encodeUuid('4174aa26-e72a-11e8-8e87-0242ac170003')]);
        # This one is only for the destroy collection of post man
        create(\App\Models\Tip::class, null,
            ['guid' => \App\Models\Tip::encodeUuid('97884b14-e72c-11e8-8d1a-0242ac170003')]);
    }
}
