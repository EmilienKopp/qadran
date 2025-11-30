<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $projects = Project::factory(10)->create();

            $projects->each(function (Project $project) {
                Task::factory(5)->create(['project_id' => $project->id]);
            });
        });
    }
}
