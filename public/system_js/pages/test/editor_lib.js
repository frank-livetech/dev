//editor library
(function ($) {
    var g_Heading1Value = '';
    var g_Heading2Value = '';
    var g_ParagraphValue = '';
    var g_BulletsValue = '';
    var g_imgactionheightWidth = 30;

    window.object_editor = function (divId) {
        $('#'+divId).append(`<div class="smart-col scrollbar-style col-12 col-lg-2" style="overflow: auto">
                <nav class="navbar bg-light justify-content-center">
                  <ul class="navbar-nav">
                    <li class="nav-item">
                      <a class="nav-link" href="#"><img id="input.svg" style="width:50px; height:50px;" src="public/files/test/input.svg" height="85" width="85" draggable="true" ondragstart="drag(event)"></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#"><img id="textarea.svg" style="width:50px; height:50px;" src="public/files/test/textarea.svg" height="85" width="85" draggable="true" ondragstart="drag(event)"></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#"><img id="dropdown.svg" style="width:50px; height:50px;" src="public/files/test/dropdown.svg" height="85" width="85" draggable="true" ondragstart="drag(event)"></a>
                    </li>
                  </ul>

                </nav>
            </div>

            <div class="smart-col scrollbar-style col-12 col-lg-8 shadow-sm p-3 mb-5 bg-white mt-3" id="editor_div" style="overflow: auto;border-radius:5px;" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
            
            <input type="file" id="fileid" onchange="placeImage()" style="display:none;" />
        </div>`);

        $('#editor_div').sortable({
            cancel: '[contenteditable=true]'
        });
        // $("#editor_div").disableSelection();
    }

    window.readFile = function () {
        document.getElementById('fileid').value = '';
        
        document.getElementById('fileid').click();
    }

    window.allowDrop = function (ev) {
        ev.preventDefault();
    }

    window.drag = function (ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    window.drop = function (ev) {
        ev.preventDefault();
        console.log(ev);
        var data = ev.dataTransfer.getData("text");

        var para = '';
        if(data == 'input.svg')
            inputSection();
        else if(data == 'textarea.svg')
            textareaSection();
        else if(data == 'dropdown.svg')
            dropdownSection();

    }   

    window.inputSection = function() {
        $('#editor_div').append('<div class="d-flex text-center justify-content-start mt-3" style="padding:10px;background-color: #c9dae4;" onMouseOver="this.style.backgroundColor=`#ffcc00`" onMouseOut="this.style.backgroundColor=`#c9dae4`">\
                <input class="form-control" id="h1-'+Date()+'" contentEditable="true" placeholder="Input Field"/>\
                <img class="ml-auto mr-2 mt-2" style="cursor : pointer;" src="public/files/test/save.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="save(\'1\', this)">\
                <img class="ml-auto mr-2 mt-2" style="display:none; cursor : pointer;" src="public/files/test/edit.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="edit(\'1\', this)">\
                <img class="ml-auto mr-2 mt-2" style="cursor : pointer;" src="public/files/test/del.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="remove(this)">\
                <img class="ml-2 mr-2 mt-2" style="display:none; cursor : pointer;" src="public/files/test/cancel.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="cancel(\'1\', this)">\
            </div>');
    }


    window.textareaSection = function() {
        $('#editor_div').append('<div class="d-flex text-center justify-content-start mt-3" style="padding:10px;background-color: #c9dae4;" onMouseOver="this.style.backgroundColor=`#ffcc00`" onMouseOut="this.style.backgroundColor=`#c9dae4`">\
                <textarea class="form-control" col="20" row="50" id="textarea-'+Date()+'" contentEditable="true" placeholder="Textarea"/>\
                <img class="ml-auto mr-2 mt-2" style="cursor : pointer;" src="public/files/test/save.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="save(\'1\', this)">\
                <img class="ml-auto mr-2 mt-2" style="display:none; cursor : pointer;" src="public/files/test/edit.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="edit(\'1\', this)">\
                <img class="ml-auto mr-2 mt-2" style="cursor : pointer;" src="public/files/test/del.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="remove(this)">\
                <img class="ml-2 mr-2 mt-2" style="display:none; cursor : pointer;" src="public/files/test/cancel.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="cancel(\'1\', this)">\
            </div>');
    }

    window.dropdownSection = function() {
        $('#editor_div').append('<div class="d-flex text-center justify-content-start mt-3" style="padding:10px;background-color: #c9dae4;" onMouseOver="this.style.backgroundColor=`#ffcc00`" onMouseOut="this.style.backgroundColor=`#c9dae4`">\
                <select class="form-control"id="dropdown-'+Date()+'" contentEditable="true" placeholder="Textarea">\
                    <option>select</option>\
                    <option>value 1</option>\
                    <option>value 2</option>\
                </select>\
                <img class="ml-auto mr-2 mt-2" style="cursor : pointer;" src="public/files/test/save.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="save(\'1\', this)">\
                <img class="ml-auto mr-2 mt-2" style="display:none; cursor : pointer;" src="public/files/test/edit.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="edit(\'1\', this)">\
                <img class="ml-auto mr-2 mt-2" style="cursor : pointer;" src="public/files/test/del.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="remove(this)">\
                <img class="ml-2 mr-2 mt-2" style="display:none; cursor : pointer;" src="public/files/test/cancel.svg" width="'+g_imgactionheightWidth+'" height="'+g_imgactionheightWidth+'" onclick="cancel(\'1\', this)">\
            </div>');
    }

    window.edit = function (mode, ele) {
        switch(parseInt(mode)){
            case 1:
                $(ele).parent('div').find('h1').attr('contentEditable', true);

                g_Heading1Value = $(ele).parent('div').find('h1').html();
                
                setCursor($(ele).parent('div').find('h1').attr('id'));
                break;
            case 2:
                $(ele).parent('div').find('h2').attr('contentEditable', true);

                g_Heading2Value = $(ele).parent('div').find('h2').html();

                setCursor($(ele).parent('div').find('h2').attr('id'));
                break;
            case 3:
                $(ele).parent('div').find('p').attr('contentEditable', true);

                g_ParagraphValue = $(ele).parent('div').find('p').html();

                setCursor($(ele).parent('div').find('p').attr('id'));
                break;
            case 4:
                $(ele).parent('div').find('li').attr('contentEditable', true);
                $(ele).parent('div').find('li').focus();

                g_BulletsValue = $(ele).parent('div').find('div').html();

                // setCursor($(ele).parent('div').find('li').attr('id'));
        }

        $(ele).next().fadeOut(125);
            $(ele).fadeOut(125, function() {
            $(ele).prev().fadeIn(125);
            $(ele).next().next().fadeIn(125);
        });

        $(ele).parent('div').css({'background-color': '#c9dae4'});
        $(ele).parent('div').attr('onMouseOver', 'this.style.backgroundColor=`#ffcc00`');
        $(ele).parent('div').attr('onMouseOut', 'this.style.backgroundColor=`#c9dae4`');
    }

    window.save = function (mode, ele) {
        switch(parseInt(mode)){
            case 1:
                $(ele).parent('div').find('h1').attr('contentEditable', false);
                // $(ele).parent('div').find('h1').html($(ele).parent('div').find('h1').html());
                g_Heading1Value = '';
                break;
            case 2:
                $(ele).parent('div').find('h2').attr('contentEditable', false);
                // $(ele).parent('div').find('h2').html($(ele).parent('div').find('h2').html());
                g_Heading2Value = '';
                break;
            case 3:
                $(ele).parent('div').find('p').attr('contentEditable', false);
                // $(ele).parent('div').find('p').html($(ele).parent('div').find('p').html());
                g_ParagraphValue = '';
                break;
            case 4:
                $(ele).parent('div').find('li').attr('contentEditable', false);
                // $(ele).parent('div').find('p').html($(ele).parent('div').find('p').html());
                g_BulletsValue = '';

        }

        //toggle action buttons
        $(ele).next().next().next().fadeOut(125)
        $(ele).fadeOut(125, function() {
            $(ele).next().fadeIn(125);
            $(ele).next().next().fadeIn(125);
        });

        $(ele).parent('div').attr('onMouseOver', '');
        $(ele).parent('div').attr('onMouseOut', '');
        $(ele).parent('div').css({'background-color': 'white'});
    }

    window.remove = function (ele) {
        $(ele).parent('div').remove();
    }

    window.cancel = function (mode, ele) {
        switch(parseInt(mode)){
            case 1:
                $(ele).parent('div').find('h1').attr('contentEditable', false);
                $(ele).parent('div').find('h1').html(g_Heading1Value);
                g_Heading1Value = '';
                break;
            case 2:
                $(ele).parent('div').find('h2').attr('contentEditable', false);
                $(ele).parent('div').find('h2').html(g_Heading2Value);
                g_Heading2Value = '';
                break;
            case 3:
                $(ele).parent('div').find('p').attr('contentEditable', false);
                $(ele).parent('div').find('p').html(g_ParagraphValue);
                g_ParagraphValue = '';
                break;
            case 4:
                $(ele).parent('div').find('div').attr('contentEditable', false);
                $(ele).parent('div').find('div').html(g_BulletsValue);
                g_BulletsValue = '';
                break;
        }

        $(ele).prev().prev().prev().fadeOut(125);
        $(ele).fadeOut(125, function() {
            $(ele).prev().prev().fadeIn(125);
            $(ele).prev().fadeIn(125);
        });
    }

    window.removeImage = function (ele) {
        $(ele).parent('div').parent('div').remove();
    }

    window.setCursor = function(id) { 
        var tag = document.getElementById(id); 
          
        // Creates range object 
        var setpos = document.createRange(); 
          
        // Creates object for selection 
        var set = window.getSelection(); 
          
        // Set start position of range 
        setpos.setStart(tag.childNodes[0], tag.innerText.length); 
          
        // Collapse range within its boundary points 
        // Returns boolean 
        setpos.collapse(true); 
          
        // Remove all ranges set 
        set.removeAllRanges(); 
          
        // Add range with respect to range object. 
        set.addRange(setpos); 
          
        // Set cursor on focus 
        tag.focus(); 
    }
})(jQuery);