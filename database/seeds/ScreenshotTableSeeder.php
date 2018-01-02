<?php

use Illuminate\Database\Seeder;

class ScreenshotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Waldo\Branch::class, 50)->create()->each(function($b) {
            $commits = factory(Waldo\Commit::class, 10)->create(['branch_id' => $b->id])->each(function ($c) {
                for ($i = 0; $i < 10; $i++) {
                    $c->screenshots()->save(
                        factory(Waldo\Screenshot::class)->make([
                            'commit_id' => $c->id,
                            'branch_id' => $c->branch_id
                        ])
                    );
                }
            });

            foreach ($commits as $commit) {
                $b->commits()->save($commit);
            }
        });
    }
}
