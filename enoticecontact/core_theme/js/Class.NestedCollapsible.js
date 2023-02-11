var FormGenerator = function(options){
	var $buttons = [];
	var $include_css = [];
	var $include_js = [];
	var $this = this;
	this.form_group_type = 'floating-12-12';
	var $form = new Form();
	this.options = {};
	this.functions = [];
	this.uniqueId = uniqueid();
	
	this.construct = function(options){
		this.options = $.extend(true,this.options,options);
    };
	
	function getNestableCollapsibleList($data){
		$row = $data;
		if(!isset($data['__child_list__'])){
			$data['__child_list__'] = ''; 
		}
		$return['jsFunction'] = '';
		$return['post'] = $data;    
		if($data['__list_type__'] == 'primary'){
			{
 
			$this.functions.push(
				function(){			
					$('#btn-'+$this.uniqueId+'-primary-delete').on('click',function(){
						CommonFunc2Confirmation({
							'action':'delete-nestable',
							'id':$row['id'],
							'module':$data['parent_module'],
							'removeElement':'collapsible-primary-'+$row['id']+''
							},
							'',
							this)
					});
				}
			)
			
			$this.functions.push(
				function(){			
					$('#btn-'+$this.uniqueId+'-primary-edit').on('click',function(){
						CommonFunc2Btn({
							'extended_action':'get',
							'form_type':'edit',
							'return-action':'nestable-list',
							'module':$data['parent_module'],
							'id':$row['id']},
							'',
							this,
							{
							'return':'collapsible-list',
							'parent_module':$data['parent_module'],
							'child_module':$data['child_module'],
							'__list_type__':'primary'
						});
					});
				}
			)
			
			$this.functions.push(
				function(){			
					$('#btn-'+$this.uniqueId+'-primary-child').on('click',function(){
					CommonFunc2Btn({
						'extended_action':'get',
						'form_type':'new',
						'return-action':'nestable-list',
						'parent_id':$row['id'],
						'module':$data['child_module']
						},'',this,{
							'return':'collapsible-list',
							'parent_id':$row['id'],
							'__list_type__':'secondary',
							'parent_module':$row['parent_module'],
							'child_module':$row['child_module']
						});
					});
				}
			)
			

			$row['name'] = (strlen($row['name']) > 60) ? substr($row['name'],0,55).'...' : $row['name'];
			$return['newrow'] = 
			'\
				<li class="dd-item panel panel-default sortable-item-parimary" data-id="'+$row['id']+'" id="collapsible-primary-'+$row['id']+'">\
					<div class="panel-heading">\
						<h4 class="panel-title">\
						<span class="dd-handle dd-handle-primary"><i class="fa fa-arrows font-white"></i></span>\
						<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#nestable_list_1"  href="#collapse_'+$row['id']+'_'+$row['i']+'" id="collapsible-primary-label-'+$row['id']+'">\
						'+$row['name']+'\
						</a>\
						<span class="" style=""> \
							<span class="text-right" style="float:right"><a href="javascript:;" id-"btn-'+$this.uniqueId+'-primary-delete" class="btn btn-sm red" >Delete</a></span>\
							<span class="text-right" style="float:right" ><a href="javascript:;" id-"btn-'+$this.uniqueId+'-primary-edit" class="btn btn-sm yellow" >Edit</a></span>\
							<span class="text-right" style="float:right"><a href="javascript:;" id-"btn-'+$this.uniqueId+'-primary-child" class="btn btn-sm green" >Add Child</a></span>\
						</span>\
						</h4>\
					</div>\
				\
					<div id="collapse_'+$row['id']+'_'+$row['i']+'" class="panel-collapse collapse in">\
						<div class="dd container panel-group  " id="accordion_'+$row['id']+'">\
						<ul class="dd-list sortable-primary" id="collapsible-primary-container-'+$row['id']+'" data-id="'+$row['id']+'">\
						'+$data['__child_list__']+'\
						</ul>\
						\
						</div>\
					</div>\
				</li>\
			';
			}    
		}
		else if($data['__list_type__'] == 'secondary'){
			{
				$delFunc = "CommonFunc2Confirmation({'action':'delete-nestable','id':'$row[id]','module':'$data[child_module]','removeElement':'collapsible-secondary-$row[id]'},'',this)";

					
				$this.functions.push(
					function(){			
						$('#btn-'+$this.uniqueId+'-primary-delete').on('click',function(){
							CommonFunc2Confirmation({
								'action':'delete-nestable',
								'id':$row['id'],
								'module':$data['child_module'],
								'removeElement':'collapsible-secondary-'+$row['id']+''},
								'',
								this)
						});
					}
				)
				
				$this.functions.push(
					function(){			
						$('#btn-'+$this.uniqueId+'-primary-edit').on('click',function(){
							CommonFunc2Btn({
							'extended_action':'get',
							'form_type':'edit',
							'return-action':'nestable-list',
							'module':$data['child_module'],
							'id':$data['id']},
							'',this,
							{
								'return':'collapsible-list',
								'parent_id':$data['parent_id'],
								'parent_module':$row['parent_module'],
								'child_module':$row['child_module'],
								'__list_type__':'secondary'
							});
						});
					}
				)
			
					
				$row['name'] = (strlen($row['name']) > 60) ? substr($row['name'],0,55).'...' : $row['name'];
				$return['newrow'] = 
				'\
				<li class="dd-item panel panel-default sortable-item-secondary" data-id="'+$row['id']+'" id="collapsible-secondary-'+$row['id']+'" data-parent="'+$row['parent_id']+'">\
					<div class="panel-heading" >\
						<h4 class="panel-title">\
						<span class="dd-handle dd-handle-secondary"><i class="fa fa-arrows font-white"></i></span>\
						<a class=""  id="collapsible-secondary-label-'+$row['id']+'">\
						 '+$row['name']+'\
						 </a>\
						<span class="" style="">\
						<span class="text-right" style="float:right"><a href="javascript:;" class="btn btn-sm red btnDelete">Delete</a></span>\
						<span class="text-right" style="float:right" ><a href="javascript:;" class="btn btn-sm yellow">Edit</a></span>\
\
						</span>\
						</h4>\
					</div>\
					<div id="collapse_sec_'+$row['id']+'_'+$row['i']+'" class="panel-collapse collapse">\
						<div class="panel-body">\
							<p></p>\
						</div>\
					</div>\
				</li>\
				';                                      
				$return['jsFunction'] += "$('#dd-list-${row['parent_id']}').append(value.newrow)";
			}    
		}
			

		return $return;    
		
	}
	this.construct(options);
)