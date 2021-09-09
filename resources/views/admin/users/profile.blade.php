@extends('layouts.admin-app')

@section('content')

<div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">
        <div id="user-profile">
          <div class="row">
            <div class="col-12">
              <div class="card profile-with-cover">
                <div class="card-img-top img-fluid bg-cover height-300" style="background: url('../../../app-assets/images/carousel/22.jpg') 50%;"></div>
                <div class="media profil-cover-details w-100">
                  <div class="media-left pl-2 pt-2">
                    <a href="#" class="profile-image">
                      <img src="../../../app-assets/images/portrait/small/avatar-s-8.png" class="rounded-circle img-border height-100"
                      alt="Card image">
                    </a>
                  </div>
                  <div class="media-body pt-3 px-2">
                    <div class="row">
                      <div class="col">
                        <h3 class="card-title"> {{ $user->name }} </h3>
                        <h3 class="card-title"> USERNAME @ {{ $user->username }} </h3>
                      </div>
                      <div class="col text-right">
                        <button type="button" class="btn btn-primary"> {{ $countFollowers }} Followers</button>
                        <button type="button" class="btn btn-primary"> {{ $countFollowings }} Following</button>
                        <div class="btn-group d-none d-md-block float-right ml-2" role="group" aria-label="Basic example">
                          <button type="button" class="btn btn-success"><i class="fa fa-dashcube"></i> Message</button>
                          <button type="button" class="btn btn-success"><i class="fa fa-cog"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <nav class="navbar navbar-light navbar-profile align-self-end">
                  <button class="navbar-toggler d-sm-none" type="button" data-toggle="collapse" aria-expanded="false"
                  aria-label="Toggle navigation"></button>
                  <nav class="navbar navbar-expand-lg">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                          <a class="nav-link" href="#"><i class="ft-user"></i> Profile</a>
                        </li>
                        <li class="nav-item active">
                          <a class="nav-link" href="#"><i class="fa fa-line-chart"></i> Timeline <span class="sr-only">(current)</span></a>
                        </li>
                        
                        <li class="nav-item">
                          <a class="nav-link" href="#"><i class="fa fa-briefcase"></i> Subscriptions</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#"><i class="fa fa-heart-o"></i> Reports </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#"><i class="fa fa-bell-o"></i> Activity </a>
                        </li>
                      </ul>
                    </div>
              </div>


              </nav>
            </div>
          

          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Profile Details</h4>
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
              <div class="card-content collapse show">
                <div class="card-body">
                  <p class="card-text"></p>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr> 
                        <th scope="col">Email</th>
                        <th scope="col">{{ $user->email }}</th> 
                      </tr>
                    </thead>
                    <tbody>
                      <tr> 
                        <td>Name</td>
                        <td>{{ $user->name }}</td> 
                      </tr>
                      <tr>
                         
                        <td>phone</td>
                        <td>{{ $user->phone }}</td> 
                      </tr>
                      <tr> 
                        <td>Email verificaiton </td>
                        <td>{{ $user->email_verify==1 ? 'verified' : 'unverified'    }}</td> 
                      </tr>
                      <tr> 
                        <td>Age </td>
                        <td>{{ $user->userMeta->age    }}</td> 
                      </tr>
                      <tr> 
                        <td>Height </td>
                        <td>{{ $user->userMeta->height    }}</td> 
                      </tr>
                      <tr> 
                        <td>Weight </td>
                        <td>{{ $user->userMeta->weight    }}</td> 
                      </tr>
                      <tr> 
                        <td>Gender </td>
                        <td>{{ $user->userMeta->gender    }}</td> 
                      </tr>
                      <tr> 
                        <td>Sexual Orientation </td>
                        <td>{{ $user->userMeta->sexual_orientation    }}</td> 
                      </tr>
                      <tr> 
                        <td>Pronoun </td>
                        <td>{{ $user->userMeta->pronouns    }}</td> 
                      </tr>
                      <tr> 
                        <td>Ethnicity </td>
                        <td>{{ $user->userMeta->ethnicity    }}</td> 
                      </tr>
                      <tr> 
                        <td>Hiv Status </td>
                        <td>{{ $user->userMeta->hiv_status    }}</td> 
                      </tr>
                      <tr> 
                        <td>Social Media Links </td>
                        <td>{{ $user->userMeta->social_media_links!=null ? :unserialize($user->userMeta->social_media_links)    }}</td> 
                      </tr>
                      <tr> 
                        <td>Description </td>
                        <td>{{ $user->userMeta->description    }}</td> 
                      </tr>
                      <tr> 
                        <td>Looking for </td>
                        <td>{{ $user->userMeta->looking_for    }}</td> 
                      </tr>
                      <tr> 
                        <td>Position </td>
                        <td>{{ $user->userMeta->position    }}</td> 
                      </tr>
                      <tr> 
                        <td>Hangout </td>
                        <td>{{ $user->userMeta->hangout    }}</td> 
                      </tr>
                      <tr> 
                        <td>Tribe </td>
                        <td>{{ $user->userMeta->tribe    }}</td> 
                      </tr>
                      <tr> 
                        <td>Signup Date </td>
                        <td>{{ $user->userMeta->created_at    }}</td> 
                      </tr> 
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>



           <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Timeline By DATATABLE</h4>
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
              <div class="card-content collapse show">
                <div class="card-body">
                  <p class="card-text"></p>
                </div>
                <div class="col-12">
                <div class="table-responsive">
                   <table class="table table-bordered data-table">
                    <thead>
                      <tr>
                        <th scope="col">Post ID</th>
                        <th scope="col">Content</th>
                        <th scope="col">Views</th>
                        <th scope="col">Media</th>
                        <th scope="Totl">Total Likes</th>
                        <th scope="col">Total Comments</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
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
 

                     <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Reports</h4>
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
              <div class="card-content collapse show">
                <div class="card-body">
                  <p class="card-text"></p>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr> 
                        <th scope="col">ID</th>
                        <th scope="col">Description</th>
                        <th scope="col">Report Type</th> 
                        <th scope="col">Date</th> 
                        <th scope="col">Action</th>

                      </tr>
                    </thead>
                    <tbody>
                      
                      @foreach($reports as $keys => $report)
                      
                        <tr> 
                        <td> {{ $keys+1 }} </td>
                        <td> {{ $report->description }} </td>
                        <td> {{ $report->type }} </td>
                        <td> {{ $report->created_at }} </td>
                        <td>

                          @if($report->type=="post")

                          <a class="btn btn-primary" href="#" > view post </a> 

                          @elseif($report->type=="comment")

                          <a class="btn btn-primary" href="#" > view comment </a>

                          @else

                          <a class="btn btn-primary" href="#" > view profile </a>

                          @endif
                         
                        </td> 

                      </tr>
                       
                      @endforeach
                          
                    </tbody>
                  </table>
                 
                </div>
              </div>
            

                    <div class="card-footer px-0 py-0">
                    <div class="card-body">
                      

                       <div class="d-flex justify-content-center">
                     {!! $reports->links() !!} 
                    </div> 
                    </div>
                    
                  </div>

                </div>

          </div>



          </div>
        </div>


            </div>
    </div>
  </div>

