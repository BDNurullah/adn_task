@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-md-4">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <button type="button" class="btn btn-info" id="generateURLBtn">
                    Generate URL
                </button>
            </div>
        </div>
    </div>
	
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Latest 10 attendance sheets</div>

            <div class="card-body" id="divid">
                <table id="example" class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>name</th>
                            <th>Excel Sheet</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($files as $key=>$file)
                        @if ($file == '.' || $file == '..')
                        @continue
                        @endif
                        
                        @if($key > 10)
                            @break
                        @endif
                        <tr>
                            <td>{{ $key +1 }}</td>
                            <td>{{ date("jS F Y", @filemtime($filePath.$file)) }}</td>
                            <td>{{ $file }}</td>
                            <td>
                                <a href="{{asset('/attendance/'.$file)}}" download" class="btn btn-outline-success btn-xs">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-xs removeFile" file="{{ $file }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td>No Data Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        //unique url generate
        $(document).on("click", "#generateURLBtn", function () {
            $(this).attr('disabled','disabled');
            //get unique url
            $.get("{{route('generateurl')}}").then(function (data) {
                //copy to clipbord
                navigator.clipboard.writeText(data);
                alert("Copied the url: " + data);
                //reload attendance sheets
                $("#divid").load(" #divid");
                $("#generateURLBtn").removeAttr('disabled');
            });
        });

        //deleted Sheets from file
        $('.removeFile').on('click', function () {
            var tr = $(this).closest('tr');
            var file = $(this).attr('file');
            $.post("{{route('delete')}}", {file: file}).then(function (data) {
                tr.remove();
            });
        });
        
    });
</script>
@endpush