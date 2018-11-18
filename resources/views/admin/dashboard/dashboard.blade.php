@extends('admin.default')

@section('content')
<!-- ### $App Screen Content ### --> 
    <div class="row">
        <div class="col-md-12">
            <!-- <example-component></example-component> -->
            <div class="card p-0 pB-10" style='background-color:transparent;min-height:10px;border:0px;'>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col">

                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
                            
        <!-- table manipulation elements -->
            
            <div class="card" style='min-height:10px;'>
                <div class="card-header" style='background-color:white'>
                    <div class="pull-left">
                            <strong>Master List</strong>
                    </div>
                    <div class="pull-right">
                            <span class="ti ti-more-alt"></span>
                    </div>
                </div>
                <div class="card-header py-2 m-0" style='background-color:transparent;border:0px;'>
                    <div class="row">
                        <div class='col-md-3'>
                                <div class="form-group mB-0">
                                    <div class="form-row">
                                        <div class="col-sm-4">
                                            <select v-model="selected" class='form-control' name="" id="displayNo">
                                                <option value="/api/debtors/5" selected>5</option>
                                                <option value="/api/debtors/10">10</option>
                                                <option value="/api/debtors/15">15</option>
                                                <option value="/api/debtors/20">20</option>
                                            </select>
                                        </div>
                                        <div class="col pt-2">
                                            <label for="displayNo">No. of Record</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        <li  class="page-item">
                                            <a class="page-link"  tabindex="-1">Previous</a>
                                        </li>
                                        <li class="page-item disabled">
                                            <a class="page-link text-dark" href="#">1 of 10</a></li>
                                        <li  class="page-item">
                                            <a class="page-link"  href="#">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class='col-md-4'>
                                <div class="form-group mB-0">
                                    <div class="form-row">
                                        <div class="col-md-4 pt-2">
                                            <label for="">Search: </label>
                                        </div>
                                        <div class="col" style='padding-right:0px;'>
                                            <input type="text" class="form-control" style='right:0'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>        
                </div>
                <div class="card-body pT-0">
                    <div  class="row" style='margin:0px 0px 0px 0px;padding:0px 0px 0px 0px'>
                        </div> 
                        <div class='p-0' style="overflow-x:auto">
                            <table id="canteen-table-display p-0" class="table table-hover" style="font-size:.8em;">
                            <thead>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Department</th>
                                <th>Manage</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Department</th>
                                <th>Manage</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Department</th>
                                <th>Manage</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Department</th>
                                <th>Manage</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Department</th>
                                <th>Manage</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Department</th>
                                <th>Manage</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Department</th>
                                <th>Manage</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Department</th>
                                <th>Manage</th>
                            </thead>
                            <tbody>
                            
                                <tr>
                                    <td>Jill</td>
                                    <td>Smith</td>
                                    <td><img src="/images/1.jpg" alt="" class="img-circle"></td>
                                    <td><img src="/images/bg.jpg" alt="" class="img-responsive img-circle"></td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                    <td>50</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                </div>
            </div>

            <div class="card p-0 pY-10" style='background-color:transparent;min-height:10px;border:0px;'>
                <div class="card-body p-0">
                    <div class="row">
                        
                        <div class="col">
                            <div class="layers bd bgc-white">
                                <div class="layer w-100 p-20">
                                    <div class="pull-left">
                                        <strong>Position</strong>
                                    </div>
                                    <div class="pull-right">
                                        <span class="ti ti-more-alt"></span>
                                    </div>
                                </div>
                                <div class="layer w-100 p-5">
                                    <table class="table">
                                        <thead>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Head
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <!-- <div class="layers bdB bgc-white">
                                <div class="layer w-100 mB-10 bgc-red">
                                    <h6 class="lh-1">Bounce Rate</h6>
                                </div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                    <div class="peer peer-greed">
                                        <span id="sparklinedash4"></span>
                                    </div>
                                    <div class="peer">
                                        <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-blue-50 c-blue-500">33%</span>
                                    </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
@endsection
