
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 
var $ = require('jQuery');

require('./bootstrap');


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
	    $("#employee-form-submit").on("click", function(e) {
	        e.preventDefault();
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
                  		$('.alert-danger').append('<li style="font-size:0.8em">Please fill the required fields. </li>');
                  		$('.alert-danger').show();
                  		$.each(result.errors, function(key, value){
                  		 $('#'+key).addClass("is-invalid");
                  		});
                  	}
                  	else
                  	{
                  		$('.alert-danger').hide();
                  		$('#employee-form-modal').modal('hide');
                  	}
                  }});
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

