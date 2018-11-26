import * as $ from 'jquery';
import 'datatables';

export default (function () {
    var position = $('.existing-position').DataTable({
        searching: false,
        info: false,
        autoWidth: false,
        pageLength: 5,
        ajax: "/get_position/level",
        columns: [
            {
                data: 'name',
                orderable: false,
            }
        ],
        oLanguage: {
            sEmptyTable: "No position available.",
            sLoadingRecords: '<i class="fa fa-spinner fa-spin"></i> Fetching Positions'
        }
    });
}());
