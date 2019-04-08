@extends('admin.default')

@section('content')


  <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
      <div class="row gap-20">
        <div class="col">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <h5>Work Reports</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
       <div class="row gap-20">
        <div class="col">
          <div class="layers bd bgc-white p-20">
            <div class="layer w-100">
              <div class="peers ai-sb fxw-nw">
                <div class="peer peer-greed">
                  <h5>Today</h5>
                </div>
                <div class="peer">
                  <div class="container">
                      <div class="row">
                          <div class="col text-center">
                            <h6><small>Agents</small></h6>
                            <h2>12222</h2>
                          </div>
                          <div class="col text-center">
                            <h6><small>Unapproved</small></h6>
                            <h2>72222</h2>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row w-100">
                <div class="col">
                  <div class="form-inline">
                        <input type='text' placeholder="Pick date range..." class='form-control m-2'>
                        <div class="btn-group" role="group">
                            <button
                                type="button"
                                class="fsz-sm bd btn primary bdrs-2 mR-3 cur-p"
                            >All
                            </button>
                            <button
                                type="button"
                                class="fsz-sm bd btn primary bdrs-2 mR-3 cur-p"
                            
                            disabled  >Today
                            </button>
                        </div>
                  </div>
                </div>
                <div class="col  text-right">
                    <div class="btn-group" role="group">
                  <button
                    type="button"
                    class="btn btn-secondary bdrs-2 mR-3 cur-p  form-control"
                  >Approved
                  </button>
                  <button
                    type="button"
                    class="btn btn-secondary bdrs-2 mR-3 cur-p  form-control"
                  
                  disabled  >Unapproved
                  </button>
                </div>
                </div>
            </div>
              <div class="layer w-100 p-20 mY-20 bd bgc-grey-200">
                <div class="peer mR-10">
                  <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                    <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                    <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                    <button class="btn btn-success" disabled>Approve</button>
                  </div>
                </div>
              </div>
              
            <div class="layer w-100">
                <div class="layer w-100 fxg-1 pos-r" style="overflow-y:auto;height:100vh">
                    <div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Emmanuel James Lajom</h6>
                                        <span><small>DEVELOPER</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Avalo Kites Vara Padma Pani</h6>
                                        <span><small>Representative - Order Placer</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Emmanuel James Lajom</h6>
                                        <span><small>DEVELOPER</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Avalo Kites Vara Padma Pani</h6>
                                        <span><small>Representative - Order Placer</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Emmanuel James Lajom</h6>
                                        <span><small>DEVELOPER</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Avalo Kites Vara Padma Pani</h6>
                                        <span><small>Representative - Order Placer</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Emmanuel James Lajom</h6>
                                        <span><small>DEVELOPER</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Avalo Kites Vara Padma Pani</h6>
                                        <span><small>Representative - Order Placer</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Emmanuel James Lajom</h6>
                                        <span><small>DEVELOPER</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Avalo Kites Vara Padma Pani</h6>
                                        <span><small>Representative - Order Placer</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Emmanuel James Lajom</h6>
                                        <span><small>DEVELOPER</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email-list-item peers fxw-nw p-20 bdB bgcH-grey-100 cur-p">
                        <div class="peer mR-10">
                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                            <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                            <label for="inputCall1" class="peers peer-greed js-sb ai-c"></label>
                        </div>
                        </div>
                        <div class="peer mR-10">
                        <div id="workreport-image-display" class="workreport-image-cover bdrs-50p" style="background-image:url(/images/nobody.jpg)"></div>
                        </div>
                        <div class="peer peer-greed">
                            
                            <div class="container">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Avalo Kites Vara Padma Pani</h6>
                                        <span><small>Representative - Order Placer</small></span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>REG</small></h6>
                                        <span>4.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>OT</small></h6>
                                        <span>3.5</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>BREAK</small></h6>
                                        <span>1.0</span>
                                    </div>
                                    <div class="col text-center">
                                        <h6><small>STATUS</small></h6>
                                        <span class="badge badge-danger badge-pill">Unapproved</span>
                                    </div>
                                    
                                    <div class="col text-center">
                                        <button class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection