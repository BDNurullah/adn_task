<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\AttendanceExport;
use App\Exports\AttendanceImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $filePath = public_path('attendance/');
        $files = (array) @scandir($filePath,1) ?? [];
        
        return view('home', compact('files','filePath'));
    }
    
    public function generateUrl()
    {
        $userID = Auth::user()->id;
        //generate unique number by the combination of user id and time
        $key = $userID.time();
        $url = url('attendance/'.$key);
        
        //generate A single and unique excel sheet
        $sheetHeading = [['ID', 'Employee Name', 'Remarks', 'Submission time']];
        Excel::store(new AttendanceExport($sheetHeading), $key.'.xlsx');
        
        return $url;
    }
    
    public function attendance($id)
    {
        $message = "";
        $userID = strlen((string)Auth::user()->id);//check user id lenth
        $time = substr($id, $userID);//remove user id for get time
        $generateDate = strtotime(date("Y-m-d", $time));
        $currentDate = strtotime(date("Y-m-d"));
        //check generated file name and URL name is the same
        $exists = Storage::disk('local')->has($id.'.xlsx');
        
        if(!$exists){
            $message = "Sorry, the url is not valid.";
        }
        //check url validity
        if($generateDate < $currentDate){
            $message = "Sorry, the attendance collection is expired for today.";
        }
        
        return view('attendance',compact('id', 'message'));
    }
    
    public function attendanceStore(Request $request, $id)
    {
        $remarks = ['1' => 'Yes – working from home','2' => 'Yes','3' => 'On Leave','4' => 'Sick Leave'];
        
        //get previes attendance data from excel
        $oldData = Excel::toArray(new AttendanceImport, $id.'.xlsx');
        //check duplicate records
        $employeeCheck = array_search($request->employee_name, array_column($oldData[0], 1));
        if($employeeCheck){
            return back()->with('failed', 'This employee attendance info has already been saved');
        }
        
        //new attendance data
        $newData = [['',$request->employee_name,$remarks[$request->remark], date('h:i:s')]];
        
        //merge previes attendance and new attendance
        $attendance = array_merge($oldData[0],$newData);
        //Store data in excell
        $status = Excel::store(new AttendanceExport($attendance), $id.'.xlsx');
        
        if($status){
            return back()->with('success', 'Attendance data save successfully');
        }else{
            return back()->with('failed', 'Attendance data save failed');
        }
    }
    
    public function fileDelete(Request $request)
    {
        $filename = public_path('attendance/'.$request->file);
        if($filename){
            unlink($filename);
        }
        return '';
    }
}
