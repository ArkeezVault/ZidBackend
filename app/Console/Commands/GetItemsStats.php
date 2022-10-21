<?php

namespace App\Console\Commands;

use App\Services\StatisticsService;
use Illuminate\Console\Command;

class GetItemsStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:stats {--stat : choose which statistic to display}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get items statistics';

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
    public function handle(StatisticsService $stats)
    {
        $statistics = $this->listStats($stats);
        $stat = $this->option('stat');

        $labels = [
            'Total Items',
            'Average Item Price',
            'Total Items Price This Month',
            'Website with Highest Price',
        ];

        if($stat) {
            $prompt = 'Type the number of the desired statistic';

            $label = $this->choice($prompt, $labels, 0);

            $this->line($stats->getOneStat($label));

            return 0;
        }

        $this->table([ 'Stat', 'Value' ], $statistics);
        
        return 0;
    }

    /**
     * Create Table Rows
     *
     * @param StatisticsService $stats
     * @return array
     */
    private function listStats(StatisticsService $stats)
    {
        $all = $stats->getStats();

        return [ 
            [ 
                'Total Items', 
                $all['total_items'] 
            ],
            [ 
                'Average Item Price', 
                $all['average_item_price'] ],
            [ 
                'Total Items Price This Month', 
                $all['total_items_price_this_month'] ],
            [ 
                'Website with Highest Price', 
                $all['top_website']['website'] .' (' . $all['top_website']['total']. ')'
            ],
        ];
    }
}