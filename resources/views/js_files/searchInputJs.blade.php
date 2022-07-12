<script>
    var $filename = $('.search-input #ticket_search').data('search'),
    bookmarkWrapper = $('.bookmark-wrapper'),
    bookmarkStar = $('.bookmark-wrapper .bookmark-star'),
    bookmarkInput = $('.bookmark-wrapper .bookmark-input'),
    navLinkSearch = $('.nav-link-search'),
    searchInput = $('.search-input'),
    searchInputInputfield = $('.search-input input'),
    searchList = $('.search-input .search-list'),
    appContent = $('.app-content'),
    bookmarkSearchList = $('.bookmark-input .search-list');
    // var tickets = @json($tickets);
    var ticketsUrl = "{{url('search-ticket')}}";


    searchInputInputfield.on('keyup', function (e) {

    $(this).closest('.search-list').addClass('show');
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
        liList = $('ul.search-list li'); // get all the list items of the search
      liList.remove();
      // To check if current is bookmark input
      if ($(this).parent().hasClass('bookmark-input')) {
        bookmark = true;
      }

      // If input value is blank
      if (value != '') {
        appContent.addClass('show-overlay');

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
        $.getJSON(ticketsUrl, {id:value}, function (data) {
            for (var i = 0; i < data.length; i++) {

              $htmlList +=
                '<li class="auto-suggestion '+(i == 0 ? "current_item" : "")+'">' +
                '<a class="d-flex align-items-center justify-content-between w-100" href="/ticket-details/'+data[i].coustom_id+'">' +
                '<div class="d-flex justify-content-start align-items-center">'+
                '<span>'+ data[i].coustom_id +' | '+ data[i].subject +' | ' +data[i].created_at+'</span>' +
                '</div></a>' +
                '</li>';

          }
          $('ul.search-list').html($htmlList);

        });
      } else {

      }
    }
  });
</script>
