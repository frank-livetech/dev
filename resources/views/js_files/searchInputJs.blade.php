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
                            for (var i = 0; i < data.length; i++) {
                                    $htmlList +=
                                    '<li class="auto-suggestion ' + (i == 0 ? "current_item" : "") + '">' +
                                    '<a class="d-flex align-items-center justify-content-between w-100" href="{{url("ticket-details")}}/' +
                                    data[i].coustom_id + '">' +
                                    '<div class="d-flex justify-content-start align-items-center">' +
                                    '<span>' + data[i].coustom_id + ' | ' + data[i].subject + ' | ' + data[i]
                                    .created_at + '</span>' +
                                    '</div></a>' +
                                    '</li>';

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

                                $htmlList +=
                                '<li class="auto-suggestion ' + (i == 0 ? "current_item" : "") + '">' +
                                '<a class="d-flex align-items-center justify-content-between w-100" href="{{url("customer-profile")}}/' +
                                data[i].id + '">' +
                                '<div class="d-flex justify-content-start align-items-center">' +
                                '<span>' + data[i].first_name +' '+ data[i].last_name + '(ID: '+data[i].id+') | ' + data[i].company + ' | ' + data[i]
                                .email + ' | ' +data[i].phone+'</span>' +
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
