function getNestableCollapsibleList($data){
    $row = $data;
    if(!isset($data['__child_list__'])){
        $data['__child_list__'] = ''; 
    }
	var $return = {};
    $return['jsFunction'] = [];
    $return['post'] = $data;    
    if($data['__list_type__'] == 'primary'){
        {
		
		//$row['name'] = (strlen($row['name']) > 60) ? substr($row['name'],0,55).'...' : $row['name'];
        $return['newrow'] = 
        '\
		<li class="m-accordion__item sortable-item-parimary" data-id="'+$row['id']+'"  id="collapsible-primary-'+$row['id']+'">\
			<div class="m-accordion__item-head collapsed"  role="tab" id="collapsible-primary-'+$row['id']+'_head" data-toggle="collapse" href="#collapsible-primary-'+$row['id']+'_body" aria-expanded="    false">\
			<span class="m-accordion__item-mode"></span>\
			<span class="m-accordion__item-icon">\
					<i class="fa flaticon-layers"></i>\
				</span>\
				<span class="m-accordion__item-title">\
					<span class="dd-handle dd-handle-primary"><i class="fa fa-arrows font-white"></i></span>\
					<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#nestable_list_1"  href="#collapse_'+$row['id']+'_'+$row['i']+'" id="collapsible-primary-label-'+$row['id']+'">\
					</a>\
					<span class="" style=""> \
						<span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red collDeletePrimary"  data-id="'+$row['id']+'" data-child_module="'+$row['child_module']+'" data-parent_module="'+$row['parent_module']+'" data-parent_fk="'+$row['parent_fk']+'">Delete</a></span>\
						<span class="text-right" style="float:right" ><a href="javascript:;" class="btn btn-sm yellow collEditPrimary"  data-id="'+$row['id']+'" data-child_module="'+$row['child_module']+'" data-parent_module="'+$row['parent_module']+'" data-parent_fk="'+$row['parent_fk']+'">Edit</a></span>\
						<span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm green collAddChild"  data-id="'+$row['id']+'" data-child_module="'+$row['child_module']+'" data-parent_module="'+$row['parent_module']+'" data-parent_fk="'+$row['parent_fk']+'">Add Child</a></span>\
					</span> \
					'+$row['name']+'\
				</span>\
			</div>\
			<div class="m-accordion__item-body collapse" id="collapsible-primary-'+$row['id']+'_body" class=" " role="tabpanel" aria-labelledby="collapsible-primary-'+$row['id']+'_head" data-parent="#nestable_main_ol">\
				<div class="m-accordion__item-content">\
				<ul class="m-accordion m-accordion--bordered m-accordion--solid sortable-primary" id="collapsible-primary-container-'+$row['id']+'" role="tablist" data-id="'+$row['id']+'">\
				'+$data['__child_list__']+'\
				</ul>\
					</div>\
				</div>\
            </li>\
        ';
        }    
    }
    else if($data['__list_type__'] == 'secondary'){
        {
		
			//$row['name'] = (strlen($row['name']) > 60) ? substr($row['name'],0,55).'...' : $row['name'];
            $return['newrow'] = 
            '\
			<li class="m-accordion__item sortable-item-secondary" data-id="'+$row['id']+'" id="collapsible-secondary-'+$row['id']+'">\
				<div class="m-accordion__item-head collapsed"  role="tab" id="collapsible-secondary-'+$row['id']+'_head" data-toggle="collapse" href="#collapsible-secondary-'+$row['id']+'_body" aria-expanded="    false">\
				<span class="m-accordion__item-mode"></span>\
				<span class="m-accordion__item-icon">\
						<i class="fa flaticon-layers"></i>\
					</span>\
					<span class="m-accordion__item-title">\
					<span class="dd-handle dd-handle-secondary"><i class="fa fa-arrows font-white"></i></span>\
					<a class=""  id="collapsible-secondary-label-'+$row['parent_id']+'-'+$row['id']+'">\
					 \
					 </a>\
                    <span class="" style="">\
                    <span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red btnDelete collDeleteSecondary" data-id="'+$row['id']+'" data-child_module="'+$row['child_module']+'" data-parent_module="'+$row['parent_module']+'" data-parent_fk="'+$row['parent_fk']+'">Delete</a></span>\
                    <span class="text-right" style="float:right" ><a href="javascript:;" class="btn btn-sm yellow collEditSecondary" data-id="'+$row['id']+'" data-child_module="'+$row['child_module']+'" data-parent_module="'+$row['parent_module']+'" data-parent_fk="'+$row['parent_fk']+'">Edit</a></span>\
                    </span>\
					'+$row['name']+'\
					</span>\
				</div>\
			</li>\
            ';
        }    
    }
    return $return;
    
}

$(document).ready(function(){
		var __funcs = [];
		__funcs.push(function(){
			$(document).on('click','.collEditPrimary',function(){
				let data = {};
				var id = $(this).data('id');
				var module = $(this).data('parent_module');
				data['data'] = {'action':'edit','id':id,'module':module};
				data['isAjax'] = true;
				data['e'] = this;
				let formClass = new FormGenerator(data);
				formClass.actionCallBacks = {
					'save':'doNothing',
					'update':'doNothing'
				};
			});
		});
		__funcs.push(function(){
			$(document).on('click','.collDeleteSecondary',function(){
				let data = {};
				var id = $(this).data('id');
				var module = $(this).data('child_module');
				data = {'action':'delete','id':id,'module':module};
				CommonFunc2Confirmation(data,'',this,{},'removeCollapsibleSecondary');
			});
		});
		__funcs.push(function(){
			$(document).on('click','.collDeletePrimary',function(){
				let data = {};
				var id = $(this).data('id');
				var module = $(this).data('parent_module');
				data = {'action':'delete','id':id,'module':module};
				CommonFunc2Confirmation(data,'',this,{},'removeCollapsiblePrimary');
			});
		});
		__funcs.push(function(){
			$(document).on('click','.collAddChild',function(){
				let data = {};
				var id = $(this).data('id');
				var module = $(this).data('child_module');
				var parent_fk = $(this).data('parent_fk');
				data['data'] = {'action':'new','module':module};
				data['data'][parent_fk] = id;
				data['isAjax'] = true;
				data['e'] = this;
				let formClass = new FormGenerator(data);
				formClass.actionCallBacks = {
					'save':'prependCollapsibleSecondary',
					'update':'doNothing'
				};
			});
		});

		
		__funcs.push(function(){
			$(document).on('click','.collEditSecondary',function(){
				let data = {};
				var id = $(this).data('id');
				var module = $(this).data('child_module');
				data['data'] = {'action':'edit','id':id,'module':module};
				data['isAjax'] = true;
				data['e'] = this;
				let formClass = new FormGenerator(data);
				formClass.actionCallBacks = {
					'save':'doNothing',
					'update':'doNothing'
				};
			});
		});			
		for (i = 0; i < __funcs.length; i++){
			__funcs[i]();
		}
});