<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DefaultExport implements WithHeadings, FromCollection
{

    public function headings(): array
    {
        return array_keys($this->collection()->first()->toArray());
    }

    public function collection() {
        return null;
    }
}