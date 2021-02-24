<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\AppHelper;

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 200; $i++) {
            //random timestamp in the last year
            $randomStartedAt = Carbon::today()
            ->subDays(rand(0, 365))
            ->addHours(rand(0, 23))
            ->addMinutes(rand(0, 59))
            ->addSeconds(rand(0, 59));

            $randomFinishedAt = (new Carbon($randomStartedAt))
                ->addHours(rand(0, 7))
                ->addMinutes(rand(0, 59))
                ->addSeconds(rand(0, 59));

            DB::table('activities')->insert([
                'user_id' => rand(1, 2),
                'description' => AppHelper::randomLoremIpsum(),
                'started_at' => $randomStartedAt,
                'finished_at' => $randomFinishedAt,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
