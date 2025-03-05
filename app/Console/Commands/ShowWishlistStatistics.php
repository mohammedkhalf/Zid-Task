<?php

namespace App\Console\Commands;
use App\Services\ItemService;
use Illuminate\Console\Command;

class ShowWishlistStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wishlist:statistics {--statistic= : The specific statistic to display (total_items_count, average_price, website_with_highest_total_price, total_price_this_month)}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display wishlist statistics';

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
        $itemService = new ItemService();
        $statistics =  $itemService->calculateWishlistStatistics();

        $specificStatistic = $this->option('statistic');

        if ($specificStatistic) {
            if (array_key_exists($specificStatistic, $statistics)) {
                $this->info(ucfirst(str_replace('_', ' ', $specificStatistic)) . ': ' . $statistics[$specificStatistic]);
            } else {
                $this->error('Invalid statistic specified.');
            }
        } else {
            $this->info('Wishlist Statistics:');
            $this->line('Total Items Count: ' . $statistics['total_items']);
            $this->line('Average Price: ' . $statistics['average_price']);
            $this->line('Website with Highest Total Price: ' . $statistics['website_highest_total_price']);
            $this->line('Total Price This Month: ' . $statistics['total_price_this_month']);
        }
    }
}
