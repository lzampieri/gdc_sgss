<?php

namespace App\Http\Controllers;

use App\Logging\Logger;
use App\Logging\TelegramLogger;
use App\Models\Event;
use App\Models\NightTask;
use Illuminate\Http\Request;
use App\Models\NightTaskPasscode;
use App\Models\PendingKill;
use App\Models\Setting;
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
        if( $type == 'shuffle_cycle' )
            return CronJobs::addNightShuffleTask( $request );
        if( $type == 'add_event' )
            return CronJobs::addEventTask( $request );

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
            if( $t->action_type == 'shuffle_cycle' )
                $count += CronJobs::runNightShuffleTask( $t );
            if( $t->action_type == 'add_event' )
                $count += CronJobs::runEventTask( $t );
        }

        if( $count > 0 )
            TelegramLogger::cronjobs( $log );

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

    public static function addNightShuffleTask( $request ) {
        $validated = $request->validate([
            'passcode' => 'required|exists:night_task_passcodes,id',
            'cycle_name' => 'required'
        ]);
        
        // Check if the cycle exists
        $cycle_name = null;
        foreach( Settings::cycles as $c ) {
            if( $validated['cycle_name'] == $c['name'] ) {
                $cycle_name = $c;
                break;
            }
        }
        if( $cycle_name == null ) {
            return back()->with( 'negative-message', 'Ciclo non esistente' );
        }

        $task = NightTask::create([
            'action_type' => 'shuffle_cycle',
            'action_params' => [
                'cycle_name' => $validated['cycle_name']
            ],
            'passcode' => $validated['passcode']
        ]);

        Log::info( "New task created", Logger::logParams( [ 'task' => $task ] ) );
        return back()->with( 'positive-message', 'Fatto' );
    }

    public static function runNightShuffleTask( $t ) {
        // Retrive the current cycle
        $current = json_decode( Settings::obtain( $t->action_params['cycle_name'] ) );

        // Mix the current cycle
        shuffle( $current );

        // Save the mixed cycle
        Settings::updoption( $t->action_params['cycle_name'], json_encode( $current ) );

        return 1;
    }

    public static function addEventTask( $request ) {
        $validated = $request->validate([
            'passcode' => 'required|exists:night_task_passcodes,id',
            'actor' => 'required|exists:users,id',
            'target' => 'required|exists:users,id',
            'finalState' => 'required|int'
        ]);

        $sendmail = $request->has( 'sendmail' );
        $resurrections = $request->has( 'resurrections' );

        $task = NightTask::create([
            'action_type' => 'add_event',
            'action_params' => [
                'actor' => $validated['actor'],
                'target' => $validated['target'],
                'finalState' => $validated['finalState'],
                'sendmail' => $sendmail,
                'resurrections' => $resurrections
            ],
            'passcode' => $validated['passcode']
        ]);

        Log::info( "New task created", Logger::logParams( [ 'task' => $task ] ) );
        return back()->with( 'positive-message', 'Fatto' );
    }

    public static function runEventTask( $t ) {
        if( $t->action_params['finalState'] >= 0 ) {
            $event = Event::create( [
                'actor' => $t->action_params['actor'],
                'target' => $t->action_params['target'],
                'finalstate' => $t->action_params['finalState']
            ]);
            Log::info("Created event", Logger::logParams(['event' => $event] ) );

            if( $t->action_params['sendmail'] ) {
                Mailer::event_created( $event );
            }

            if( ( $t->action_params['finalState'] == 0 ) &&  $t->action_params['resurrections'] ) {
                Pendings::resuscitate( $event, $t->action_params['sendmail'] );

            }
        }
        if( $t->action_params['finalState'] == -1 ) {
            $pending = PendingKill::create( [
                'actor' => $t->action_params['actor'],
                'target' => $t->action_params['target']
            ]);
            Log::info("Created pending event", Logger::logParams(['pending_event' => $pending] ) );

            if( $t->action_params['sendmail'] ) {
                Mailer::pending_create( $pending );
            }
        }

        $t->delete();
        return 1;
    }
}
