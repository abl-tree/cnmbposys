@extends('admin.default')

@section('content')


  <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
        <div class="bd bgc-white">
          <div class="layers">
              <div class="layer w-100 p-20">
                <div class="container">
                    <div class="row bdB">
                        <div class="col-md-8">
                            <div class="row pB-5 bdB">
                                <div class="col">
                                  <div class="peers ai-sb fxw-nw">
                                      <div class="peer mR-10">
                                          <img class="bdrs-50p w-3r h-3r" src="/images/nobody.jpg">
                                      </div>
                                      <div class="peer peer-greed">
                                          <div class="container">
                                              <div class="row peers m-0 p-0">
                                                  <div class="peer peer-greed">
                                                  <span>
                                                      <div class="fw-500 c-blue-500 p-0 m0">Emmanuel James Lajom</div>
                                                      <span style="font-size:0.8em">jamesenglajom@gmail.com</span>
                                                  <span class="mX-5 bgc-grey" style="font-size:0.7em">&#9679;</span>
                                                  <span style="font-size:0.8em">Representative - Order Placer</span>
                                                  </span>

                                                  </div>
                                                  <div class="peer">
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                </div>
                            </div>
                            <div class="row pY-10 bdB">
                              <div class="col-md-3">
                                  <div class="layer w-100 bdL bdR p-20">
                                      
                                      <div class="layer w-100 text-left">
                                          <small>STAT 1</small>
                                      </div>
                                      <div class="layer w-100 text-center">
                                          <h1>12000</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="layer w-100 bdL bdR p-20">
                                      
                                      <div class="layer w-100 text-left">
                                          <small>STAT 2</small>
                                      </div>
                                      <div class="layer w-100 text-center">
                                          <h1>12000</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="layer w-100 bdL bdR p-20">
                                      
                                      <div class="layer w-100 text-left">
                                          <small>STAT 3</small>
                                      </div>
                                      <div class="layer w-100 text-center">
                                          <h1>12000</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="layer w-100 bdL bdR p-20">
                                      
                                      <div class="layer w-100 text-left">
                                          <small>STAT 4</small>
                                      </div>
                                      <div class="layer w-100 text-center">
                                          <h1>12000</h1>
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div class="row pY-10 bdB">
                                <div class="col-md-6 peers pR-30">
                                    <div class="peer mL-20 mR-15">
                                        <button class="btn btn-primary">START WORK</button>
                                    </div>
                                  <div class="peer peer-greed h-100 text-center" style='display:table'>
                                      <div style='display:table-cell;vertical-align:middle'>
                                          <h6><small class='c-grey-800'>WORK DURATION:</small></h6>
                                      </div>
                                  </div>
                                  <div class="peer">
                                      <h3>3.5</h3>
                                  </div>
                                </div>
                                <div class="col-md-6 peers pR-30">
                                    <div class="peer mL-20 mR-15">
                                        <button class="btn btn-primary">BREAK</button>
                                    </div>
                                  <div class="peer peer-greed h-100 text-center" style='display:table'>
                                      <div style='display:table-cell;vertical-align:middle'>
                                          <h6><small class='c-grey-800'>BREAK DURATION:</small></h6>
                                      </div>
                                  </div>
                                  <div class="peer">
                                      <h3>0.5</h3>
                                  </div>
                                </div>
                            </div>
                            <div class="row pT-30 pB-5 mB-0">
                                <div class="col text-center">
                                    <button class="btn btn-primary" style="border-radius:20px">TRACKER</button>
                                </div>
                                <div class="col text-center">
                                    <button class="btn bgc-white" style="border-radius:20px">TEAM</button>
                                </div>
                                <div class="col text-center">
                                    <button class="btn bgc-white" style="border-radius:20px">WORK REPORT</button>
                                </div>
                                <div class="col text-center">
                                    <button class="btn bgc-white" style="border-radius:20px">INCIDENT REPORT</button>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <mini-calendar></mini-calendar>
                        </div>
                    </div>
                    <div class="row">
                      <work-graph :userId='1'></work-graph>
                    </div>
                    <div class="row">
                    </div>
                </div>
                
              </div>
          </div>
        </div>
    </div>
    <div class="masonry-item w-100">
      <!-- #Sales Report ==================== -->
      <today-activity></today-activity>
    </div>
    
    <div class="masonry-item w-100">
      <div class="bd bgc-white">
        <div class="layers">
          <div class="layer w-100 p-20">
            <h6 class="lh-1">Event Request(RTA view)</h6>
          </div>
          <div class="layer p-20 w-100">
            <input id="tab1" type="radio" class="input-table-tabs" checked>
            <label for="tab1" class='label-table-tabs'>Codepen</label>

            <input id="tab2" type="radio" class="input-table-tabs">
            <label for="tab2" class='label-table-tabs'>Dribbble</label>

            <input id="tab3" type="radio" class="input-table-tabs">
            <label for="tab3" class='label-table-tabs'>Stack Overflow</label>

            <input id="tab4" type="radio" class="input-table-tabs">
            <label for="tab4" class='label-table-tabs'>Bitbucket</label>
          </div>
          <div class="layer w-100 pX-20">
            <!-- <div class="input-group">
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Agent...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Supervisor...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Manager...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Attendance...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Log Status...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <div class="input-group-append">
                  <button class="btn btn-outline-secondary btn-sm" type="button">Clear</button>
              </div>
            </div> -->
            <div class="table-responsive p-20">
              <table class="table">
                <thead>
                  <tr>
                    <th class="bdwT-0">Name</th>
                    <th class="bdwT-0">Type</th>
                    <th class="bdwT-0">Date range</th>
                    <th class="bdwT-0">Request Date</th>
                    <th class="bdwT-0">Status</th>
                    <th class="bdwT-0">RTA remarks</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style='font-size:.95em'><div class="fw-600">Agent Name</div><div><i class="ti-email c-grey-400 mR-5"></i>agent@gmail.com</div></td>
                    <td style='font-size:.95em'><div>Vacation Leave</div></td>
                    <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20 <span class="mX-5 ti-calendar c-blue-500"></span></div></td>
                    <td style='font-size:.95em'><div>2019/03/15</div></td>
                    
                    <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-green-100 c-green-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-check c-green-500 fw-900"></span></span>APPROVED</span><span class="mL-15 ti-pencil c-blue-500"></span></div></td>
                    <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                  </tr>
                  
                  <tr>
                    <td style='font-size:.95em'><div class="fw-600">Agent Name</div><div><i class="ti-email c-grey-400 mR-5"></i>agent@gmail.com</div></td>
                    <td style='font-size:.95em'><div>Vacation Leave</div></td>
                    <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20 <span class="mX-5 ti-calendar c-blue-500"></span></div></td>
                    <td style='font-size:.95em'><div>2019/03/15</div></td>
                    <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-red-100 c-red-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-alert c-red-500 fw-900"></span></span>DENIED</span><span class="mL-15 ti-pencil c-blue-500"></span></div></td>
                    <td style='font-size:.95em'><div class='c-grey-900'>Please send another request for another date.</div></td>
                  </tr>
                  <tr>
                    <td style='font-size:.95em'><div class="fw-600">Agent Name</div><div><i class="ti-email c-grey-400 mR-5"></i>agent@gmail.com</div></td>
                    <td style='font-size:.95em'><div>Vacation Leave</div></td>
                    <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20 <span class="mX-5 ti-calendar c-blue-500"></span></div></td>
                    <td style='font-size:.95em'><div>2019/03/15</div></td>
                    
                    <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-orange-100 c-orange-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-time c-orange-500 fw-900"></span></span>PENDING</span><span class="mL-15 ti-pencil c-blue-500"></span></div></td>
                    <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                  </tr>
                  
                  <tr>
                    <td style='font-size:.95em'><div class="fw-600">Agent Name</div><div><i class="ti-email c-grey-400 mR-5"></i>agent@gmail.com</div></td>
                    <td style='font-size:.95em'><div>Vacation Leave</div></td>
                    <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20 <span class="mX-5 ti-calendar c-blue-500"></span></div></td>
                    <td style='font-size:.95em'><div>2019/03/15</div></td>
                    
                    <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-close c-grey-500 fw-900"></span></span>EXPIRED</span></div></td>
                    <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="masonry-item col-md-12">
      <!-- #Sales Report ==================== -->
      <div class="bd bgc-white">
        <div class="layers">
          <div class="layer w-100 p-20">
            <h6 class="lh-1">Event Request(Agent View)</h6>
          </div>
          <div class="layer w-100 pX-20">
            <!-- <div class="input-group">
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Agent...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Supervisor...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Manager...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Attendance...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <select class="custom-select custom-select-sm" id="inputGroupSelect04">
                  <option selected>Log Status...</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
              </select>
              <div class="input-group-append">
                  <button class="btn btn-outline-secondary btn-sm" type="button">Clear</button>
              </div>
            </div> -->
            <div class="table-responsive p-20">
              <table class="table">
                <thead>
                  <tr>
                    <th class="bdwT-0">Status</th>
                    <th class="bdwT-0">Type</th>
                    <th class="bdwT-0">Date range</th>
                    <th class="bdwT-0">Request Date</th>
                    <th class="bdwT-0">RTA remarks</th>
                    <th class="bdwT-0">Response Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-green-100 c-green-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-check c-green-500 fw-900"></span></span>APPROVED</span></div></td>
                    <td style='font-size:.95em'><div>Vacation Leave</div></td>
                    <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20</div></td>
                    <td style='font-size:.95em'><div>2019/03/15</div></td>
                    <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                    <td style='font-size:.95em'><div class='c-grey-900'>2019/03/30</div></td>
                  </tr>
                  <tr>
                    <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-red-100 c-red-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-alert c-red-500 fw-900"></span></span>DENIED</span></div></td>
                    <td style='font-size:.95em'><div>Vacation Leave</div></td>
                    <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20</div></td>
                    <td style='font-size:.95em'><div>2019/03/15</div></td>
                    <td style='font-size:.95em'><div class='c-grey-900'>Please send another request for another date.</div></td>
                    <td style='font-size:.95em'><div class='c-grey-900'>2019/03/30</div></td>
                  
                  </tr>
                  <tr>
                    <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-orange-100 c-orange-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-time c-orange-500 fw-900"></span></span>PENDING</span><span class="mL-15 ti-reload c-blue-500"></span></div></td>
                    <td style='font-size:.95em'><div>Vacation Leave</div></td>
                    <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20</div></td>
                    <td style='font-size:.95em'><div>2019/03/15</div></td>
                    <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                    <td style='font-size:.95em'><div class='c-grey-900'>2019/03/30</div></td>
                  
                  </tr>
                  <tr>
                    <td style='font-size:.99em'><span class="badge badge-pill p-5 bgc-grey-100 c-grey-800 fw-900"><span class='badge badge-pill p-3 bgc-white mR-5'><span class="ti-close c-grey-500 fw-900"></span></span>EXPIRED</span></div></td>
                    <td style='font-size:.95em'><div>Vacation Leave</div></td>
                    <td style='font-size:.95em'><div>2019/04/10 - 2019/04/20</div></td>
                    <td style='font-size:.95em'><div>2019/03/15</div></td>
                    <td style='font-size:.95em'><div class='c-grey-500'>No remarks</div></td>
                    <td style='font-size:.95em'><div class='c-grey-500'>No response</div></td>
                  
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection