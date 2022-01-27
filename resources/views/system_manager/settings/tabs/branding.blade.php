<div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">Branding Settings</h4>

                            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                <li class="nav-item">
                                    <a href="#generalTech" data-bs-toggle="tab" aria-expanded="false"
                                        class="nav-link rounded-0 active">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">General </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#visualSetting" data-bs-toggle="tab" aria-expanded="false"
                                        class="nav-link rounded-0 ">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Visual Settings </span>
                                    </a>
                                </li>
                            </ul>
                            
                        
                            <div class="tab-content">
                                <div class="tab-pane active" id="generalTech">
                                    <form method="post" action="{{asset('/save-brand-settings')}}" enctype="multipart/form-data"
                                        id="brand_settings">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="site_title">Site Name</label>
                                                    @if($brand_settings != null )
                                                        <input class="form-control mb-1" type="text" name="site_title" id="site_title"
                                                        placeholder="" value="{{$brand_settings->site_title}}">
                                                    @else
                                                        <input class="form-control mb-1" type="text" name="site_title" id="site_title"
                                                        placeholder="">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="site_logo">Logo Title</label>
                                                    @if($brand_settings != null )
                                                    <input class="form-control mb-1" type="text" name="site_logo_title" id="site_logo_title"
                                                        placeholder="" value="{{$brand_settings->site_logo_title}}">
                                                    @else 
                                                    <input class="form-control mb-1" type="text" name="site_logo_title" id="site_logo_title"
                                                        placeholder="">
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="site_logo">Site Version</label>
                                                    @if($brand_settings != null )
                                                        <input class="form-control mb-1" type="text" name="site_version" id="site_version" placeholder="" value="{{$brand_settings->site_version}}" readonly>
                                                    @else
                                                        <input class="form-control mb-1" type="text" name="site_version" id="site_version" placeholder="">
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="site_domain">Domain for Fake Email Generator</label>
                                                    @if($brand_settings != null )
                                                        <input class="form-control mb-1" type="text" name="site_domain" id="site_domain" placeholder="domain.com" value="{{$brand_settings->site_domain}}">
                                                    @else
                                                        <input class="form-control mb-1" type="text" name="site_domain" id="site_domain" placeholder="domain.com">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="departmrnt">Logo</label>
                                                    <div class="input-group mb-3">

                                                        <div class="custom-file">
                                                            <input type="file" class="form-control mb-1" id="site_logo"
                                                                name="site_logo">
                                                                <div class="d-flex" style="top: 45px; position: absolute;">
                                                                    <small id="name13" class="badge badge-light-primary">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                                </div>
                                                            <!-- <label class="custom-file-label" for="site_logo">Choose file</label> -->
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="departmrnt">Favicon</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="form-control mb-1" id="site_favicon"
                                                                name="site_favicon">
                                                                <div class="d-flex" style="top: 45px; position: absolute;">
                                                                    <small id="name13" class="badge badge-light-primary">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                                </div>
                                                            <!-- <label class="custom-file-label" for="site_favicon">Choose file</label> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                
                                                @if($brand_settings != null && $brand_settings->site_logo != null)
                                                    @if(file_exists( public_path() .'/'. $file_path .  $brand_settings->site_logo ))
                                                        <img id="site_logo_preview" name="site_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . $brand_settings->site_logo)}}" />
                                                    @else
                                                    <img id="site_logo_preview" name="site_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . 'default_imgs/site_logo.png')}}" />
                                                    @endif
                                                @else
                                                    <img id="site_logo_preview" name="site_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . 'default_imgs/site_logo.png')}}" />
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                @if($brand_settings != null && $brand_settings->site_logo != null)
                                                    @if(file_exists( public_path() .'/'. $file_path .  $brand_settings->site_favicon ))
                                                        <img id="site_favicon_preview" name="site_favicon_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . $brand_settings->site_favicon)}}" />
                                                    @else
                                                    <img id="site_favicon_preview" name="site_favicon_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . 'default_imgs/site_logo.png')}}" />
                                                    @endif
                                                @else
                                                    <img id="site_favicon_preview" name="site_favicon_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . 'default_imgs/site_logo.png')}}" />
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="departmrnt">Login Logo</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="form-control mb-1" id="login_logo"
                                                                name="login_logo">
                                                                <div class="d-flex" style="top: 45px; position: absolute;">
                                                                    <small id="name13" class="badge badge-light-primary">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                                </div>
                                                            <!-- <label class="custom-file-label" for="login_logo">Choose file</label> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="departmrnt">Customer Default Logo</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="form-control mb-1" id="customer_logo"
                                                                name="customer_logo">
                                                                <div class="d-flex" style="top: 45px; position: absolute;">
                                                                    <small id="name13" class="badge badge-light-primary">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                                </div>
                                                            <!-- <label class="custom-file-label" for="customer_logo">Choose file</label> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mt-2">
                                            
                                                @if($brand_settings != null && $brand_settings->login_logo != null)
                                                    @if(file_exists( public_path() .'/'. $file_path . $brand_settings->login_logo ))
                                                        <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset( $file_path . $brand_settings->login_logo)}}" />
                                                    @else
                                                    <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset('default_imgs/login_logo.png')}}" />
                                                    @endif
                                                @else
                                                    <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset('default_imgs/login_logo.png')}}" />
                                                @endif                                        
                                            </div>

                                            <!-- customer -->
                                            <div class="col-md-6 mt-2">
                                                @if($brand_settings != null && $brand_settings->customer_logo != null)
                                                    @if(file_exists( public_path().'/'. $file_path . $brand_settings->customer_logo ))
                                                        <img id="customer_logo_preview" name="customer_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . $brand_settings->customer_logo)}}" />
                                                    @else
                                                    <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset('default_imgs/customer.png')}}" />
                                                    @endif
                                                @else
                                                <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset('default_imgs/customer.png')}}" />
                                                @endif                                     
                                            </div>
                                        </div>


                                        <!-- company & users -->
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="departmrnt">Company Default Logo</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="form-control mb-1" id="company_logo"
                                                                name="company_logo">
                                                                <div class="d-flex" style="top: 45px; position: absolute;">
                                                                    <small id="name13" class="badge badge-light-primary">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                                </div>
                                                            <!-- <label class="custom-file-label" for="company_logo">Choose file</label> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1 ">
                                                    <label for="departmrnt">Staff Default Logo</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="form-control mb-1" id="user_logo"
                                                                name="user_logo">
                                                                <div class="d-flex" style="top: 45px; position: absolute;">
                                                                    <small id="name13" class="badge badge-light-primary">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                                </div>
                                                            <!-- <label class="custom-file-label" for="user_logo">Choose file</label> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mt-2">

                                                @if($brand_settings != null && $brand_settings->company_logo != null)
                                                    @if(file_exists( public_path().'/'. $file_path .  $brand_settings->company_logo ))
                                                        <img id="company_logo_preview" name="company_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . $brand_settings->company_logo)}}" />
                                                    @else
                                                        <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                            height="60" src="{{asset('default_imgs/company.png')}}" />
                                                    @endif
                                                @else
                                                    <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                            height="60" src="{{asset('default_imgs/company.png')}}" />
                                                @endif 
                                                
                                            </div>

                                            <!-- customer -->
                                            <div class="col-md-6 mt-2">

                                                @if($brand_settings != null && $brand_settings->user_logo != null)
                                                    @if(file_exists( public_path().'/'. $file_path .  $brand_settings->user_logo ))
                                                        <img id="user_logo_preview" name="user_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset($file_path . $brand_settings->user_logo)}}" />
                                                    @else
                                                        <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                            height="60" src="{{asset('default_imgs/logo.png')}}" />
                                                    @endif
                                                @else
                                                    <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                                height="60" src="{{asset('default_imgs/logo.png')}}" />
                                                @endif 
                                                
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="form-group my-1 ">
                                                    <label for="site_footer">Footer Copyright</label>
                                                    @if($brand_settings != null)
                                                    <textarea class="form-control mb-1" rows="3" id="site_footer"
                                                        name="site_footer">{{$brand_settings->site_footer}}</textarea>
                                                    @else
                                                    <textarea class="form-control mb-1" rows="3" id="site_footer"
                                                        name="site_footer"></textarea>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success mr-auto" style="float:right;"><i
                                                class="fas fa-check"></i>&nbsp;Save</button>
                                    </form>
                                </div>
                                <div class="tab-pane show" id="visualSetting">
                                    <form class="lightModeForm" method="post" >
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <h3 >Light Mode</h3>
                                                <hr>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <input type="hidden" value="Light" name="mode">
                                                    <label for="main_sys_back" class="col-sm-12">Main System Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="main_sys_back" id="main_sys_back" class="form-control demo mb-1" value="#f2f7f8">                                                        
                                                    </div>
                                                    <!-- <select name="main_sys_back" id="main_sys_back" class="form-control col-sm-6 bg-info" style="color:white;">
                                                        <option > Select </option>
                                                        <option value="bg-danger" class="bg-danger"> Danger </option>
                                                        <option value="bg-success" class="bg-success"> Success </option>
                                                        <option value="bg-primary" class="bg-primary"> Primary </option>
                                                        <option value="bg-info" class="bg-info"> Info </option>
                                                        <option value="bg-warning" class="bg-warning"> Warning </option>
                                                        <option value="bg-secondary" class="bg-secondary"> Secondary </option>
                                                    </select> -->
                                                    <!-- <label class="col-sm-3 pt-2 colorName" >#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Header Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="head_back" id="head_back" class="form-control demo mb-1" value="#009efb">                                                        
                                                    </div>

                                                
                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Card Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="card_back" id="card_back" class="form-control demo mb-1" value="#fff">                                                        
                                                    </div>
                                                
                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Table Header Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="table_head_back" id="table_head_back" class="form-control demo mb-1" value="#009efb">                                                        
                                                    </div>
                                                
                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Table Rows</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="table_row" id="table_row" class="form-control demo mb-1" value="#fff">                                                        
                                                    </div>
                                                
                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Main Font Color</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="main_font" id="main_font" class="form-control demo mb-1" value="#54667a">                                                        
                                                    </div>
                                                
                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Bread Crumb Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="bread_crum_back" id="bread_crum_back" class="form-control demo mb-1" value="transparent">                                                        
                                                    </div>
                                                    
                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Border Thickness</label>
                                                    <div class="col-md-12">
                                                        <input type="number" class="form-control mb-1 " id="border_thick" name="border_thick" value="2">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Card Shadow</label>
                                                    <div class="col-md-12">
                                                        <input type="number" class="form-control mb-1 " id="card_shadow" name="card_shadow" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-end">
                                                <button class="btn btn-success" type="submit"> 
                                                    Save
                                                </button>
                                            </div>
                                        </div>     
                                    </form>    
                                        
                                    <form method="post" class="lightModeForm" id="darkModeForm">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <h3 >Dark Mode</h3>
                                                <hr>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <input type="hidden" value="dark" name="mode">
                                                    <label for="" class="col-sm-12">Main System Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="drk_main_sys_back" id="drk_main_sys_back" class="form-control demo mb-1" value="#323743 ">                                                        
                                                    </div>

                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Header Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="drk_header_back" id="drk_header_back" class="form-control demo mb-1" value="#323743">                                                        
                                                    </div>

                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Card Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="drk_card_back" id="drk_card_back" class="form-control demo mb-1" value="#252629">                                                        
                                                    </div>

                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Table Header Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="drk_table_header" id="drk_table_header" class="form-control demo mb-1" value="#1E3E53">                                                        
                                                    </div>

                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Table Rows</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="drk_table_row" id="drk_table_row" class="form-control demo mb-1" value="#ffff">                                                        
                                                    </div>

                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Main Font Color</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="drk_main_font" id="drk_main_font" class="form-control demo mb-1" value="#d2dae0 ">                                                        
                                                    </div>

                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Border Thickness</label>
                                                    <div class="col-md-12">
                                                        <input type="number" class="form-control" id="drk_border_thick" name="drk_border_thick" value="2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Card Shadow</label>
                                                    <div class="col-md-12">
                                                        <input type="number" class="form-control" id="drk_card_shadow" name="drk_card_shadow" value="0">                                                        
                                                    </div>
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group my-1  row">
                                                    <label for="" class="col-sm-12">Bread Crumb Background</label>
                                                    <div class="col-md-12">
                                                    <input type="text" name="drk_bread_crum" id="drk_bread_crum" class="form-control demo mb-1" value="transparent">                                                        
                                                    </div>

                                                    <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-end">
                                                <button class="btn btn-success" type="submit"> 
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                        <form method="post" class="lightModeForm">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <h3 >Buttons</h3>
                                                    <hr>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <input type="hidden" value="button" name="mode">
                                                        <label for="" class="col-sm-12">Add Button Background</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="add_btn_back" id="add_btn_back" class="form-control demo" value="#39c449">                                                            
                                                        </div>
                                                    
                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">Font Color</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="add_font_color" id="add_font_color" class="form-control demo" value="#fff">                                                            
                                                        </div>
                                                    
                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">Delete Button Background</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="dlt_btn_back" id="dlt_btn_back" class="form-control demo" value="#f62d51">                                                            
                                                        </div>
                                                    
                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">Font Color</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="dlt_font_clr" id="dlt_font_clr" class="form-control demo" value="#fff">                                                            
                                                        </div>
                                                    
                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">New Button Background</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="new_btn_back" id="new_btn_back" class="form-control demo" value="#39c449">                                                            
                                                        </div>
                                                    
                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">Font Color</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="new_font_clr" id="new_font_clr" class="form-control demo" value="#fff">                                                            
                                                        </div>
                                                    
                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">Register Button Background</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="reg_btn_back" id="reg_btn_back" class="form-control demo" value="#39c449">                                                            
                                                        </div>

                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">Font Color</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="reg_font_clr" id="reg_font_clr" class="form-control demo" value="#fff">                                                            
                                                        </div>
                                                    
                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">Login Button Background</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="login_btn_btn" id="login_btn_btn" class="form-control demo" value="#7460ee">                                                            
                                                        </div>

                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group my-1  row">
                                                        <label for="" class="col-sm-12">Font Color</label>
                                                        <div class="col-md-12">
                                                        <input type="text" name="login_font_clr" id="login_font_clr" class="form-control demo" value="#fff">                                                            
                                                        </div>
                                                    
                                                        <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-end mt-2">
                                                    <button class="btn btn-success" type="submit"> 
                                                        Save
                                                    </button>
                                                </div>
                                            
                                            </div>

                                        </form>

                                    
                                </div>
                                <div class="tab-pane" id="settings1">

                                </div>
                            </div>

                        </div>
                    </div>