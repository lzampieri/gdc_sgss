<?php

namespace App\Exports;

use App\Models\User;
use App\Exports\DefaultExport;

class UsersExport extends DefaultExport
{
    public function collection()
    {
        return User::all();
    }
}
