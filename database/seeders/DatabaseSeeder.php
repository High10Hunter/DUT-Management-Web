<?php

namespace Database\Seeders;

use App\Models\EAO_staff;
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
        $this->call([
            // UserSeeder::class,
            // ClassSeeder::class
            // MajorSubjectSeeder::class,
            // StudentSeeder::class,
            // LecturerSeeder::class,
            // ModuleStudentSeeder::class,
            EAOStaffSeeder::class,
        ]);
    }
}
