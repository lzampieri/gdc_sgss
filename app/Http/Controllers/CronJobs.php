<?php

namespace App\Http\Controllers;

use App\Logging\Logger;
use App\Models\NightTask;
use Illuminate\Http\Request;
use App\Models\NightTaskPasscode;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class CronJobs extends Controller
{
    public static function addPasscode( Request $request ) {
        $validated = $request->validate([
            'title' => 'required|string|unique:night_task_passcodes,title',
            'code' => 'required|string|unique:night_task_passcodes,code'
        ]);

        $passcode = NightTaskPasscode::create([
            'title' => $validated['title'],
            'code' => $validated['code']
        ]);

        Log::info( "New cronjob passcode created", Logger::logParams( [ 'passcode' => $passcode ] ) );

        return back()->with( 'positive-message', 'Fatto' );
    }

    public static function deletePasscode( $id ) {
        $passcode = NightTaskPasscode::find( $id );
        if( ! $passcode ) {
            return back()->with( 'negative-message', 'Non trovato' );
        }
        
        $passcode->delete();
        
        Log::info( "Cronjob passcode deleted", Logger::logParams( [ 'passcode' => $passcode ] ) );
        return back()->with( 'positive-message', 'Fatto' );
    }
    
    public static function addTask( $type, Request $request ) {
        if( $type == 'change_option' )
            return CronJobs::addChangeOptionTask( $request );

        return back()->with( 'negative-message', 'Tipologia di compito non trovata' );
    }

    public static function deleteTask( $id ) {
        $task = NightTask::find( $id );
        if( ! $task ) {
            return back()->with( 'negative-message', 'Non trovato' );
        }
        
        $task->delete();
        
        Log::info( "Task deleted", Logger::logParams( [ 'task' => $task ] ) );
        return back()->with( 'positive-message', 'Fatto' );
    }

    public static function run( $code ) {
        $passcode = NightTaskPasscode::where( 'code', $code )->firstOrFail();
        
        $count = 0;
        $log = "";
        foreach( $passcode->tasks as $t ) {
            $log .= $t->explain() . "\n";
            if( $t->action_type == 'change_option' )
                $count += CronJobs::runChangeOptionTask( $t );
        }

        if( $count > 0 )
            Mailer::cronjobs( $log );

        return response()->json([
            'success' => True,
            'task_executed' => $count,
            'log' => $log
        ]);
    }

    public static function addChangeOptionTask( $request ) {
        $validated = $request->validate([
            'passcode' => 'required|exists:night_task_passcodes,id',
            'option_name' => 'required',
            'option_value' => 'required'
        ]);

        // Check if the setting exists
        $setting = null;
        foreach( Settings::editable as $s ) {
            if( $validated['option_name'] == $s['name'] ) {
                $setting = $s;
                break;
            }
        }
        if( $setting == null ) {
            return back()->with( 'negative-message', 'Opzione non esistente' );
        }

        // Check if value exists
        $valid = false;
        foreach( $setting['dict'] as $k => $v ) {
            if( $validated['option_value'] == $k ) {
                $valid = true;
                break;
            }
        }
        if( $valid == false ) {
            return back()->with( 'negative-message', 'Valore non accettabile per questa opzione' );
        }
        
        $task = NightTask::create([
            'action_type' => 'change_option',
            'action_params' => [
                'option_name' => $validated['option_name'],
                'option_value' => $validated['option_value'] 
            ],
            'passcode' => $validated['passcode']
        ]);

        Log::info( "New task created", Logger::logParams( [ 'task' => $task ] ) );
        return back()->with( 'positive-message', 'Fatto' );
    }

    public static function runChangeOptionTask( $t ) {
        Settings::updoption( $t->action_params['option_name'], $t->action_params['option_value'] );
        $t->delete();
        return 1;
    } 
}
