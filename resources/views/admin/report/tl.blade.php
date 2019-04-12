@extends('admin.default')

@section('content')


  <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
      <div class="row gap-20">
        <div class="col">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100">
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
                    <work-log></work-log>
                  </div>
              </div>
              
          </div>
        </div>
      </div>

      

      
    </div>
  </div>
@endsection