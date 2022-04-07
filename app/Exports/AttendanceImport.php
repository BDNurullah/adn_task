<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class AttendanceImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        return $collection;
    }
}
