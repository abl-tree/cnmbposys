import * as $ from 'jquery';
import 'datatables';

export default (function () {

  	/*$('#employee').DataTable({
		language: {
			// 'url' : 'https://cdn.datatables.net/plug-ins/1.10.16/i18n/French.json'
			// More languages : http://www.datatables.net/plug-ins/i18n/
		},
		aaSorting: []
	});*/

	//PROFILE EMPLOYEE LIST -- START

	$('#employee').DataTable({
		processing: true,
		serverSide: true,
		scrollY:        '40vh',
        scrollCollapse: true,

		columnDefs: [
			{
				"targets": "_all", // your case first column
			   "className": "text-center",
				
		   }
		  ],
		
		ajax: "/refreshEmployeeList",
		columns: [
			{data: 'id', name: 'id'},
			{data: 'name', name: 'name'},
			{data: 'birthdate', name: 'birthdate'},
			{data: 'gender', name: 'gender'},
			{data: 'contact_number', name: 'contact_number'},
			{data: 'address', name: 'address'},
			{data: 'salary_rate', name: 'salary_rate'},
			{defaultContent:'<div class="btn-group mr-2" role="group" > <button type="button" class="btn btn-secondary ti-pencil-alt2" style="color:white"></button><button type="button" class="btn btn-danger ti-face-sad" style="color:white"></button></div>'}

		]
	});
	//PROFILE EMPLOYEE LIST -- END


}());
