@extends('admin.default')

@section('content')
<!-- ### $App Screen Content ### -->
<div class="full-container">
    <div class="email-app">
        <div class="email-side-nav remain-height ov-h">

            <div class="h-100 layers">
                <div class="p-20 bgc-grey-100 layer w-100">
                    <div class="card">
                        <div class="box">
                            <div class="img">
                                <img src="images/user.png">
                            </div>
                            <h2>{{ auth()->user()->name }}<br><span>*insert hierarchy tier*</span></h2>
                            <span> SSS Number: </span>
                            <br>
                            <span> Another Number: </span>
                            <br>
                            <span> Another Number: </span>
                            <br>
                            <button type="submit" class="btn cur-p btn-dark"><span class="ti-pencil-alt"></span> Edit</button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="email-wrapper row remain-height bgc-white ov-h">
            <!-- Content -->
            <div class="bdT pX-40 pY-30 col-md-12">
                <h4 class="c-grey-900 mB-20">Employee list  </h4>
             
                <button type="submit" class="btn cur-p btn-dark reposition"><span class="ti-pencil-alt"></span> Add</button>
               
                <table id="employee" class="table table-striped table-bordered" cellspacing="0" width="100%">
          
                    <thead>
                        <tr>
                            <th>User ID No.</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Address</th>
                            <th>Birthday</th>
                            <th>Rate</th>


                        </tr>
                    </thead>
                </table>
            </div>



        </div>
    </div>
</div>




@endsection
