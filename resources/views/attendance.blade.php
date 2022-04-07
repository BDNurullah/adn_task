@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-8">
        @if($message != "")
        <div class="card">
            <div class="alert alert-danger" role="alert">{{$message}}</div>
        </div>
        @else
        
        <div class="card">
            @if(session("success"))
                <div class="alert alert-success" role="alert">{{session("success")}}</div>
            @endif
            @if(session("failed"))
                <div class="alert alert-danger" role="alert">{{session("failed")}}</div>
            @endif

            <form action="{{route('attendance',$id)}}" method="post">
                @csrf
                <div class="card-header">Information Add</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="employee_name" id="employee_id" class="form-control" placeholder="Type your name">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="remark" id="remark_id" class="form-select">
                                    <option value="">Select a remark</option>
                                    <option value="1">Yes – working from home</option>
                                    <option value="2">Yes</option>
                                    <option value="3">On Leave</option>
                                    <option value="4">Sick Leave</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info" id="submitButton">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        //get data from local storage
        $("#employee_id").val(localStorage.getItem('employee'));
        $("#remark_id").val(localStorage.getItem('remark')).change();
        
        //store data in local storage
        $(document).on("click", "#submitButton", function () {
            localStorage.clear();
            localStorage.setItem("employee", $("#employee_id").val());
            localStorage.setItem("remark", $("#remark_id").val());
        });
    });
</script>
@endpush