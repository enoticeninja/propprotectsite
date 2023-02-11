function getCollapsiblePanel(data){
	var id = getUniqueId();
	var html = 
	'\
	<div class="panel panel-default">\
		<div class="panel-heading">\
			<h4 class="panel-title">\
				<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#'+data['parent']+'" href="#collapse_'+id+'"> '+data['title']+' </a>\
			</h4>\
		</div>\
		<div id="collapse_'+id+'" class="panel-collapse collapse">\
			<div class="panel-body">\
				'+data['content']+'\
			</div>\
		</div>\
	</div>\
	\
	';

	return html;
}

function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}

function getUniqueId(){
	var id = Math.floor(Math.random() * 26) + Date.now() + Math.floor(Math.random() * 26);
	return id;
}
