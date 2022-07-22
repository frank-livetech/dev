<script>
    // var bookmarkInput = $('.bookmark-wrapper .bookmark-input'),
    //     searchInputInputfield = $('#helpDeskSearch .search-input input'),
    //     bookmarkSearchList = $('.bookmark-input .search-list'),
    //     searchInput = $('#helpDeskSearch .search-input'),
    //     searchList = $('#helpDeskSearch .search-input .search-list'),
    //     appContent = $('.app-content'),
    //     searchInput = $('.search-input-close').closest('.search-input'),
    //     ticketsUrl = "{{ url('search-ticket') }}";

    //     $(window).on('load', function() {

    //         $('.content-overlay').on('click', function() {
    //             $('.search-list').removeClass('show');
    //             if (searchInput.hasClass('open')) {
    //                 searchInput.removeClass('open');
    //                 searchInputInputfield.val('');
    //                 searchInputInputfield.blur();
    //                 searchList.removeClass('show');
    //             }

    //             $('.app-content').removeClass('show-overlay');
    //             $('.bookmark-wrapper .bookmark-input').removeClass('show');
    //         });

    //     });

    //     searchInputInputfield.on('keyup', function(e) {
    //         $(this).closest('.search-list').addClass('show');
    //         if (e.keyCode !== 38 && e.keyCode !== 40 && e.keyCode !== 13) {
    //             if (e.keyCode == 27) {
    //                 appContent.removeClass('show-overlay');
    //                 bookmarkInput.find('input').val('');
    //                 bookmarkInput.find('input').blur();
    //                 searchInputInputfield.val('');
    //                 searchInputInputfield.blur();
    //                 searchInput.removeClass('open');
    //                 if (searchInput.hasClass('show')) {
    //                     $(this).removeClass('show');
    //                     searchInput.removeClass('show');
    //                 }
    //             }

    //             // Define variables
    //             var value = $(this).val().toLowerCase(), //get values of input on keyup
    //                 activeClass = '',
    //                 bookmark = false,
    //                 liList = $('ul.search-list li'); // get all the list items of the search
    //             liList.remove();

    //             // To check if current is bookmark input
    //             if ($(this).parent().hasClass('bookmark-input')) {
    //                 bookmark = true;
    //             }

    //             // If input value is blank
    //             if (value != '') {
    //                 appContent.addClass('show-overlay');

    //                 // condition for bookmark and search input click
    //                 if (bookmarkInput.focus()) {
    //                     bookmarkSearchList.addClass('show');
    //                 } else {
    //                     searchList.addClass('show');
    //                     bookmarkSearchList.removeClass('show');
    //                 }
    //                 if (bookmark === false) {
    //                     searchList.addClass('show');
    //                     bookmarkSearchList.removeClass('show');
    //                 }

    //                 var $htmlList = ''

    //                 // getting json data from file for search results
    //                 $.getJSON(ticketsUrl, {
    //                     id: value
    //                 }, function(data) {
    //                     for (var i = 0; i < data.length; i++) {

    //                         $htmlList +=
    //                             '<li class="auto-suggestion ' + (i == 0 ? "current_item" : "") + '">' +
    //                             '<a class="d-flex align-items-center justify-content-between w-100" href="/ticket-details/' +
    //                             data[i].coustom_id + '">' +
    //                             '<div class="d-flex justify-content-start align-items-center">' +
    //                             '<span>' + data[i].coustom_id + ' | ' + data[i].subject + ' | ' + data[i]
    //                             .created_at + '</span>' +
    //                             '</div></a>' +
    //                             '</li>';

    //                     }
    //                     $('ul.search-list').html($htmlList);

    //                 });

    //             }
    //         }
    //     });



    $('.content-overlay').on('click', function() {
        $('.search-list').removeClass('show');
        if ($('.search-input').hasClass('open')) {
            // $('.search-input').removeClass('open');
            $('.search-input #ticket_search').val('');
            $('.search-input #ticket_search').blur();
            $('.search-input .search-list').removeClass('show');
        }
        $('.app-content').removeClass('show-overlay');
        $('.bookmark-wrapper .bookmark-input').removeClass('show');
    });

    function searchInputField(searchField,input,url){
        var bookmarkInput = $('.bookmark-wrapper .bookmark-input'),
        searchInputInputfield = $('#'+searchField +' .search-input #'+ input),
        bookmarkSearchList = $('.bookmark-input .search-list'),
        searchInput = $('#'+searchField +' .search-input'),
        searchList = $('#'+searchField +' .search-input .search-list'),
        appContent = $('.app-content'),
        searchInput = $('#'+searchField +' .search-input-close').closest('.search-input'),
        ticketsUrl = url;


        searchInputInputfield.on('keyup', function(e) {
            $(this).closest('#'+searchField +' .search-list').addClass('show');
            if (e.keyCode !== 38 && e.keyCode !== 40 && e.keyCode !== 13) {
                if (e.keyCode == 27) {
                    appContent.removeClass('show-overlay');
                    bookmarkInput.find('input').val('');
                    bookmarkInput.find('input').blur();
                    searchInputInputfield.val('');
                    searchInputInputfield.blur();
                    searchInput.removeClass('open');
                    if (searchInput.hasClass('show')) {
                        $(this).removeClass('show');
                        searchInput.removeClass('show');
                    }
                }

                // Define variables
                var value = $(this).val().toLowerCase(), //get values of input on keyup
                    activeClass = '',
                    bookmark = false,
                    liList = $('#'+searchField+' ul.search-list li'); // get all the list items of the search
                liList.remove();

                // To check if current is bookmark input
                if ($(this).parent().hasClass('bookmark-input')) {
                    bookmark = true;
                }

                // If input value is blank
                if (value != '') {
                    // appContent.addClass('show-overlay');

                    // condition for bookmark and search input click
                    if (bookmarkInput.focus()) {
                        bookmarkSearchList.addClass('show');
                    } else {
                        searchList.addClass('show');
                        bookmarkSearchList.removeClass('show');
                    }
                    if (bookmark === false) {
                        searchList.addClass('show');
                        bookmarkSearchList.removeClass('show');
                    }

                    var $htmlList = ''
                   
                    // getting json data from file for search results

                    $.getJSON(ticketsUrl, {
                            id: value
                        }, function(data) {
                            console.log(data)
                            
                            for (var i = 0; i < data.length; i++) {
                                
                                let user_badge= (data[i].is_staff_tkt == 1 ? 'Staff' : 'User');
                                let path = (root + '/' + data[i].user_pic);
                                let img = `<img src="`+ path +`" style="border-radius: 50%;" class="rounded-circle " width="40px" height="40px" />`;

                                $htmlList +=
                                    '<li class="auto-suggestion ' + (i == 0 ? "current_item" : "") + '">' +
                                        '<a class="" href="{{url("ticket-details")}}/' + data[i].coustom_id + '">' +
                                            '<div class="modal-first">' +
                                                '<div class="mt-0 mt-0 rounded" style="padding:4px; ">' +
                                                    // '<div class="float-start rounded me-1 bg-none" style="margin-top:5px">' +
                                                    //     ' <div class="">' + img + '</div>' +
                                                    // '</div>' +
                                                    '<div class="more-info">' +
                                                        '<div class="" style="display: -webkit-box">' +
                                                            ' <span class="mb-0" style="font-size: 11px;"><strong>Ticket ID: </strong>' + (data[i].coustom_id) +'</span>' +
                                                    '</div>' +
                                                    '<div class="first">' +
                                                        '<span style="font-size:11px"><strong>Subject: </strong>' + data[i].subject + '</span>' +
                                                    '</div>' +
                                                    '<div class="first">' +
                                                        '<span style="font-size:11px"><strong>Department: </strong>' + data[i].department_name + ' </span>' +
                                                        '<span style="font-size:11px">| <strong>Status: </strong>' + data[i].status_name + ' </span>' +
                                                        '<span style="font-size:11px">| <strong>Type: </strong>' + data[i].type_name + ' </span>' +
                                                        '<span style="font-size:11px">| <strong>Priority: </strong>' + data[i].priority_name + ' </span>' +
                                                        
                                                    '</div>' +
                                                    '<div class="first">' +
                                                        '<span style="font-size:11px"><strong>Owner: </strong>' + data[i].assignee_name + ' </span>' +
                                                        '<span style="font-size:11px">| <strong>Created by: </strong>' + (data[i].creator_name != null ? data[i].creator_name : data[i].customer_name) +'<span class="badge badge-secondary mx-25"> '+ user_badge +'</span></span>' +
                                                        '<span style="font-size:11px">| <strong>Last Replier: </strong>' + (data[i].lastReplier = null ? data[i].lastReplier ?? data[i].creator_name : data[i].customer_name) + ' </span>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</a>' +
                                    '</li>';

                                    // $htmlList +=
                                    // '<li class="auto-suggestion ' + (i == 0 ? "current_item" : "") + '">' +
                                    //     '<a class="" href="{{url("ticket-details")}}/' + data[i].coustom_id + '">' +
                                    //         '<div class="modal-first">' +
                                    //             '<div class="mt-0 mt-0 rounded" style="padding:4px; ">' +
                                    //                 '<div class="float-start rounded me-1 bg-none" style="margin-top:5px">' +
                                    //                     ' <div class="">' + img + '</div>' +
                                    //                 '</div>' +
                                    //                 '<div class="more-info">' +
                                    //                     '<div class="" style="display: -webkit-box">' +
                                    //                         ' <h6 class="mb-0">' + (data[i].creator_name != null ? data[i].creator_name : data[i].customer_name) +'<span class="badge badge-secondary"> '+ user_badge +'</span></h6>' +
                                    //                         '<span class="ticket-timestamp3 text-muted small" style="margin-left: 9px;">Ticket ID ('+ data[i].coustom_id +' )</span>' +
                                    //                 '</div>' +
                                    //                 '<div class="first">' +
                                    //                     '<span style="font-size:14px">' + data[i].subject + ' </span>' +
                                    //                 '</div>' +
                                    //             '</div>' +
                                    //         '</div>' +
                                    //     '</a>' +
                                    // '</li>';

                                    // '<li class="auto-suggestion ' + (i == 0 ? "current_item" : "") + '">' +
                                    // '<a class="d-flex align-items-center justify-content-between w-100" href="{{url("ticket-details")}}/' +
                                    // data[i].coustom_id + '">' +
                                    // '<div class="d-flex justify-content-start align-items-center">' +
                                    // '<span>' + data[i].coustom_id + ' | ' + data[i].subject + ' | ' + data[i]
                                    // .created_at + '</span>' +
                                    // '</div></a>' +
                                    // '</li>';

                            }
                            $('#'+searchField+' ul.search-list').html($htmlList);
                        });

                }else{
                    if (searchInput.hasClass('open')) {
                        searchInput.removeClass('open');
                        searchInputInputfield.val('');
                        searchInputInputfield.blur();
                    }
                    searchList.removeClass('show');
                    $('.app-content').removeClass('show-overlay');
                    $('.bookmark-wrapper .bookmark-input').removeClass('show');
                }
            }
        });
    }

    function searchInputFieldCustomer(searchField,input,url){
        var bookmarkInput = $('.bookmark-wrapper .bookmark-input'),
        searchInputInputfield = $('#'+searchField +' .search-input-customer #'+ input),
        bookmarkSearchList = $('.bookmark-input .search-list-customer'),
        searchInput = $('#'+searchField +' .search-input-customer'),
        searchList = $('#'+searchField +' .search-input-customer .search-list-customer'),
        appContent = $('.app-content'),
        searchInput = $('#'+searchField +' .search-input-close').closest('.search-input-customer'),
        ticketsUrl = url;


        searchInputInputfield.on('keyup', function(e) {

            $(this).closest('#'+searchField +' .search-list-customer').addClass('show');
            if (e.keyCode !== 38 && e.keyCode !== 40 && e.keyCode !== 13) {
                if (e.keyCode == 27) {
                    appContent.removeClass('show-overlay');
                    bookmarkInput.find('input').val('');
                    bookmarkInput.find('input').blur();
                    searchInputInputfield.val('');
                    searchInputInputfield.blur();
                    searchInput.removeClass('open');
                    if (searchInput.hasClass('show')) {
                        $(this).removeClass('show');
                        searchInput.removeClass('show');
                    }
                }

                // Define variables
                var value = $(this).val().toLowerCase(), //get values of input on keyup
                    activeClass = '',
                    bookmark = false,
                    liList = $('#'+searchField+' ul.search-list-customer li'); // get all the list items of the search
                liList.remove();

                // To check if current is bookmark input
                if ($(this).parent().hasClass('bookmark-input')) {
                    bookmark = true;
                }

                // If input value is blank
                if (value != '') {
                    // appContent.addClass('show-overlay');

                    // condition for bookmark and search input click
                    if (bookmarkInput.focus()) {
                        bookmarkSearchList.addClass('show');
                    } else {
                        searchList.addClass('show');
                        bookmarkSearchList.removeClass('show');
                    }
                    if (bookmark === false) {
                        searchList.addClass('show');
                        bookmarkSearchList.removeClass('show');
                    }

                    var $htmlList = ''


                    // getting json data from file for search results

                    $.getJSON(ticketsUrl, {
                        id: value
                    }, function(data) {
                        for (var i = 0; i < data.length; i++) {

                                var company = data[i].company != null && data[i].company != "" ? data[i].company : '';
                                var phone = data[i].phone != null && data[i].phone != "" ? data[i].phone : '';
                                var avatar_url = data[i].avatar_url != null && data[i].avatar_url != "" ? data[i].avatar_url : '/public/default_imgs/customer.png';

                                $htmlList +=
                                '<li class="auto-suggestion ' + (i == 0 ? "current_item" : "") + '">' +
                                '<a class="d-flex align-items-center justify-content-between w-100" href="{{url("customer-profile")}}/' +
                                data[i].id + '">' +
                                '<div class="d-flex justify-content-start align-items-center">' +
                                '<span class="avatar">'+
                                '<img src="'+root+'/'+avatar_url+'" class="rounded-circle" id="login_usr_logo" width="50px" height="50px">' +
                                                            
                                '</span>'+
                                '<span>' + data[i].first_name +' '+ data[i].last_name + '(ID: '+data[i].id+') | ' + company + ' | ' + data[i]
                                .email + ' | ' +phone+'</span>' +
                                '</div></a>' +
                                '</li>';

                        }

                        $('#'+searchField+' ul.search-list-customer').html($htmlList);

                    });

                }else{
                    if (searchInput.hasClass('open')) {
                        // searchInput.removeClass('open');
                        searchInputInputfield.val('');
                        searchInputInputfield.blur();
                    }
                    searchList.removeClass('show');
                    $('.app-content').removeClass('show-overlay');
                    $('.bookmark-wrapper .bookmark-input').removeClass('show');
                }
            }
        });
    }

</script>
