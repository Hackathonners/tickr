<?php

namespace App\Console\Commands;

use App\Karina\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateSearchableNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate indexed searchable names for users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function(){
            DB::table('users')->update(['name_search' => DB::raw('LOWER(name)')]);
        });
    }
}
