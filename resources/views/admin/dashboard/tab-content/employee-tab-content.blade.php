<div class="pX-40 pY-30">
    <h4 class="c-grey-900 mB-20">Employee list</h4> 

    <div class="form-group reposition">
    <div class="btn-group mR-2" role="group" aria-label="First group">
        <div class="btn-group">
            <button type="button cur-p ti-arrow-left" class="btn cur-p btn-primary" id="prevProfile" disabled>← Prev</button>
        </div>

        <div class="btn-group">
            @if(isAdminHRM())
            <button class="btn cur-p btn-primary" id="showAll"><span class="ti-menu"></span></button>
            @endif
            <button class="btn cur-p btn-primary" id="showChild"><span class="ti-menu-alt"></span></button>
            <button class="btn cur-p btn-primary" id="showTerminated"><span class="ti-na"></span></button>
        </div>

        @if(isAdminHR())
        <div class="btn-group">
            <button type="input" class="btn cur-p btn-info excel-action-button"  id ="import" data-action="import"><span class="ti-upload"></span></button>
            <button type="input" class="btn cur-p btn-info excel-action-button"  id ="export" data-action="export"><span class="ti-download"></span></button>
        </div>

        <button type="submit" class="btn cur-p btn-secondary form-action-button" id="addflag" data-action="add" data-url="/employee"><span class="ti-pencil-alt"></span></button>
        @endif
    </div>
    </div>

    <table id="employee" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Employee No.</th>
                <th>Picture</th>
                <th>Name</th>
                <th>Position</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Contact No.</th>
                <th>Address</th>
                <th>Status</th>
                <th>Action</th> 
            </tr>
        </thead>
    </table>
</div>