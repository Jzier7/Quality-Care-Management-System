<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncUserAbilities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:abilities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user abilities based on roles to the routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing user abilities...');

        DB::table('abilities')->truncate();

        // Define role IDs
        $adminRoleId = 1;
        $healthcareWorkerRoleId = 2;
        $patientRoleId = 3;

        // Define route IDs
        $allRouteIds = DB::table('routes')->pluck('id')->toArray();

        // Define specific route IDs for each role
        $adminRouteIds = array_diff($allRouteIds, [29, 30, 34, 35, 36, 38]);

        $healthcareWorkerRouteIds = [
            1,
            2,
            8,
            13,
            15,
            16,
            17,
            18,
            19,
            20,
            23,
            29,
            30,
            31,
            32,
            33,
            34,
            35,
            36,
            38,
            43
        ];

        $patientRouteIds = [
            1,
            2,
            9,
            23,
            27,
            31,
            37
        ];

        // Syncing abilities for Admin
        foreach ($adminRouteIds as $routeId) {
            DB::table('abilities')->insert([
                'role_id' => $adminRoleId,
                'route_id' => $routeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Syncing abilities for Healthcare Worker
        foreach ($healthcareWorkerRouteIds as $routeId) {
            DB::table('abilities')->insert([
                'role_id' => $healthcareWorkerRoleId,
                'route_id' => $routeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Syncing abilities for Patient
        foreach ($patientRouteIds as $routeId) {
            DB::table('abilities')->insert([
                'role_id' => $patientRoleId,
                'route_id' => $routeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->info('User abilities synced successfully!');
    }
}
