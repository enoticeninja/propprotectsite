var thisElement;
var caretPosition;
function MySummerInit(element,unique_id,height){

$('#'+element+'').summernote({
  height: height,
  callbacks: {

    onInit: function() {
      //console.log('Summernote is launched');
      // Add "open" - "save" buttons
      var noteBtn = '';
/*       var fileGroup = '<div class="note-file btn-group"><button id="'+unique_id+'-btn-link-db" type="button" class="btn btn-default btn-sm btn-small my-link-db" title="Add Link From Databse" data-event="something" tabindex="-1"><i class="fa fa-database font-yellow" style="font-size:25px"></i><i class="fa fa-link font-yellow" style="font-size:25px"></i></button></div>';
      var fileGroup2 = '<div class="note-file btn-group"><button id="'+unique_id+'-btn-link-db-text" type="button" class="btn btn-default btn-sm btn-small my-link-db-text" title="Add Text and Link From Databse" data-event="something" tabindex="-1"><i class="fa fa-database font-yellow" style="font-size:25px"></i><i class="fa fa-pencil font-yellow" style="font-size:25px"></i><i class="fa fa-external-link-square font-yellow" style="font-size:25px"></i></button></div>';
      //$(fileGroup).appendTo($('.note-toolbar'));
      $(this).next().find('.note-toolbar').append(fileGroup);
      $(this).next().find('.note-toolbar').append(fileGroup2); */
      var tempBar = $(this).find('div.note-toolbar');
      // Button tooltips
/*       $('#'+unique_id+'-btn-link-db').tooltip({
        container: 'body',
        placement: 'bottom'
      });
      $('#'+unique_id+'-btn-link-db-text').tooltip({
        container: 'body',
        placement: 'bottom'
      }); */
      // Button events
/*       $('#'+unique_id+'-btn-link-db').click(function(event) {
          if(window.getSelection().toString() != ""){
            $('#basic').modal('show');   
            thisElement = $(this).closest('.note-editor').find('.note-editable')[0];            
            CommonFunc2({'type':'get-link-db'});              
          }


      });  
      $('#'+unique_id+'-btn-link-db-text').click(function(event) {
        $('#basic').modal('show');
        thisElement = $(this).closest('.note-editor').find('.note-editable')[0];
        CommonFunc2({'type':'get-link-db-text'}); 
      });  */   
      
    }
  }
});
    
}

var page_link_path,page_link_class;
/*    
$('body').on('change','#page-link', function(){
    page_link_path = $('#page-link').val();    
});  */                   
$('body').on('click','.my-link-db-btn', function(){
     var highlight = window.getSelection(); 
     var spn = document.createElement('a');
     var range = highlight.getRangeAt(0); 
    spn.innerHTML = highlight;
    console.log(highlight);
    if(highlight.toString() != ""){
        range.deleteContents();
        range.insertNode(spn);                    
        spn.href = $('#page-link').val();
        range.deleteContents();
        range.insertNode(spn);             
    }    
  
});                  
$('body').on('click','.my-link-db-btn-text', function(){
    var spn = document.createElement('a');                   
    var href = $('#page-link-text').val();      
    var className = $('#page-link-text-class').val();      
    var text = $('#page-link-text-text').val(); 
    fakeEl = '<a href="'+href+'" class="'+className+'">'+text+'</a>';
    pasteHtmlAtCaret(fakeEl,thisElement);    
  
});
function setEndOfContenteditable(contentEditableElement) {
    var range, selection;
    if (document.createRange) //Firefox, Chrome, Opera, Safari, IE 9+
    {
        range = document.createRange(); //Create a range (a range is a like the selection but invisible)
        range.selectNodeContents(contentEditableElement); //Select the entire contents of the element with the range
        range.collapse(false); //collapse the range to the end point. false means collapse to end rather than the start
        selection = window.getSelection(); //get the selection object (allows you to change selection)
        selection.removeAllRanges(); //remove any selections already made
        selection.addRange(range); //make the range you have just created the visible selection
    } else if (document.selection) //IE 8 and lower
    {
        range = document.body.createTextRange(); //Create a range (a range is a like the selection but invisible)
        range.moveToElementText(contentEditableElement); //Select the entire contents of the element with the range
        range.collapse(false); //collapse the range to the end point. false means collapse to end rather than the start
        range.select(); //Select the range (make it the visible selection
    }
}

function elementContainsSelection(el) {
    var sel;
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.rangeCount > 0) {
            for (var i = 0; i < sel.rangeCount; ++i) {
                if (!isOrContains(sel.getRangeAt(i).commonAncestorContainer, el)) {
                    return false;
                }
            }
            return true;
        }
    } else if ((sel = document.selection) && sel.type != "Control") {
        return isOrContains(sel.createRange().parentElement(), el);
    }
    return false;
}

function isOrContains(node, container) {
    while (node) {
        if (node === container) {
            return true;
        }
        node = node.parentNode;
    }
    return false;
}

function pasteHtmlAtCaret(html, el) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (elementContainsSelection(el)) {
            if (sel.getRangeAt && sel.rangeCount) {
                range = sel.getRangeAt(0);
                range.deleteContents();

                // Range.createContextualFragment() would be useful here but is
                // non-standard and not supported in all browsers (IE9, for one)
                var el = document.createElement("div");
                el.innerHTML = html;
                var frag = document.createDocumentFragment(),
                    node, lastNode;
                while ((node = el.firstChild)) {
                    lastNode = frag.appendChild(node);
                }
                range.insertNode(frag);

                // Preserve the selection
                if (lastNode) {
                    range = range.cloneRange();
                    range.setStartAfter(lastNode);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            } else if (document.selection && document.selection.type != "Control") {
                // IE < 9
                document.selection.createRange().pasteHTML(html);
            }
        } else {
            setEndOfContenteditable(el);
            pasteHtmlAtCaret(html, el);
        }
    }

}

