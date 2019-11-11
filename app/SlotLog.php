<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SlotLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'slot_logs';
    protected $primaryKey = 'id';

    // Set default values
    protected $attributes = [
        // 
    ];

    public function slot()
    {
        return $this->belongsTo('App\Slot', 'slot_id');
    }
}
