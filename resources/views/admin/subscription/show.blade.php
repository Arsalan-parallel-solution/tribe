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
                        <th>Plan</th> 
                        <th>Amount</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody> 
                      @foreach($data as $key => $values)

                     <tr>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>

                     </tr> 


                      @endforeach
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
   
 