$(document).ready(function(){	
	
	var accomodationRecords = $('#accomodationListing').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,		
		"bFilter": true,
		'serverMethod': 'post',		
		"order":[],
		"ajax":{
			url:"accomodation_action.php",
			type:"POST",
			data:{action:'listAccomodation'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 3, 4],
				"orderable":false,
			},
		],
		"pageLength": 10
	});		
	
	
	$('#addAccomodation').click(function(){
		$('#accomodationModal').modal({
			backdrop: 'static',
			keyboard: false
		});		
		$("#accomodationModal").on("shown.bs.modal", function () {
			$('#accomodationForm')[0].reset();				
			$('.modal-title').html("<i class='fa fa-plus'></i> Add Accomodation");					
			$('#action').val('addAccomodation');
			$('#save').val('Save');
		});
	});		
	
	$("#accomodationListing").on('click', '.update', function(){
		var id = $(this).attr("id");
		var action = 'getAccomodationDetails';
		$.ajax({
			url:'accomodation_action.php',
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(respData){				
				$("#accomodationModal").on("shown.bs.modal", function () { 
					$('#accomodationForm')[0].reset();
					respData.data.forEach(function(item){						
						$('#id').val(item['id']);						
						$('#accomodationName').val(item['accomodation']);	
						$('#description').val(item['description']);
					});														
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit accomodation");
					$('#action').val('updateAccomodation');
					$('#save').val('Save');					
				}).modal({
					backdrop: 'static',
					keyboard: false
				});			
			}
		});
	});
	
	$("#accomodationModal").on('submit','#accomodationForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"accomodation_action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#accomodationForm')[0].reset();
				$('#accomodationModal').modal('hide');				
				$('#save').attr('disabled', false);
				accomodationRecords.ajax.reload();
			}
		})
	});		

	$("#accomodationListing").on('click', '.delete', function(){
		var id = $(this).attr("id");		
		var action = "deleteAccomodation";
		if(confirm("Are you sure you want to delete this record?")) {
			$.ajax({
				url:"accomodation_action.php",
				method:"POST",
				data:{id:id, action:action},
				success:function(data) {					
					accomodationRecords.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	
	
});

