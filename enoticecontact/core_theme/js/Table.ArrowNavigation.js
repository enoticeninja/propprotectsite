var ArrowNavigation = function(tableId){
    var $table = $('#'+tableId+'');
    //if(typeof focusChildNode === 'undefined')var focusChildNode = true;
    //focusedTd = $('.excel-focus-start')[0];
    /* if($('#'+tableId+' tr').eq(1).length){
        console.log('no length');
    } */
    if(focusChildNode){
        if($('.excel-cell-input')[0])focusedTd = $('.excel-cell-input')[0].closest('td');
        try{
            focusedTd.childNodes[0].focus();
            focusedTd.childNodes[0].classList.add('excel-selected-cell');
        }
        catch(ex){}
    }
    else{
        focusedTd.focus();
        focusedTd.classList.add('excel-selected-cell');
    }
    //focusedTd.find('.excel-cell-input').focus();
    /* focusedTd.style.backgroundColor = 'green';
    focusedTd.style.color = 'white'; */

    function construct(){

    }
    function focusColumn(sibling) {
        if(focusChildNode && sibling.childNodes[0].classList && sibling.childNodes[0].classList.contains('excel-cell-input')){
            focusColumnChildNode(sibling)
        }
        if(!focusChildNode){
            focusColumnItself(sibling)
        }
    }

    function focusColumnChildNode(sibling) {
        if (sibling != null) {
            if (sibling.childNodes[0]) {
                focusedTd.childNodes[0].focus();
                focusedTd.childNodes[0].classList.remove('excel-selected-cell');
                //focusedTd.closest('tr').classList.remove('excel-selected-tr');
                //start.childNodes[0].style.color = '';
                //sibling.focus();
                sibling.childNodes[0].focus();
                sibling.childNodes[0].classList.add('excel-selected-cell');
                //sibling.childNodes.select();
                //sibling.closest('tr').classList.add('excel-selected-tr');
                //sibling.childNodes[0].style.color = 'white';
                focusedTd = sibling;
            }
        }
    }
    function focusColumnItself(sibling) {
        if (sibling != null) {
            if (sibling) {
                focusedTd.focus();
                focusedTd.classList.remove('excel-selected-cell');
                focusedTd.closest('tr').classList.remove('excel-selected-tr');
                //start.childNodes[0].style.color = '';
                //sibling.focus();
                sibling.focus();
                sibling.classList.add('excel-selected-cell');
                focusedTd.closest('tr').classList.add('excel-selected-tr');
                //sibling.childNodes[0].style.color = 'white';
                focusedTd = sibling;
            }
        }
    }

    //document.onkeydown = checkKey;
    $(document).on('keydown','.excel-td',function(e){
        //console.log(sibling[0]);
        if(focusedTd)checkKey(e);
    })
    $(document).on('mousedown','.excel-tr',function(e){
        if(selectedTr && selectedTr[0])selectedTr[0].classList.remove('excel-selected-tr');
        sibling = $(e.target).closest('td')[0] ? $(e.target).closest('td')[0] : sibling;
        selectedTr = $(e.target).closest('tr');
        if(selectedTr && selectedTr[0]){
            selectedTr[0].classList.add('excel-selected-tr');
            if(!focusedTd)focusedTd = sibling;
            focusColumn(sibling);
        }
    })
    /* $(document).on('mousedown','.excel-td',function(e){
        if(selectedTr)selectedTr[0].classList.remove('excel-selected-tr');
        sibling = $(e.target).closest('td')[0];
        selectedTr = $(e.target).closest('tr');
        selectedTr[0].classList.add('excel-selected-tr');
        focusColumn(sibling);
    }) */

    function checkKey(e) {
        e = e || window.event;
        if (e.keyCode == '38') {
            // up arrow
            e.preventDefault();
            var idx = focusedTd.cellIndex;
            var nextrow = focusedTd.parentElement.previousElementSibling;
            if (nextrow != null) {
                var sibling = nextrow.cells[idx];
                //focusColumn(sibling);
            }
        } 
        else if (e.keyCode == '40') {
            // down arrow
            e.preventDefault();
            var idx = focusedTd.cellIndex;
            var nextrow = focusedTd.parentElement.nextElementSibling;
            if (nextrow != null) {
                var sibling = nextrow.cells[idx];
                //focusColumn(sibling);
            }
        } 
        else if (e.keyCode == '37' || (e.shiftKey && e.keyCode == 9)) {
            // left arrow
            e.preventDefault();
            var sibling = focusedTd.previousElementSibling;
            if(sibling){
                while (sibling) {
                    if((sibling.childNodes[0].classList && sibling.childNodes[0].classList.contains('excel-cell-input'))){
                        break;
                    }
                    else{
                        sibling = sibling.previousElementSibling;
                    }
                }
            }
            //focusColumn(sibling);
        }
        else if (e.keyCode == '39' || e.keyCode == '9') {
            // right arrow and tab
            e.preventDefault();
            var sibling = focusedTd.nextElementSibling;
            if(sibling){
                while (sibling) {
                    if((sibling.childNodes[0].classList && sibling.childNodes[0].classList.contains('excel-cell-input'))){
                        break;
                    }
                    else{
                        sibling = sibling.nextElementSibling;
                    }
                }
            }     
            //focusColumn(sibling);            
        } 
        else if (e.keyCode == '13') {
            // down arrow on ENTER
            e.preventDefault();
            var idx = focusedTd.cellIndex;
            var nextrow = focusedTd.parentElement.nextElementSibling;
            if (nextrow != null) {
                var sibling = nextrow.cells[idx];
                //focusColumn(sibling);
            }          
        }
        else if(e.which === 3 || e.button === 2) {
            var sibling = focusedTd.nextElementSibling;
            //focusColumn(sibling);
        }
        if(sibling)focusColumn(sibling);
    }
    function nextInDOM(_selector, _subject) {
        var next = getNext(_subject);
        while(next.length != 0) {
            var found = searchFor(_selector, next);
            if(found != null) return found;
            next = getNext(next);
        }
        return null;
    }
    function getNext(_subject) {
        if(_subject.next().length > 0) return _subject.next();
        return getNext(_subject.parent());
    }
    function searchFor(_selector, _subject) {
        if(_subject.is(_selector)) return _subject;
        else {
            var found = null;
            _subject.children().each(function() {
                found = searchFor(_selector, $(this));
                if(found != null) return false;
            });
            return found;
        }
        return null; // will/should never get here
    }
    construct();
}