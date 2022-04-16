<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportsController extends Controller
{
    const exportables = [
        'users' => [
            'name' => 'Utenti',
            'class' => UsersExport::class
        ],
        'events' => [
            'name' => 'Eventi',
            'class' => EventsExport::class
        ],
        'users_with_deaths' => [
            'name' => 'Utenti con data di morte',
            'class' => UsersWithDeathsExport::class
        ]
    ];

    public static function export( $table, $format ) {
        $exportables = ExportsController::exportables;

        if( !isset( $exportables[$table] ) )
            return response(['error' => true, 'error-msg' => 'Table not recognized'], 404);
        
        return Excel::download( new $exportables[$table]['class'], $table . "." . $format );

    }
}