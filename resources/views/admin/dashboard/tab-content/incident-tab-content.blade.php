<div class="row">
<!-- style='position:fixed' -->
    <div class="col-md-2 mL-5 p-0 bd bgc-white db" >  
    <!-- profile -->
        <div class="layers bgc-white">
            @php
                if(!empty($profile->image)){
                    $p1 = substr($profile->image,0,strpos($profile->image,','));
                    $p2 = substr($profile->image,strpos($profile->image,','));

                    echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url('.$profile->image.');width:150px;height:150px;position:absolute; top:50px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                }else{
                    echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg);width:150px;height:150px;position:absolute; top:50px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                }
            @endphp
            <div class="layer mB-10 profile-bg" style='background-image:url("/images/bg.jpg");height:150px;width:100%;background-size:cover;background-repeat:no-repeat;'></div>
            <div class="layer bgc-white w-100p p-10" style="height:calc(100vh - 287px);">
                <div class="min-content mT-40" style="text-align:center;">
                    <h6 style='padding:0;margin:0;'>Emmanuel James Eng Lajom</h6>
                    <small style="font-size:.75em">jamesenglajom@gmail.com</small></h5>
                    <div class="bdc-red-a200 bgc-red-a100 c-white p-5 m-10" style="text-align:center;border:2px solid">
                        <span>Agent</span>
                    </div>
                    <div class="info-container mT-20">
                        <div class="layer">
                            <input type="radio" class="info-radio" name="info-nav" id="tab1" style='display:none;' checked> 
                            <label for="tab1" class="info-button-label" style="font-size:.8em;margin:0px -5px 0px 0px;border-radius:5px 0px 0px 5px;">Info</label>
                            <input type="radio" class="info-radio" name="info-nav" id="tab2" style='display:none;'> 
                            <label for="tab2" class="info-button-label" style="font-size:.8em;margin:0px -5px 0px 0px;">Identification</label>
                            <input type="radio" class="info-radio" name="info-nav" id="tab3" style='display:none;'> 
                            <label for="tab3" class="info-button-label" style="font-size:.8em;margin:0px -5px 0px 0px;border-radius:0px 5px 5px 0px;">Contact</label>
                            <div class="layer p-20 tab-content-wrapper">
                                <div id="tab-content1" class="tab-content">
                                    Info
                                </div>
                                <div id="tab-content2" class="tab-content">
                                    ID
                                </div>
                                <div id="tab-content3" class="tab-content">
                                    Contact
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-md-2 mL-5 p-0"></div> -->
    <div class="col-md-8">
        <!-- leaders -->
        <div class="row ">
            <div class="col">
                <div class="layers bd bgc-white">
                @php
                    if(!empty($profile->image)){
                        $p1 = substr($profile->image,0,strpos($profile->image,','));
                        $p2 = substr($profile->image,strpos($profile->image,','));

                        echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url('.$profile->image.');width:70px;height:70px;position:absolute; top:10px;left:30px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                    }else{
                        echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg);width:70px;height:70px;position:absolute; top:10px;left:30px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                    }
                @endphp
                    <div class="layers mB-10 profile-bg" style='background-image:url("/images/bg.jpg");height:70px;width:100%;background-size:cover;background-repeat:no-repeat;'>
                        <div style="left:110px;position:absolute;top:15px;">
                        <h6 class="c-white" style='padding:0;margin:0;'>Emmanuel James Eng Lajom</h6>
                        <small class="c-white" style="font-size:.75em">jamesenglajom@gmail.com</small>
                        </div>
                    </div>
                    <div class="layers bgc-white w-100p p-10">
                        <div class="min-content" style="text-align:center;">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-2 pL-5"> -->
            <!-- <div id='full-calendar'></div> -->
        </div>

        <!-- co agent -->
        <div class="mT-20">
            <h5><div class="hr bgc-grey-300"><span class="bgc-grey-100" style='padding: 0 10px;'>Co-Agents</span></div></h5>
        </div>

        <div class="row">
            <div class="col">
                <div class="layer p-10">
                    <div class="row">
                        <div class="col-sm-4">
                            @php
                                if(!empty($profile->image)){
                                    $p1 = substr($profile->image,0,strpos($profile->image,','));
                                    $p2 = substr($profile->image,strpos($profile->image,','));

                                    echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url('.$profile->image.');width:70px;height:70px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                                }else{
                                    echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg);width:70px;height:70px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                                }
                            @endphp
                        </div>
                        <div class="col-sm-8 pT-15 pB-15">
                            <b style="font-size:.75em;">Emmanuel James Eng Lajom</b>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="layer p-10">
                    <div class="row">
                        <div class="col-sm-4">
                            @php
                                if(!empty($profile->image)){
                                    $p1 = substr($profile->image,0,strpos($profile->image,','));
                                    $p2 = substr($profile->image,strpos($profile->image,','));

                                    echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url('.$profile->image.');width:70px;height:70px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                                }else{
                                    echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg);width:70px;height:70px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                                }
                            @endphp
                        </div>
                        <div class="col-sm-8 pT-15 pB-15">
                            <b style="font-size:.75em;">Emmanuel James Eng Lajom</b>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="layer p-10">
                    <div class="row">
                        <div class="col-sm-4">
                            @php
                                if(!empty($profile->image)){
                                    $p1 = substr($profile->image,0,strpos($profile->image,','));
                                    $p2 = substr($profile->image,strpos($profile->image,','));

                                    echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url('.$profile->image.');width:70px;height:70px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                                }else{
                                    echo '<div id="" class="profile-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg);width:70px;height:70px;-webkit-box-shadow: 0 10px 6px -6px #777;-moz-box-shadow: 0 10px 6px -6px #777;box-shadow: 0 10px 6px -6px #777;"></div>';
                                }
                            @endphp
                        </div>
                        <div class="col-sm-8 pT-15 pB-15">
                            <b style="font-size:.75em;">Emmanuel James Eng Lajom</b>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="pR-30 mB-0 pB-0" style="text-align:right;width:100%">
                <a href="#">See more...</a>
            </div>
        </div>

        <div class="mT-0">
            <h5><div class="hr bgc-grey-300"><span></span></div></h5>
        </div>


            <!-- report -->
        <div class="row mT-15 mB-10">
            <div class="col">
                <div class="bd bgc-white p-20">
                    <h5>Report</h5>
                    <div class="row">
                        <table id="" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>IR No.</th>
                                    <th>Date</th>
                                    <th>Level</th>
                                    <th>Type</th>
                                    <th>Reply</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2 <span class="badge badge-pill badge-warning">NEW!</span></td>
                                    <td>10/20/18</td>
                                    <td>Written</td>
                                    <td>Absentism</td>
                                    <td><span class='badge badge-pill badge-danger'> NO Reply</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-info ti-eye view-employee"  data-toggle="modal" data-target="#ir-response-modal"></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1 </td>
                                    <td>10/01/18</td>
                                    <td>Verbal</td>
                                    <td>Absentism</td>
                                    <td><span class='badge badge-pill badge-success'>10/02/18</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-info ti-eye view-employee" data-toggle="modal" data-target="#ir-response-modal"></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
                                <!-- hidden -->
        <div class="bdT bgc-white  ta-c p-30 lh-0 fsz-sm c-grey-600 mT-15px" style='visibility:hidden'>
            <span>Copyright © 2018 Designed by
                <a href="https://colorlib.com" target='_blank' title="Colorlib">Colorlib</a> Powered by Solid Script Web Systems. All rights reserved.</span>
        </div>
        
    </div>
    <div class="col bd bgc-white">
        <div class="layer db">
            
        </div>
    </div>
</div>

@include('admin.dashboard.include.ir_response_modal')