<div class="modal fade" id="modal-id">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title" id="userCrudModal"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body">
                <form id="companydata">
                    
                    <div class="user">
                    <a href="#" class="profile-image">
                      <img src="../../../app-assets/images/portrait/small/avatar-s-8.png" class="rounded-circle img-border height-50"
                      alt="Card image">
                    </a>
                    <span>@ {{ $user->username }}</span><b> shared this post.</b>
                    
                    <br>
                    
                    </div>
                    <div class="row">
                      <div class="col-6">

                        <div class="post-media">

                        </div>

                        <div class="post-details">
                        <h1 class="post-content"></h1> 
                        <span class="likes"></span> | 
                        <span class="comment"></span>

                        </div>

                    </div>
                     
                      <div class="col-6">
                          <h2>Comments</h2>
                          <div class="comments">
 
                          </div>

                      </div>

                    </div>
                    

                    

                    

                    
                    </label><br>

                     

                </form>
            </div>

        </div>
    </div>
</div> 
       @endsection
       @section('scripts')
       <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/style.css') }}">
       <script type="text/javascript" src="{{ asset('admin/ajax.js') }}"></script>

       <script type="text/javascript">
            $(function () {
    
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.post',$user->id) }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'content', name: 'username'},
                    {data: 'views', name: 'name'}, 
                    {data: 'postmeta', name: 'postmeta'}, 
                    {data: 'comment_count', name: 'comment_count'}, 
                    {data: 'likes_count', name: 'likes_count'},  
                    {data: 'created_at', name: 'created_at'},  
                     {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
            
          });
    </script>


        @endsection