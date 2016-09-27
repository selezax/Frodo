<?php

namespace Test\Frodo\Console\Commands;

use Illuminate\Console\Command;

class ImportTweets extends Command {

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'posts:import';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Import Posts from Twiter';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle() {

        $_accounts = new \Test\Frodo\Lib\Account();
        $_accounts->getPostsByAccountInterval();


    }


}