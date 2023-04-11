$(document).ready(function(){	
	
	var roomsRecords = $('#roomsListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": true,
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"rooms_action.php",
			type:"POST",
			data:{action:'listRooms'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 6, 7],
				"orderable":false,
			},
		],
		"pageLength": 10
	});		
	
	
	$('#addRoom').click(function(){
		$('#roomModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#roomModal").on("shown.bs.modal", function () {
			$('#roomForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Add Room");					
			$('#action').val('addRoom');
			$('#hotelPicture').html("");
			$('#save').val('Save');
		});
	});		
	
	$("#roomsListing").on('click', '.update', function(){
		var id = $(this).attr("id");
		var action = 'getRoomDetails';
		$.ajax({
			url:'rooms_action.php',
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(respData){				
				$("#roomModal").on("shown.bs.modal", function () { 
					$('#roomForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#id').val(item['id']);						
						$('#room').val(item['room']);
						if(item['picture']) {
							$('#hotelPicture').html("<img src='../images/"+item['picture']+"' width='60' height='60'>");
						}						
						$('#accomodations').val(item['accomodationId']);
						$('#number_of_person').val(item['number_person']);
						$('#price').val(item['price']);
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit Room Details");
					$('#action').val('updateRoom');
					$('#save').val('Save');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#roomModal").on('submit','#roomForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');		
		$.ajax({
			url:"rooms_action.php",
			method:"POST",			
			data:new FormData(this),
			contentType: false,  
            processData:false,  
			success:function(data){				
				$('#roomForm')[0].reset();
				$('#roomModal').modal('hide');				
				$('#save').attr('disabled', false);
				roomsRecords.ajax.reload();
			}
		})
	});		

	$("#roomsListing").on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deleteRoom";
		if(confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url:"rooms_action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data) {					
					roomsRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
	
	$("input[name=picture]").change(function () {
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				var img = $('<img>').attr({'src': e.target.result, 'width' : '60', 'height' : '60'});
				$('#hotelPicture').html(img);
			};
			reader.readAsDataURL(this.files[0]);	
		}
	}); 
	
});

