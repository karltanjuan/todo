<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class ExpiredTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expired-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tasks that have expired after 30 days in the trash';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredTasks = Task::where('is_trashed', 1)
            ->where('expired_at', '<=', Carbon::now()->subDays(30))
            ->get();

        foreach ($expiredTasks as $task) {
            $task->delete();
        }

        $this->info('Expired tasks deleted successfully.');
    }
}
