@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    <a class="btn btn-secondary mb-2" href="{{url('linkedin/create')}}">Create New Account</a>
                    <table id="linkedinUser" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>UUID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Cretaed On</th>
                                <th>Screenshot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($linkedinUsers as $user)
                                <tr>
                                    <td>{{$user->uuid}}</td>
                                    <td>{{$user->first_name}}</td>
                                    <td>{{$user->last_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->password}}</td>
                                    <td>{{$user->created_at}}</td>
                                    <td><button type="button" class="btn btn-secondary viewScreenshot" data-uuid="{{$user->uuid}}" data-toggle="modal" data-target="#screenshotModel">View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            {{$linkedinUsers->links()}}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="screenshotModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Screenshot</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body screenshot-container">

            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#linkedinUser').DataTable();
    });

    $(".viewScreenshot").on("click",function(){
        var uuid = $(this).data("uuid");
        $.ajax({
            url: "{{url('/linkedin')}}/"+uuid,
            type:"GET",
            success: function(result){
                console.log(result);
                $(".screenshot-container").html(result);
                $('.carousel').carousel();
        }});
    });
</script>
@endsection
