<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class AttendanceExport implements FromArray
{
    protected $id;

    function __construct($id) {
        $this->id = $id;
    }
    public function array():array
    {
        //dd($this->id);
        return $this->id;//[['ID', 'Employee Name', 'Remarks', 'Submission time']];
    }
}
