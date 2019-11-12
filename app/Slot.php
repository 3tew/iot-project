<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\SlotLog;

class Slot extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'slots';
    protected $primaryKey = 'id';

    // Set default values
    protected $attributes = [
        'status' => 'ready',
    ];

    // Appending values
    protected $appends = [
        'present_time',
        'last_in',
        'last_out',
        'count_in_month',
        'count_in'
    ];

    public static function list_data()
    {
        return Slot::orderBy('name', 'asc')->paginate(15);
    }

    public static function countEmptySlot()
    {
        $all_count = Slot::all()->count();
        $busy_count = Slot::where('status', 'busy')->get()->count();
        return $all_count - $busy_count;
    }

    public function getPresentTimeAttribute()
    {
        $last_in = $this->lastIn;
        $time = 0;
        if($last_in) {
            $list_in_time = strtotime($last_in['created_at']);
            $time = ceil(((time() - $list_in_time) / 60) / 60);
        }

        return $time;
    }

    public function getLastInAttribute()
    {
        return SlotLog::where([
            ['slot_id', $this->id],
            ['action', 'in']
        ])->get()->last();
    }

    public function getLastOutAttribute()
    {
        return SlotLog::where([
            ['slot_id', $this->id],
            ['action', 'out']
        ])->get()->last();
    }

    public function getCountInAttribute()
    {
        return SlotLog::where([
            ['slot_id', $this->id],
            ['action', 'in']
        ])->get()->count();
    }

    public function getCountInMonthAttribute()
    {
        return SlotLog::where([
            ['slot_id', $this->id],
            ['action', 'in']
        ])->whereMonth('created_at', date("m", time()))->get()->count();
    }

    public static function create_log($slot, $action)
    {
        $log = new SlotLog;
        $log->slot()->associate($slot);
        $log->action = $action;
        $log->save();
    }
}
