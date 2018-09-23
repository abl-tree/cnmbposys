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
		ajax: "/refreshEmployeeList",
		columns: [
			{data: 'id', name: 'id'},
			{data: 'name', name: 'name'},
			{data: 'birthdate', name: 'birthdate'},
			{data: 'gender', name: 'gender'},
			{data: 'contact_number', name: 'contact_number'},
			{data: 'address', name: 'address'},
			{data: 'salary_rate', name: 'salary_rate'}
		]
	});
	//PROFILE EMPLOYEE LIST -- END


}());
