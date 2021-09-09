
var origin = window.location.origin;

$(document).ready(function () {

//get_company_data()

$.ajaxSetup({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});

//Get all company
function get_company_data() {
    
	$.ajax({
        url: root_url,
        type:'GET',
    	data: { }
	}).done(function(data){
        table_data_row(data.data)
	});
}

//Company table row
function table_data_row(data) {

    var	rows = '';
    
	$.each( data, function( key, value ) {
        
	  	rows = rows + '<tr>';
	  	rows = rows + '<td>'+value.name+'</td>';
	  	rows = rows + '<td>'+value.address+'</td>';
	  	rows = rows + '<td data-id="'+value.id+'">';
                rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="editCompany" data-id="'+value.id+'" data-toggle="modal" data-target="#modal-id">Edit</a> ';
                rows = rows + '<a class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" id="deleteCompany" data-id="'+value.id+'" >Delete</a> ';
                rows = rows + '</td>';
	  	rows = rows + '</tr>';
	});

	$("tbody").html(rows);
}

//Insert company data
$("body").on("click","#createNewCompany",function(e){

    e.preventDefault;
    $('#userCrudModal').html("Create company");
    $('#submit').val("Create company");
    $('#modal-id').modal('show');
    $('#company_id').val('');
    $('#companydata').trigger("reset");

});

//Save data into database
$('body').on('click', '#submit', function (event) {
    event.preventDefault()
    var id = $("#company_id").val();
    var name = $("#name").val();
    var address = $("#address").val();
   
    $.ajax({
      url: store,
      type: "POST",
      data: {
        id: id,
        name: name,
        address: address
      },
      dataType: 'json',
      success: function (data) {
          
          $('#companydata').trigger("reset");
          $('#modal-id').modal('hide');
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Success',
            showConfirmButton: false,
            timer: 1500
          })
          get_company_data()
      },
      error: function (data) {
          console.log('Error......');
      }
  });

//Edit modal window

 

});

//SHOW POST
$('body').on('click', '.viewPost', function (event) { 

    var id = $(this).attr('data-id');
    $.ajax(
        {
          url: '/admin/posts/'+id,
          type: 'GET',
          success: function (response){ 

            $(".post-content").html(response.content);
            $(".likes").html(response.likes_count + " Peoples liked");
            $(".comment").html(response.comments_count + " Peoples Commented");

            var media = "";
            for(var i=0; i < response.postmeta.length; i++){

                let link = response.postmeta[i].media;
                console.log(response.postmeta[i].media);
                media += "<img src='"+origin+'/images/'+link+"' />";

            }

            $(".post-media").html(media);

            $(".comments").html('<div class="card-body"> <div class="media"> <div class="media-left mr-1"> <a href="#"> <span class="avatar avatar-online"> <img src="../../../app-assets/images/portrait/small/avatar-s-1.png" alt="avatar"> </span> </a> </div> <div class="media-body"> <p class="text-bold-600 mb-0"><a href="#">Jason Ansley</a></p> <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.</p> <ul class="list-inline"> <li class="pr-1"> <a href="#" class=""> <span class="fa fa-thumbs-o-up"></span> Like</a> </li> <li class="pr-1"> <a href="#" class=""> <span class="fa fa-commenting-o"></span> Reply</a> </li> </ul> <div class="media"> <div class="media-left mr-1"> <a href="#"> <span class="avatar avatar-online"> <img src="../../../app-assets/images/portrait/small/avatar-s-18.png" alt="avatar"> </span> </a> </div> <div class="media-body"> <p class="text-bold-600 mb-0"><a href="#">Janice Johnston</a></p> <p>Gravida nulla. Nulla vel metus scelerisque ante sollicitudin.</p> <ul class="list-inline"> <li class="pr-1"> <a href="#" class=""> <span class="fa fa-thumbs-o-up"></span> Like</a> </li> <li class="pr-1"> <a href="#" class=""> <span class="fa fa-commenting-o"></span> Reply</a> </li> </ul> </div> </div> </div> </div> </div>')

            console.log(response);
             
            $("#modal-id").modal('show');
        }
     }); 
});



 //DeleteCompany
 $('body').on('click', '#deleteCompany', function (event) {
    if(!confirm("Do you really want to do this?")) {
       return false;
     }

     event.preventDefault();
    var id = $(this).attr('data-id');
 
    $.ajax(
        {
          url: store+'/'+id,
          type: 'DELETE',
          data: {
                id: id
        },
        success: function (response){
          
            Swal.fire(
              'Remind!',
              'Company deleted successfully!',
              'success'
            )
            get_company_data()
        }
     });
      return false;
   });

}); 