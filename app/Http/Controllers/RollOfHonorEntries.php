<?php

namespace App\Http\Controllers;

use App\Logging\Logger;
use App\Models\RollOfHonorEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RollOfHonorEntries extends Controller
{
    public static function create_entry() {
        $new_entry = RollOfHonorEntry::create([
                'draft' => true,
                'title' => "",
                'visible_area' => "",
                'hidden_area' => ""
            ]);
        
        Log::info("Roll of honor created", Logger::logParams( [ 'roh' => $new_entry ] ) );

        return back()->with( 'positive-message', 'Fatto!')->with( 'roh_id', $new_entry->id );
    }

    public static function update_entry( Request $request ) {
        $validated = $request->validate([
            'id' => 'required|int|exists:roll_of_honor_entries,id',
            'draft' => 'required|in:0,1',
            'title' => 'required',
            'visible_area' => 'required',
            'hidden_area' => 'required'
        ]);

        $upd_entry = RollOfHonorEntry::findOrFail( $validated['id'] );

        $upd_entry->draft = $validated['draft'] > 0 ? true : false;
        $upd_entry->title = $validated['title'];
        $upd_entry->visible_area = $validated['visible_area'];
        $upd_entry->hidden_area = $validated['hidden_area'];

        $upd_entry->save();
        
        Log::info("Roll of honor updated", Logger::logParams( [ 'roh' => $upd_entry ] ) );

        return back()->with( 'positive-message', 'Fatto!')->with( 'roh_id', $upd_entry->id );
    }
}
