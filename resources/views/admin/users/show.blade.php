@extends('layouts.admin-app')

@section('content')

 <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">

 
        <!-- Extra large table start -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Users</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                   
                </div>
                <div class="container-fluid">
                <div class="table-responsive">
                  <table class="table table-bordered data-table">
                    <thead>
                      <tr>
                        <th>id</th>
                        <th>Username</th> 
                        <th>Name</th> 
                        <th>Email</th>
                        <th>Hiv Status</th>
                        <th>Sexual Orientation</th>
                        <th>Gender</th>
                        <th>Total Post</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody> 
                    </tbody>
                  </table>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Extra large table end -->
  
      </div>


      </div>

      </div>
      </div>

       @endsection
       @section('scripts')

       <script type="text/javascript">
        $(function () {
    
        var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'username', name: 'username'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'}, 
            {data: 'hiv_status', name: 'hiv_status'}, 
            {data: 'sexual_orientation', name: 'sexual_orientation'}, 
            {data: 'gender', name: 'gender'}, 
            {data: 'post_count', name: 'post_count'}, 
            {data: 'status', name: 'status', orderable: false, searchable: false}, 
            {data: 'action', name: 'action', orderable: false, searchable: false},
              ]
          });
          
        });
    </script>


        @endsection