@extends('admin.default')

@section('content')

{{-- <div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>

    <div class="masonry-item w-100">
        <employee-table></employee-table>
   </div>
</div> --}}
{{-- @include('admin.dashboard.include.employee_form_modal'); --}}
{{-- <rta-schedule-page></rta-schedule-page> --}}

<div class="fluid-container">
    <div class="row">
        <div class="col-9 p-10">
            {{-- // Date picker --}}
            <div class="w-100 pY-10">
                <div class="row">
                    <div class="col-md-3">
                        <date-time-picker
                        no-label
                        range
                        no-shortcuts
                        only-date
                        format="YYYY-MM-DD"
                        formatted="ddd D MMM YYYY"
                        color="red"
                        label="Week"
                        >
                        <button class="btn bgc-red-500 c-white form-control"></button>
                        </date-time-picker>
                    </div>
                </div>
            </div>
            {{-- // Slot info --}}
            Avalable slots
            <div class="w-100 bgc-white bd pY-10">
                <div class="row">
                    <div class="col">
                        <div class="w-100 text-center fsz-xs fw-600">
                            MON
                        </div>
                        <div class="w-100 text-center">
                            2019-05-10
                        </div>
                        <div class="w-100 text-center fsz-lg fw-600">
                            10
                        </div>
                    </div>
                    <div class="col">
                        <div class="w-100 text-center fsz-xs fw-600">
                            TUE
                        </div>
                        <div class="w-100 text-center">
                            2019-05-11
                        </div>
                        <div class="w-100 text-center fsz-lg fw-600">
                            10
                        </div>
                    </div>
                    <div class="col">
                        <div class="w-100 text-center fsz-xs fw-600">
                            WED
                        </div>
                        <div class="w-100 text-center">
                            2019-05-12
                        </div>
                        <div class="w-100 text-center fsz-lg fw-600">
                            10
                        </div>
                    </div>
                    <div class="col">
                        <div class="w-100 text-center fsz-xs fw-600">
                            THU
                        </div>
                        <div class="w-100 text-center">
                            2019-05-13
                        </div>
                        <div class="w-100 text-center fsz-lg fw-600">
                            10
                        </div>
                    </div>
                    <div class="col">
                        <div class="w-100 text-center fsz-xs fw-600">
                            FRI
                        </div>
                        <div class="w-100 text-center">
                            2019-05-14
                        </div>
                        <div class="w-100 text-center fsz-lg fw-600">
                            10
                        </div>
                    </div>
                    <div class="col">
                        <div class="w-100 text-center fsz-xs fw-600">
                            SAT
                        </div>
                        <div class="w-100 text-center">
                            2019-05-15
                        </div>
                        <div class="w-100 text-center fsz-lg fw-600">
                            10
                        </div>
                    </div>
                    <div class="col">
                        <div class="w-100 text-center fsz-xs fw-600">
                            SUN
                        </div>
                        <div class="w-100 text-center">
                            2019-05-16
                        </div>
                        <div class="w-100 text-center fsz-lg fw-600">
                            10
                        </div>
                    </div>
                </div>
            </div>
            {{-- //filters --}}
            <div class="w-100 mT-20 pY-10">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Agent Search...">
                    </div>
                    <div class="col-md-6 offset-md-3">
                        <div class="input-group">
                        <select name id class="select form-control">
                            <option value="1">Operations Manager</option>
                            <option value="2">Team Leader</option>
                        </select>
                        <select name id class="select form-control">
                            <option value="1">Some OM</option>
                            <option value="2">Some TL</option>
                        </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- //requests --}}

            <div class="w-100 p-10 mT-20">Requests</div>
            <div class="w-100  pY-10">
                <div class="row">
                    <div class="col-md-6 mB-20">
                        <div class="bgc-white bd">
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col">
                                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                                        </div>
                                        <div class="h-100" style="float:left;display:flex">
                                            <div class="pL-5 align-self-center">
                                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                                <div class="" style="font-size:.8em">Vacation Leave</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>M</td>
                                                    <td>T</td>
                                                    <td>W</td>
                                                    <td>T</td>
                                                    <td>F</td>
                                                    <td>S</td>
                                                    <td>S</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>11</td>
                                                    <td>12</td>
                                                    <td>13</td>
                                                    <td><div class="badge p-5 bgc-orange-200">14</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">15</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">16</div></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 p-10"><div class="btn btn-light form-control">Approve</div></div>
                        </div>
                    </div>
                    <div class="col-md-6 mB-20">
                        <div class="bgc-white bd">
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col">
                                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                                        </div>
                                        <div class="h-100" style="float:left;display:flex">
                                            <div class="pL-5 align-self-center">
                                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                                <div class="" style="font-size:.8em">Vacation Leave</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>M</td>
                                                    <td>T</td>
                                                    <td>W</td>
                                                    <td>T</td>
                                                    <td>F</td>
                                                    <td>S</td>
                                                    <td>S</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>11</td>
                                                    <td>12</td>
                                                    <td>13</td>
                                                    <td><div class="badge p-5 bgc-orange-200">14</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">15</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">16</div></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 p-10"><div class="btn btn-light form-control">Approve</div></div>
                        </div>
                    </div>
                    <div class="col-md-6 mB-20">
                        <div class="bgc-white bd">
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col">
                                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                                        </div>
                                        <div class="h-100" style="float:left;display:flex">
                                            <div class="pL-5 align-self-center">
                                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                                <div class="" style="font-size:.8em">Vacation Leave</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>M</td>
                                                    <td>T</td>
                                                    <td>W</td>
                                                    <td>T</td>
                                                    <td>F</td>
                                                    <td>S</td>
                                                    <td>S</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>11</td>
                                                    <td>12</td>
                                                    <td>13</td>
                                                    <td><div class="badge p-5 bgc-orange-200">14</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">15</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">16</div></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 p-10"><div class="btn btn-light form-control">Approve</div></div>
                        </div>
                    </div>
                    <div class="col-md-6 mB-20">
                        <div class="bgc-white bd">
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col">
                                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                                        </div>
                                        <div class="h-100" style="float:left;display:flex">
                                            <div class="pL-5 align-self-center">
                                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                                <div class="" style="font-size:.8em">Vacation Leave</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>M</td>
                                                    <td>T</td>
                                                    <td>W</td>
                                                    <td>T</td>
                                                    <td>F</td>
                                                    <td>S</td>
                                                    <td>S</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>11</td>
                                                    <td>12</td>
                                                    <td>13</td>
                                                    <td><div class="badge p-5 bgc-orange-200">14</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">15</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">16</div></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 p-10"><div class="btn btn-light form-control">Approve</div></div>
                        </div>
                    </div>
                    <div class="col-md-6 mB-20">
                        <div class="bgc-white bd">
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col">
                                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                                        </div>
                                        <div class="h-100" style="float:left;display:flex">
                                            <div class="pL-5 align-self-center">
                                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                                <div class="" style="font-size:.8em">Vacation Leave</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 bdB p-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>M</td>
                                                    <td>T</td>
                                                    <td>W</td>
                                                    <td>T</td>
                                                    <td>F</td>
                                                    <td>S</td>
                                                    <td>S</td>
                                                </tr>
                                                <tr>
                                                    <td>10</td>
                                                    <td>11</td>
                                                    <td>12</td>
                                                    <td>13</td>
                                                    <td><div class="badge p-5 bgc-orange-200">14</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">15</div></td>
                                                    <td><div class="badge p-5 bgc-orange-200">16</div></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 p-10"><div class="btn btn-light form-control">Approve</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- approved list --}}
        <div class="col-3 p-10">
            Approved List
            <div class="w-100 bgc-white bd p-10">
                <div class="row">
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mB-3 pY-10">
                        <div class="bdrs-50p w-2r h-2r bgc-grey-200" style="display:flex;float:left">
                            <span class="align-self-center c-grey-600 w-100 text-center fsz-xs fw-600">XS</span>
                        </div>
                        <div class="h-100" style="float:left;display:flex">
                            <div class="pL-5 align-self-center">
                                <div class="fw-600" style="font-size:.8em">Emmanuel James Eng Lajom</div>
                                <div class="" style="font-size:.8em">Vacation Leave</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection