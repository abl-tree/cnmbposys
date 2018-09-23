
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.$ = jQuery;

require('./bootstrap');
window.swal = require('sweetalert2');


// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });





//EJEL -- START
//form reset
	$(document).on('click','#employee-modal-cancel',function(e){
		e.preventDefault();
		$('#employee-form')[0].reset();
        $('.alert-danger').html('');
        $('#employee-form-modal').modal('hide');
        $('.is-invalid').removeClass('is-invalid');
        $('.alert-danger').hide();
	});


//request for dynamic data from db
	$(document).on('change','#position',function(){
	  	if($(this).val() != '')
	  	{
		   var value = $(this).val();
		   var _token = $('input[name="_token"]').val();
		   $.ajax({
		    url:"employee/fetch",
		    method:"POST",
		    data:{value:value, _token:_token},
		    success:function(result)
		    {
		     	$('#designation').html(result);
		    }

		   })
		}
	});
//request for form submition
	// function bindButton() {

	var clicked = false;
    $(document).on("click","#employee-form-submit", function(e) {
        e.preventDefault();
        if(clicked == false){
	        $(this).prop('disabled');
	        var formData = new FormData($('#employee-form')[0]);
	        $("#employee-form input").removeClass('is-invalid');
	           $.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
	           	$.ajax({
	              	url: "/employee",
	              	method: 'post',
	              	dataType:'json',
	              	data:formData,
				    cache: false,
				    contentType: false,
				    processData: false,
	              	success: function(result){
	              	if(result.errors)
	              	{
	              		$('.alert-danger').html('');
	              		console.log(result.errors);
	              		var compact_req_msg='false'; //compact required message
	              		var unique_email_msg='false';
	              		var email_error='false';
	              		$.each(result.errors, function(key, value){
	              			$('#'+key).addClass("is-invalid");
	              			value = ""+value;
	              			console.log(value.indexOf("is required."));
	              			if(value.indexOf("is required.") != -1){
	              				compact_req_msg='true';
	              			}
	              			if(key=='email'){
		              			if(value.indexOf("is already used.")!= -1){
		              				unique_email_msg='true';
		              			}else{
		              				email_error='true';
		              			}
		              		}
	              		});
	              		if(compact_req_msg=='true'){
	              				$('.alert-danger').append('<li style="font-size:0.8em"> Please fill the required fields. </li>');
	              		}
	              		if(unique_email_msg=='true'){
	              				$('.alert-danger').append('<li style="font-size:0.8em"> Your email is already in use. </li>');
	              		}
	              		if(email_error=='true'){
	              				$('.alert-danger').append('<li style="font-size:0.8em"> Please enter valid email. </li>');
	              		}
	          		 	
	          			$('.alert-danger').show();
	              	}
	              	else
	              	{
	              		$('.alert-danger').hide();
	              		swal({
						  type: 'success',
						  title: 'Your work has been saved',
						  showConfirmButton: false,
						  timer: 1500
						});
	              		$('#employee-form-modal').modal('hide');
	              		$('.alert-danger').html('');
	              		$('#employee-form')[0].reset();
	              		$('.is-invalid').removeClass('is-invalid');
	              		clicked='true';
	              	}
	            }
	        });
        }
    });
	// }

// enable when modal is open, wala pa
	// bindButton();

//preview image file on select
	function readURL(input) {

	  if (input.files && input.files[0]) {
	    var reader = new FileReader();

	    reader.onload = function(e) {
	      $('#upload-image-display').attr('src', e.target.result);
	    }

	    reader.readAsDataURL(input.files[0]);
	  }
	}

	$("#photo").change(function() {
	  readURL(this);
	});


	//EJEL -- END

