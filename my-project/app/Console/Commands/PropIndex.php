<?php

namespace App\Console\Commands;

use App\Models\iblock;
use App\Service\Iblocks;
use Illuminate\Console\Command;

class PropIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'propindex:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $childs = iblock::find(1)->getChilds();
        foreach ($childs as $child) {
            Iblocks::getAllProps($child->id, true);
        }
        return 0;
    }
}