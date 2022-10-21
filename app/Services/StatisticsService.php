<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

class StatisticsService
{
    /**
     * Returns a list of statistics about items
     *
     * @return object
     */
    public function getStats()
    {
        $stats = new stdClass;

        $stats->total_items = $this->totalItemsCount();
        $stats->average_item_price = $this->averageItemPrice();
        $stats->total_items_price_this_month = $this->itemsTotalPriceThisMonth();
        $stats->top_website = $this->topItemsWebsite();

        return collect($stats)->toArray();
    }

    /**
     * Returns a count of all existing items
     *
     * @return integer
     */
    private function totalItemsCount()
    {
        return DB::table('items')->count();
    }

    /**
     * Returns average price of an item
     *
     * @return float
     */
    private function averageItemPrice()
    {   
        $averagePrice = DB::table('items')->avg('price');
        
        return number_format($averagePrice, 2, '.', ' ');
    }

    /**
     * Returns the website with the highest total price of its items
     *
     * @return string
     */
    private function topItemsWebsite()
    {
        $websites = DB::table('items')->select('url', 'price')->groupBy('url')->get()
        ->map(function ($item) {
            $item->url = $this->getDomain($item->url);
            return $item;
        })->groupBy('url');

        $sums = $websites->map(function ($website)
        {
            return $website->map(function ($item) {
                return $item->price;
            })->sum();
        });

        $highestValue = max($sums->toArray());
        $highestKey = array_search($highestValue, $sums->toArray());

        return [ 
            'website' => $highestKey, 
            'total' => number_format($highestValue, 2, '.', ' ')
        ];
    }

    /**
     * Retruns total price of items added this month
     *
     * @return float
     */
    private function itemsTotalPriceThisMonth()
    {
        $currentMonth = Carbon::now()->toDateString();
        $totalPrice = DB::table('items')->whereDate('created_at', $currentMonth)->sum('price');
        
        return number_format($totalPrice, 2, '.', ' ');
    }

    private function getDomain($url)
    {
      $pieces = parse_url($url);

      $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];

      if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
      }
      
      return false;
    }

    public function getOneStat(String $label) {
        $topWebsite = $this->topItemsWebsite();
        $topSite = $topWebsite['website'] . ' (' . $topWebsite['total'] . ')';

        return match($label) {
            'Total Items' => $this->totalItemsCount() . ' Items',
            'Average Item Price' => $this->averageItemPrice(),
            'Total Items Price This Month' => $this->itemsTotalPriceThisMonth(),
            'Website with Highest Price' => $topSite,
        };
    }
}