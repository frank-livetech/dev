<script>
// Integration SCript Blade
    function initMap() {
        var name = $('.integration-title').text();
        var api_key = $("#google_api_key").val();
        var data = {
            api_key: api_key,
            name: name
        }
        if (api_key != '') {

            $.ajax({
                type: "POST",
                url: "{{asset('IntegrationsVerify')}}",
                data: data,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    // console.log(data);
                    Swal.fire({
                        position: 'top-end',
                        icon: data.success ? 'success' : 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2500
                    })
                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });

        }
    }

</script>
    
<script>

    // wordpress wizard

    $("#verifyBtn").click(function() {

        var name = $('.integration-title').text();
        var api_url = $("#api_url_field").val();
        var consumer_key = $("#consumer_key").val();
        var consumer_secret = $("#consumer_secret").val();
        let status = false;

        if(api_url == '') {
            $("#url_error").html("this field is required");
            status = false;
        }else{
            $("#url_error").html(" ");
            status = true;
        }
        if(consumer_key == '') {
            $("#key_error").html("this field is required");
            status = false;
        }else{
            $("#key_error").html(" ");
            status = true;
        }
        if(consumer_secret == '') {
            $("#secret_error").html("this field is required");
            status = false;
        }else{
            $("#secret_error").html(" ");
            status = true;
        }

        if(status == true) {

            var data = {
                api_url: api_url,
                consumer_key: consumer_key,
                consumer_secret: consumer_secret,
                name: name,
            }

            $.ajax({
                type: "POST",
                url: "{{asset('IntegrationsVerify')}}",
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function(data) {
                    $("#wrdprss_loader").show();
                },
                success: function(data) {
                    if (data.status_code == 200 && data.success == true) {

                        alertNotification('success', 'Success' ,data.message );
                        $("#submitBtn").removeAttr('disabled');                 
        
                    } else {
                        alertNotification('success', 'Success' ,data.message );
                    }
                },
                complete: function(data) {
                    $("#wrdprss_loader").hide();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    if (XMLHttpRequest.readyState == 4) {
                        alertNotification('error', 'Error' , "Verfication Failed Check your Credentials...!" );
                    }
                    else if (XMLHttpRequest.readyState == 0) {
                        alertNotification('error', 'Error' , "fail to connect, please check your connection" );
                    }
                    else {
                        alertNotification('error', 'Error' , errorThrown );
                    }
                }
            });

        }
    });

  function getWpCustomers(page) {

        var name = $('.integration-title').text();
        var api_url = $("#api_url_field").val();
        var consumer_key = $("#consumer_key").val();
        var consumer_secret = $("#consumer_secret").val();
        var integration_id = $("#wordpress_id").val();
        let status = true;

        if(!document.querySelector('#customCheckCustomers').checked && !document.querySelector('#customCheckOrders').checked && !document.querySelector('#customCheckProducts').checked && !document.querySelector('#customCheckSubscriptions').checked) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'No functions are selected for this action!',
                showConfirmButton: false,
                timer: swal_message_time
            })
            return false;
        }

        if(status == true) {

            var formdata = {
                integration_id:integration_id,
                name:name,
                api_url: api_url,
                consumer_key:consumer_key,
                consumer_secret:consumer_secret,
                page_index:page,
            }
        
            $.ajax({
                type: "POST",
                url: "{{asset('get_wp_customers')}}",
                data: formdata,
                beforeSend: function(data) {
                    let id = "wordpress_"+page;
                    $(".wp_loader").attr("id",id);
                    $('#'+id).show();
                    
                    let loadertext = "loader_text_" + page;
                    $(".loadertext").attr("id",loadertext);
                    if(document.querySelector('#customCheckCustomers').checked) $("#"+ loadertext).show().text("Fetching Customers Please wait.....");
                    
                },
                success: function(data) {
                    
                    if(data.status_code == 200 && data.success == true) {

                        if(data.is_finished == false) {
                            if(document.querySelector('#customCheckCustomers').checked) getWpCustomers(data.page_index);
                        }else{

                            let type = 'Wordpress';
                            let slug = 'customer-lookup';
                            let icon = 'fab fa-wordpress';
                            let title = 'WP Customers';
                            let desc = 'WP Customers Fetched';

                            sendNotification(type,slug,icon,title,desc);

                            if(document.querySelector('#customCheckOrders').checked) getWpORders(1);
                        }
                        
                    }else{

                        alertNotification('error', 'Error' ,data.message );
                    }
                
                },
                complete: function(data) {
                    
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                    let id = "wordpress_"+page;
                    $(".wp_loader").attr("id",id);
                    $('#'+id).hide();
                    
                    let loadertext = "loader_text_" + page;
                    $(".loadertext").attr("id",loadertext);
                    $("#"+ loadertext).hide().text(" ");
                    
                    if (textStatus == 'timeout') {
                        alertNotification('error', 'Error' , textStatus);
                    }
                    if (XMLHttpRequest.readyState == 4) {
                        alertNotification('error', 'Error' , readyState);
                    }
                    else if (XMLHttpRequest.readyState == 0) {
                        alertNotification('error', 'Error' , "fail to connect, please check your connection");

                    }
                    else {
                        alertNotification('error', 'Error' , errorThrown);
                    }
                }
            });

        }
    }


    function getWpORders(page) {

        var name = $('.integration-title').text();
        var api_url = $("#api_url_field").val();
        var consumer_key = $("#consumer_key").val();
        var consumer_secret = $("#consumer_secret").val();
        var integration_id = $("#wordpress_id").val();
        let status = true;

        if(status == true) {

            var formdata = {
                integration_id:integration_id,
                name:name,
                api_url: api_url,
                consumer_key:consumer_key,
                consumer_secret:consumer_secret,
                page_index : page,
            }

            $.ajax({
                type: "POST",
                url: "{{asset('get_wp_orders')}}",
                data: formdata,
                beforeSend: function(data) {

                    let id = "wordpress_"+page;
                    $(".wp_loader").attr("id",id);
                    $('#'+id).show();
                    
                    let loadertext = "loader_text_" + page;
                    $(".loadertext").attr("id",loadertext);
                    $("#"+ loadertext).show().text("Fetching Orders Please wait.....");
                },
                success: function(data) {
                    
                    if(data.status_code == 200 && data.success == true) {

                        if(data.is_finished == false) {
                            getWpORders(data.page_index);
                        }else{

                            alertNotification('success', 'Success' ,data.message );
                            
                            let type = 'Wordpress';
                            let slug = 'billing/home';
                            let icon = 'fab fa-wordpress';
                            let title = 'WP Orders';
                            let desc = 'WP Orders Fetched';

                            sendNotification(type,slug,icon,title,desc);

                            $("#wordPress").modal('hide');

                            let id = "wordpress_"+page;
                            $(".wp_loader").attr("id",id);
                            $('#'+id).hide();
                            
                            let loadertext = "loader_text_" + page;
                            $(".loadertext").attr("id",loadertext);
                            $("#"+ loadertext).hide().text(" ");

                        }
                    }else{

                        alertNotification('error', 'Error' ,data.message );
                    }
                
                },
                complete: function(data) {
                    if(data.is_finished == true) {
                        
                        $("#wordPress").modal('hide');
                        
                        let id = "wordpress_"+page;
                        $(".wp_loader").attr("id",id);
                        $('#'+id).hide();
                        
                        let loadertext = "loader_text_" + page;
                        $(".loadertext").attr("id",loadertext);
                        $("#"+ loadertext).hide().text(" ");
                        
                        
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    let id = "wordpress_"+page;
                    $(".wp_loader").attr("id",id);
                    $('#'+id).hide();
                    
                    let loadertext = "loader_text_" + page;
                    $(".loadertext").attr("id",loadertext);
                    $("#"+ loadertext).hide().text(" ");
                    
                    if (textStatus == 'timeout') {
                        alertNotification('error', 'Error' , textStatus);
                    }
                    if (XMLHttpRequest.readyState == 4) {
                        alertNotification('error', 'Error' , readyState);
                    }
                    else if (XMLHttpRequest.readyState == 0) {
                        alertNotification('error', 'Error' , "fail to connect, please check your connection");
                    }
                    else {
                        alertNotification('error', 'Error' , errorThrown);
                    }
                }
            });

        }
    }
    
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<script>
   $(document).ready(function() {

        $('.integrations-menu').on('click', function() {
            $('.integrations-menu').removeClass(['bg-dark', 'text-white']);
            $(this).addClass(['bg-dark', 'text-white']);
            $('.integrations-content').addClass('d-none')
            $('#' + $(this).attr('data-id')).removeClass('d-none');
        });
        // i add those line of code for toggel
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            let switchery = new Switchery(html, {
                size: 'small'
            });
        });

        $("#verify").on('click', function() {
            var name = $('.integration-title').text();
            console.log(name, "name");
            var continues = 'true';
            if (name == 'PayPal') {
                var client_id = $("#client_id").val();
                var secret_key = $("#secret_key").val();
                var enviornment = $("input:radio[name=enviornment]:checked").val();

                var data = {
                    client_id: client_id,
                    secret_key: secret_key,
                    name: name,
                    enviornment: enviornment

                }
                console.log(data);
            }
            if (name == 'NameCheap') {
                var api_key = $("#api_key").val();
                var username = $("#username").val();
                var ip = $("#ip").val();
                var enviornment = $("input:radio[name=enviornment]:checked").val();

                var data = {
                    api_key: api_key,
                    username: username,
                    ip: ip,
                    name: name,
                    enviornment: enviornment

                }
                console.log(data);
            }
            if (name == 'NMI Payment Gateway') {

                var tokenization_key = $("#tokenization_key").val();
                var security_key = $("#security_key").val();
                var data = {
                    tokenization_key: tokenization_key,
                    security_key: security_key,
                    name: name
                }
            }
            if (name == "Google Api's") {

                var api_key = $("#google_api_key").val();
                var data = {
                    api_key: api_key,
                    name: name
                }
                if (api_key != '') {
                    var script = "https://maps.googleapis.com/maps/api/js?key=" + api_key;

                    $.getScript(script)
                        .done(function(script, textStatus) {
                            console.log(textStatus);
                            if (typeof google === 'object' && typeof google.maps === 'object') {
                                console.log(google)
                                setTimeout(function() {
                                    if ($('#google_verification').val() == '1') {
                                        initMap();
                                    }
                                }, 8000);

                            }
                        })
                        .fail(function(jqxhr, settings, exception) {
                            // $( "div.log" ).text( "Triggered ajaxError handler." );
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Field to Verify!',
                                showConfirmButton: false,
                                timer: 2500
                            })
                            // return false;
                        });
                }
                return false;
            }
            //    return false;
            if (name == "Google Api's") {
                return false;
            }
            $.ajax({
                        type: "POST",
                        url: "{{asset('IntegrationsVerify')}}",
                        data: data,
                        dataType: 'json',
                        cache: false,
                        success: function(data) {
                            // console.log(data);
                            Swal.fire({
                                position: 'top-end',
                                icon: data.success ? 'success' : 'error',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 2500
                            })
                        },
                        failure: function(errMsg) {
                            console.log(errMsg);
                        }
                    });
        });
        });

            function convertJson(text) {
                let obj = [],
                    res = text.trim();
                res.split('\n').forEach((line) => {
                    let key = line.trim().split(':', 1)[0].toLowerCase().replace(' ', '_'),
                        value = line.trim().split(':').splice(1).join(':').trim().split(',');
                    obj[key] = value;
                })
                return obj;
            }

            $("#save-details").submit(function(event) {

                event.preventDefault();

                var formData = new FormData($(this)[0]);
                var action = $(this).attr('action');
                var method = $(this).attr('method');
                
                $.ajax({
                    type: method,
                    url: action,
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(data) {
                        $("#form_loader").show();
                    },
                    success: function(data) {
                        
                        if(data.status_code == 200 && data.success == true) {
                            alertNotification('success', 'Success' ,data.message );                    
                        }else{
                            alertNotification('error', 'Error' ,data.message );
                        }
                        
                    },
                    complete: function(data) {
                        $("#form_loader").hide();
                        $("#add-integration").modal('hide');
                    },
                    error: function(errMsg) {
                        console.log(errMsg);
                    }
                });

            });

            function showFolderModel(integration) {
                let content = ``;
                var details = JSON.parse(integration.details);
                console.log(details);
        
                if(integration.name == 'WordPress') {

                    if(details != null) {
                        $("#database_name").val(details.dbname != null ? details.dbname : '');
                        $("#db_username").val(details.username != null ? details.username : '');
                        $("#db_password").val(details.password != null ? details.password : '');
                        $("#db_hostname").val(details.hostname != null ? details.hostname : '');
                        $("#port").val(details.port != null ? details.port : '');
                        
                        $("#api_url_field").val(details.api_url != null ? details.api_url : '');
                        $("#consumer_key").val(details.consumer_key != null ? details.consumer_key : '');
                        $("#consumer_secret").val(details.consumer_secret != null ? details.consumer_secret : '');
                    }
                
                }
                
                switch (integration.slug) {
                    case 'bitdefender':
                        content = `
                        <div class="form-group">
                            <label for="companies_api">Companies Key</label>
                            <input type="text" class="form-control" id="companies_api" name="companies_api">
                        </div>
                        <div class="form-group">
                            <label for="licensing_api">Licensing Key</label>
                            <input type="text" class="form-control" id="licensing_api" name="licensing_api">
                        </div>
                        <div class="form-group">
                            <label for="package_api">Packages Key</label>
                            <input type="text" class="form-control" id="package_api" name="package_api">
                        </div>
                        <div class="form-group">
                            <label for="network_api">Network Key</label>
                            <input type="text" class="form-control" id="network_api" name="network_api">
                        </div>
                        <div class="form-group">
                            <label for="policies_api">Policies Key</label>
                            <input type="text" class="form-control" id="policies_api" name="policies_api">
                        </div>
                        <div class="form-group">
                            <label for="intergration_api">Intergrations Key</label>
                            <input type="text" class="form-control" id="intergration_api" name="intergration_api">
                        </div>
                        `;
                        break;
                    case 'namecheap':
                        if (details && details.hasOwnProperty('enviornment') ) {
                            var checkedlive = details.enviornment == 'true' ? 'checked' : '';
                            var checkedSand = details.enviornment == 'false' ? 'checked' : '';
                            var username=details.username;
                            var ip=details.ip;
                            var api_key = details.api_key;
                        } else {
                            var checkedlive = '';
                            var checkedSand = 'checked';
                            var username='';
                            var ip='';
                            var api_key ='';
                        }

                        content = `
                            <div class="form-group">
                                <label for="api_key">Api Key</label>
                                <input type="text" class="form-control" id="api_key" value="` + api_key + `" name="api_key">
                            </div>
                            <div class="form-group">
                                <label for="tokenization_key">User Name </label>
                                <input type="text" class="form-control" id="username" value="` + username + `" name="username">
                            </div>
                            <div class="form-group">
                                <label for="tokenization_key">Ip </label>
                                <input type="text" class="form-control" id="ip" value="` + ip + `" name="ip">
                            </div>
                            <div class="form-row mb-2 mt-2">
                                <div class="row ">
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="enviornment"  value="true" id="Live"  ` + checkedlive + `>
                                        <label class="form-check-label" for="Live"> Live </label>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="enviornment"  value="false" ` + checkedSand + ` id="SandBox">
                                        <label class="form-check-label" for="SandBox"> SandBox </label>
                                    </div>
                                </div>
                            </div>
                        `;
                        $("#api_url").hide();
                        break;
                    case 'splashtop':
                        content = `
                            <div class="form-group">
                                <label for="api_key">Api Key</label>
                                <input type="text" class="form-control" id="api_key" name="api_key">
                            </div>
                        `;
                        break;
                    case 'acronis':
                        content = `
                            <div class="form-group">
                                <label for="api_token">Api Token</label>
                                <input type="text" class="form-control" id="api_token" name="api_token">
                            </div>
                        `;
                        break;
                    case 'nmi-payment-gateway':
                        content = `
                            <div class="form-group">
                                <label for="security_key">Security Key</label>
                                <input type="text" class="form-control" id="security_key" value="` + details.security_key + `" name="security_key">
                            </div>
                            <div class="form-group">
                                <label for="tokenization_key">Tokenization Key</label>
                                <input type="text" class="form-control" id="tokenization_key" value="` + details.tokenization_key + `" name="tokenization_key">
                            </div>
                            
                        `;
                        $("#api_url").hide();
                        break;
                    case 'zelle-payment-service':
                        content = `
                            <div class="form-group">
                                <label for="partner_id">Partner Id</label>
                                <input type="text" class="form-control" id="partner_id" name="partner_id">
                            </div>
                        `;
                        break;
                    case 'paypal':
                        if (details.enviornment !== undefined) {
                            var checkedlive = details.enviornment == 'live' ? 'checked' : '';
                            var checkedSand = details.enviornment == 'sandbox' ? 'checked' : '';
                        } else {
                            var checkedlive = '';
                            var checkedSand = 'checked';
                        }

                        content = `
                            <div class="form-group">
                                <label for="client_id">Client Id</label>
                                <input type="text" class="form-control" id="client_id" value="` + details.client_id + `" name="client_id">
                            </div>
                            <div class="form-group">
                                <label for="secret_key">Secret Key</label>
                                <input type="text" class="form-control" id="secret_key" value="` + details.secret_key + `" name="secret_key">
                            </div>
                            <div class="form-row mb-2 mt-2">
                                <div class="row ">
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="enviornment"  value="live" id="Live"  ` + checkedlive + `>
                                        <label class="form-check-label" for="Live"> Live </label>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="enviornment"  value="sandbox" ` + checkedSand + ` id="SandBox">
                                        <label class="form-check-label" for="SandBox"> SandBox </label>
                                    </div>
                                </div>
                            </div>
                        `;
                        $("#api_url").hide();
                        break;
                    case 'the-ssl-store':
                        content = `
                            <div class="form-group">
                                <label for="authentication">Authentication</label>
                                <textarea class="form-control" name="authentication" id="authentication" rows="10"
                                placeholder='{\n "AuthRequest":{\n "PartnerCode": "String Content",\n "AuthToken":"String Content",\n "RepayToken":"String Content",\n "UserAgent":"String Content",\n "TokenId":"String Content",\n "TokenCode":"String Content",\n "IPAddress":"String Content",\n "IsUsedForTokenSystem": true,\n "Token":"String Content"\n},\n "HostName":"String Content"\n}'></textarea>
                            </div>
                        `;
                        break;
                    case 'code-guard':
                        content = `
                            <div class="form-group">
                                <label for="partner_api_key">Partner API Key</label>
                                <input type="text" class="form-control" id="partner_api_key" name="partner_api_key">
                            </div>
                            <div class="form-group">
                                <label for="api_user_access_key">API User access key</label>
                                <input type="text" class="form-control" id="api_user_access_key" name="api_user_access_key">
                            </div>
                    `
                        break;
                    case 'google-api':
                    var api_keys='';
                        if(!$.isEmptyObject(details)){
                            if( details.hasOwnProperty('api_key')){
                                api_keys=details.api_key ;
                            }
                        }
                        content = `
                            <div class="form-group">
                                <label for="api_key">API Key</label>
                                <input type="text" class="form-control" id="google_api_key" value="` + api_keys + `" name="api_key">
                            
                            </div>
                    `;
                        $("#api_url").hide();
                        break;
                    case 'cytracom':
                        content = `
                            <div class="form-group">
                                <label for="api_token">API Token</label>
                                <input type="text" class="form-control" id="api_token" name="api_token">
                            </div>
                    `
                        break;
                    case 'amazon':
                        content = `
                            <div class="form-group">
                                <label for="api_token">API Token</label>
                                <input type="text" class="form-control" id="api_token" name="api_token">
                            </div>
                    `
                        break;
                    case 'facebook':
                        content = `
                            <div class="form-group">
                                <label for="access_token">Access Token</label>
                                <input type="text" class="form-control" id="access_token" name="access_token">
                            </div>
                    `
                        break;
                    case 'wordpress':
                        content = `
                            <div class="form-group">
                            </div>
                    `
                        break;
                    case 'microsoft':
                        content = `
                            <div class="form-group">
                                <label for="authorization_token">Authorization Token</label>
                                <input type="text" class="form-control" id="authorization_token" name="authorization_token">
                            </div>
                    `
                        break;
                    case 'coinbase':
                        content = `
                            <div class="form-group">
                                <label for="api_key">Api Key</label>
                                <input type="text" class="form-control" id="api_key_coinbase" name="api_key">
                            </div>
                    `
                        break;
                    case 'whatsapp':
                        content = `
                            <div class="form-group">
                                <label for="api_key">Authentication Tokens</label>
                                <input type="text" class="form-control" id="api_key1" name="api_key">
                            </div>
                    `
                        break;
                }
                $('#conent-body').html(content);
                $("#save_api").trigger("reset");
                $('.integration-logo').attr({
                    'src': 'assets/images/' + integration.image,
                    'alt': integration.name
                });
                $('.integration-title').text(integration.name);
                $('#integration_id').val(integration.id);

                if(integration.name == 'WordPress') {
                    $("#wordPress").modal('show');
                    $('#wordpress_id').val(integration.id);
                }else{
                    $('#add-integration').modal('show');
                } 
                
            }

            function integrationStatus(el) {
                let st = '0';
                if ($(el).prop('checked')) {
                    st = '1';
                }

                $.ajax({
                    type: "POST",
                    url: "{{asset('integrationsStatus')}}",
                    data: {
                        id: $(el).data('id'),
                        status: st
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data) {

                        if (data.success != 'success') {
                            // $(el).atte('click');
                            $(el).prop('checked', false);


                        }
                        Swal.fire({
                            position: 'top-end',
                            icon: data.success ? 'success' : 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500
                        })
                    },
                    failure: function(errMsg) {
                        console.log(errMsg);
                    }
                });
            }

        function gm_authFailure(e) { 
            Swal.fire({
                position: 'top-end',
                icon:  'error',
                title:'Field to Verify!',
                showConfirmButton: false,
                timer: 2500
            })
            $('#google_verification').val(0).trigger('change');
        };


  
</script>