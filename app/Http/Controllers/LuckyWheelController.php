<?php

namespace App\Http\Controllers;

use App\Models\SpinEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LuckyWheelController extends Controller
{
    const DECIMAL_STEP = 10;

    public function ShowWheel($id)
    {
        $spinEvent = SpinEvent::find($id);
        $items = $spinEvent->products;
        return view('spin.index', [
            'spinEvent' => $spinEvent,
            'items' => $items,
        ]);
    }

    public function LuckyWheel($id)
    {
        $items = SpinEvent::find($id)->products;
        return $this->chance($items);
    }

    public function chance($items)
    {
        $max_value = $items->sum('fixed_percent') * self::DECIMAL_STEP;
        // echo 'The Max Value can be: ' . ($max_value) . '<br>';
        $number = rand(0, $max_value);
        // echo 'Checking for: ' . $number . '<br>';
        $starter = 0;
        foreach ($items as $item) {
            $starter += $item->fixed_percent * self::DECIMAL_STEP;
            // echo 'Current value being tested against is: ' . $starter . ' which is ' . $item->name . '<br>';
            if ($number <= $starter) {
                $winItem = $item->name;
                break;
            }
        }
        return $winItem;
    }
}
