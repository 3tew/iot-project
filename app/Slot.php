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
        'status' => false,
    ];

    public static function list_data()
    {
        $slots = DB::table('slots')->orderBy('name', 'asc')->paginate(15);
        $slots = $slots->map(function($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'createdAt' => $item->created_at,
                'updatedAt' => $item->updated_at,
                'presentTime' => Slot::find($item->id)->present_time(),
                'countIn' => Slot::find($item->id)->count_in()
            ];
        });
        return $slots;
    }

    public static function create_log($slot, $action)
    {
        $log = new SlotLog;
        $log->slot()->associate($slot);
        $log->action = $action;
        $log->save();
    }

    public function last_in()
    {
        return SlotLog::where([
            ['slot_id', $this->id],
            ['action', 'in']
        ])->get()->last();
    }

    public function last_out()
    {
        return SlotLog::where([
            ['slot_id', $this->id],
            ['action', 'out']
        ])->get()->last();
    }

    public function present_time()
    {
        $last_in = $this->last_in();
        $list_in_time = strtotime($last_in['created_at']);
        $time = floor((time() - $list_in_time) / 60);

        return $time;
    }

    public function count_in()
    {
        return SlotLog::where([
            ['slot_id', $this->id],
            ['action', 'in']
        ])->get()->count();
    }
}
