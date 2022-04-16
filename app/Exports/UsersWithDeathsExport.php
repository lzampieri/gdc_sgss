<?php

namespace App\Exports;

use App\Models\User;
use App\Exports\DefaultExport;

class UsersWithDeathsExport extends DefaultExport
{
    public function collection()
    {
        $users = User::all();
        $users = $users->map( function($u) {
            $u['death_time'] = $u->death_time();
            return $u;
        });
        return $users;
    }
}
