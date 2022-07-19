@php
$file_path = Session::get('is_live') == 1 ? 'public/' : '/';
$path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
@endphp
<div class="card card-height">
    <div class="card-body">

        <h4 class="card-title mb-3">Default Company</h4>

        <div class="tab-content">

            <form id="update_company" action="{{url('/default_company_profile')}}" onsubmit="return false">
                <input type="hidden" name="company_id" id="company_id" >
                <div class="form-row">
                    <div class="col-md-12 form-group">
                        <label>Company Name</label>
                        <input type="text" id="name" name="name" placeholder="Johnathan Doe"
                            value="" class="form-control">
                        <span class="text-danger" id="err2"></span>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-6 form-group">
                        <label>Owner First Name :</label>
                        <input type="text" id="poc_first_name" name="poc_first_name"
                            value="" class="form-control">
                        <span class="text-danger" id="err"></span>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Owner Last Name :</label>
                        <input type="text" id="poc_last_name" name="poc_last_name"
                            value="" class="form-control">
                        <span class="text-danger" id="err1"></span>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-6 form-group">
                        <label>Phone No</label>
                        <div class="d-flex">
                            <input type="tel" class="tel form-control" name="phone" id="phone" value=" " placeholder="" autofocus>
                        </div>
                        <small class="text-secondary">NOTE: Include country code before number e.g 1 for US</small>

                    </div>
                    <div class="col-md-6 form-group">
                        <label for="domain">Domain</label>
                        <input type="text" id="domain" class="form-control" name="domain"
                            value="">
                        {{-- <span class="text-danger" id="err3"></span> --}}
                    </div>
                </div>


                <div class="col-md-12 mt-1">
                    <input type="submit" value="Save"
                        class="btn btn-success float-btn">
                </div>

            </form>
        </div>
    </div>
</div>

<!--Ticket Modals -->

