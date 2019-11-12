<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Slot;
use App\SlotLog;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slots = Slot::list_data();

        return response()->json(['data' => $slots]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $slot = Slot::find($id);
        // if($slot && $slot->delete()) {
        //     return response()->json(['status' => 'Deleted']);
        // } else {
        //     return $this->bad_request();
        // }
    }

    public function present($payload, $token)
    {
        if (!$this->verify_access_token($token)) return $this->bad_request();

        $dataArray = explode(',', $payload);

        foreach ($dataArray as $key => $value) {
            $data = explode('|', $value);
            $slot = Slot::where('name', $data[0])->get()->first();

            if($slot) {
                if($data[1] == 255 && $slot->status == false) Slot::create_log($slot, 'in');
                if($data[1] == 0 && $slot->status == true) Slot::create_log($slot, 'out');
                ($data[1] == 255) ? $slot->status = true : $slot->status = false;
                $slot->save();
            } else return $this->bad_request();
        }
        return response()->json(['message' => 'OK']);
    }

    /* PRIVATE METHOD */

    private function bad_request()
    {
        return response()->json(['message' => 'Bad request'], 400);
    }

    private function verify_access_token($token)
    {
        $def_token = 'JustAVeryStrongAndCuteToken';
        if($token == $def_token) return true;
        else return false;
    }
}
