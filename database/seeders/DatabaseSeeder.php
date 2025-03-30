<?php

namespace Database\Seeders;

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
            AnnouncementSeeder::class,
            CategorySeeder::class,
            CourseEnrollmentSeeder::class,
            CourseSystemSeeder::class,
            HomeworkSeeder::class,
            ResourceSystemSeeder::class,
            TestCoursesSeeder::class,
            UpdateUserPhonesSeeder::class,
        ]);
    }
}