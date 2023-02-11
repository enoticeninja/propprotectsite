<?php
include_once(DIR_SITE_ROOT."bootstrap.php");
include_once(DIR_SITE_ROOT."common.php");
$module = '';
include_once("Adapter.php");
$title = 'Chain | Manage Site Chain';
$previous_page_link = '';
$previous_page = '';
$jsFunction = '';
$tools = array();
$cat_module = 'posts';
$cat_module = get_current_url_array(1);
$jsFunction = 
"
GetNestedTreeCommon({'module':'chain','action':'get-all-nested-data'},'');
";

?>
<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
<?php include_once $tpl_path.'tplHead.php'; ?>
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
<style>

.action-buttons{
	position:absolute;
	top:10px;
	right:20px;
}

.img100{
	width:100px;
}
.jstree-ocl{
    width: 20px;
    height: 20px;
}
.jstree-default .jstree-anchor {
    line-height: 36px;
    height: 36px;
}
.jstree-default .jstree-node {
    min-height: 36px;
    line-height: 36px;
    margin-left: 36px;
    min-width: 36px;
}
.jstree-default .jstree-icon:empty, .jstree-default .jstree-icon {
    width: 36px;
    height: 36px;
    line-height: 36px;
    font-size: 30px;
    border-radius:30px;
}
.jstree-anchor span{
	padding:5px;
	margin-left:5px;
}
</style>

<script>
var theme_path = '<?php echo $theme_path ?>';
</script>

<style> 
.jstree li.jstree-open > a.jstree-anchor > i.jstree-checkbox, 
.jstree li.jstree-closed > a.jstree-anchor > i.jstree-checkbox {
    background: rgba(153, 153, 153, 0.21);
    width: 15px;
    height: 15px;
    vertical-align: middle;
    cursor: default; 
    }
.jstree li.jstree-open > a.jstree-anchor, 
.jstree li.jstree-closed > a.jstree-anchor{
    
    }
</style>
</head>
<!-- end::Head -->
    <!-- end::Body -->
<body class="<?php echo get_body_class() ?>">
	<!-- begin:: Page -->
	<div class="m-grid m-grid--hor m-grid--root m-page">
		<?php include_once $tpl_path.'tplHeader7.php'; ?>
	<!-- begin::Body -->
		<?php //include_once $tpl_path.'tplMaterialSideBar.php'; ?>
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
			<?php include_once $tpl_path.'tplSidebar7.php'; ?>
			<div class="m-grid__item m-grid__item--fluid m-wrapper ">
				<!-- BEGIN: Subheader -->
				<div class="m-subheader d-none">
					<div class="d-flex align-items-center">
						<div class="mr-auto">
							<h3 class="m-subheader__title ">
								Dashboard
							</h3>
						</div>
						<div>
							<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
								<span class="m-subheader__daterange-label">
									<span class="m-subheader__daterange-title"></span>
									<span class="m-subheader__daterange-date m--font-brand"></span>
								</span>
								<a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
									<i class="la la-angle-down"></i>
								</a>
							</span>
						</div>
					</div>
				</div>
				<!-- END: Subheader -->
				<div class="m-content">
				<div class="m-portlet">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<span class="m-portlet__head-icon">
									<i class="flaticon-map"></i>
								</span>
								<h3 class="m-portlet__head-text">
									Chain
								</h3>
							</div>
						</div>
						<div class="m-portlet__head-tools">
							<ul class="m-portlet__nav">
								<li class="m-portlet__nav-item">
								<a href="javascript:;" class="btn green" id="expand-all"> Collapse All</a>
								</li>
								<li class="m-portlet__nav-item">
									<a href="" class="m-portlet__nav-link m-portlet__nav-link--icon">
										<i class="la la-refresh"></i>
									</a>
								</li>
								<li class="m-portlet__nav-item">
									<a href="" class="m-portlet__nav-link m-portlet__nav-link--icon">
										<i class="la la-angle-down"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="m-portlet__body">
					<div class="row">
					<div class="col-md-6 col-lg-4 card p-3">
						<div class="input-group">
							<div class="input-icon" style="width: 70%;">
								<i class="fa fa-search fa-fw"></i>
								<input id="tree_cat_search" class="form-control" type="text" name="Search" placeholder="Search"> </div>
							<span class="input-group-btn">
								<button id="" class="btn btn-success" type="button">
									<i class="fa fa-search fa-fw"></i> Search</button>
							</span>
						</div>
					</div>					
					</div>		
					<div class="row">			
					<div id="tree_cat" class="tree-demo col-md-10"> </div>
					</div>
					</div>
				</div>				
					<!--Begin::Section-->
				</div>
			</div>
		</div>			
		<!-- end:: Body -->
		<?php include_once $tpl_path.'tplFooter.php'; ?>
	</div>
	<!-- end:: Page -->
	<?php include_once $tpl_path.'tplQuickSidebar.php'; ?>    

	<?php include_once $tpl_path.'tplQuickNav.php'; ?>
	<?php include_once $tpl_path.'tplFooterJs.php'; ?>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jcrop/js/jquery.Jcrop.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jquery-jeditable/jquery.jeditable.min.js" type="text/javascript"></script>
<script src="<?php echo get_core_theme_path() ?>js/imageCropJs.js?<?php echo time() ?>" type="text/javascript"></script>

<script>

var cat_module = '<?php echo $cat_module ?>';
var checked_cat;
var checked_nodes = [];
var node_being_created = null;
var is_new_node_created = false;
var new_node_id;
var tree;

function formatDataForTree(data){
	$data2 = [];
	var i = 0;
	$.each(data,function(key,$row){
		{
		$temp = {};
		$temp['data'] = {};
		/// If a Parent Node 
		if(+$row['nright'] > +$row['nleft']+1){
			$icon = ' flaticon-user-add bg-glossy-info';
			$children = true;
		}
		/// If a Leaf Node
		else{
			$icon = ' flaticon-profile-1 bg-glossy-warning';
			$children = false;            
		}

		$temp['id'] = "cattree-" + $row['id'];
	   
		//// If Not Root Category set the parent     
		if($row['nleft'] != 1){
			$temp['parent'] = "cattree-" + $row['id_parent'];
		}
		//// If Root Category set the parent to #            
		else if($row['id_parent'] == 0 || $row['id_parent'] == null){
			$temp['parent'] = "#";
		} 

		$state = {};
		// If Root Node open it
		if($row['nleft'] == 1 ){
			$state['opened'] = true;
		}        
		$temp['text'] = '';
		$temp['text'] += '<span class="badge badge-success">Level : '+ (+$row['level_depth']) +'</span> => ';
		$temp['text'] += $row['name'];
		//$temp['text'] += '<span class="bg-less-glossy-success">CID '+$row['customer_id']+'</span>';
		$temp['text'] += ' ::<span class="">Customer Id:  '+$row['customer_id']+'</span>';
		//$temp['text'] += '<span class="bg-less-glossy-info">ID '+$row['id']+'</span>';
		//$temp['text'] += '<span class="bg-less-glossy-error">'+$row['nleft']+'->'+$row['nright']+'</span>';
		if($row['status'] == 0){
			//$temp['text'] += '<span class="bg-less-glossy-warning">PENDING</span>';
		}
		//$temp['text'] = '<span class="bg-less-glossy-pink">'+$row['nleft']+'</span>'+$temp['text']+'<span class="bg-less-glossy-pink">'+$row['nright']+'</span>'
		$temp['icon'] = $icon;
		$temp['state'] = $state;
		$temp['data']['id'] = $row['id'];
		$temp['data']['text'] = $row['name'];
		$data2.push($temp);
		i++;
		}		
	});
	return $data2;
}

function prependTableRow2(data){
	console.log(data);
	var $node = tree.jstree('create_node', node_being_created);
	console.log($node);
	tree.jstree('rename_node', $node, data['name']);
	tree.jstree(true).set_id($node,'cattree-'+data['id']);
	tree.jstree('open_node', node_being_created.id);
	is_new_node_created = false;
	new_node_id = 0;
}

function GetNestedTree(){
    startLoading();
    $.ajax({
        type:'POST',
        url: '<?php echo $site_path ?>CategoryRouterX',
        data: {'module':cat_module,'tree_parent':'get-all-post','product_id':0},
        success: function(val){		
            var value = JSON.parse(val);
            //console.log(value);			
            t(value.json);
            checked_cat = value.checked;
            stopLoading();
        }
    });	
}
function GetNestedTreeCommon(data,url){
	if(typeof url === 'undefined') url = '<?php echo $site_path ?>CategoryRouterX';
	console.log(url);
    startLoading();
    $.ajax({
        type:'POST',
        url: url,
        data: data,
        success: function(val){		
            var value = JSON.parse(val);
            //console.log(value);			
            var formattedData = formatDataForTree(value.json);
			t(formattedData);
            checked_cat = value.checked;
            stopLoading();
        }
    });	
}


var t = function(datta) {
    tree = $("#tree_cat").jstree({
    //plugins: ["contextmenu","wholerow","checkbox","dnd","types","conditionalselect","search"], 
    plugins: ["contextmenu","dnd","types","conditionalselect","search"], 
    "contextmenu":{         
        "items": function($node) {
            return {
                "Create": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Add New Customer To Chain",
                    "action": function (obj) { 
						console.log($node);
						node_being_created = $node;
                        //$node = tree.jstree('create_node', $node);
                        //tree.jstree('edit', $node);
						var data2 = {};
						data2['data'] = {action:'new','tree-parent':$node.id,'module':cat_module,id:$node.id};
						data2['isAjax'] = true;
						data2['e'] = this;
						var chainClass = new FormGenerator(data2);
						chainClass.actionCallBacks.save = 'prependTableRow2';
                    }
                },
/*                  "Rename": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Rename Category",
                    "action": function (obj) { 
                        tree.jstree('edit', $node);
                    }
                }, */                         
                "Remove": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Remove Customer",
                    "action": function (obj) { 
                        tree.jstree('delete_node', $node);
                    }
                },                         
/*                 "Attributes": {
                    "separator_before": true,
                    "separator_after": true,
                    "label": "Feature Attributes",
                    "action": function (obj) {
                        $('#modalForm').modal('show'); 
                        GetForm('new-repeat-form',$node.id,'CategoryFeatures','modalForm');
                    }
                },                      
                "Edit": {
                    "separator_before": true,
                    "separator_after": false,
                    "label": "Edit",
                    "action": function (obj) {
                        $('#modalForm').modal('show'); 
                        //console.log($node);
						var data = {};
						data['data'] = {action:'edit','module':cat_module,id:$node.data.id};
						data['isAjax'] = true;
						data['e'] = this;
						new FormGenerator(data);						
                    }  
                }*/ 
            };
        }
    },    
    core: {
        themes: {
            responsive: !1,
            dots: 1         
        },
        check_callback: !0,
        data: datta
    },
/*     checkbox: {       
      three_state : false, // to avoid that fact that checking a node also check others
      whole_node : false,  // to avoid checking the box just clicking the node 
      tie_selection : false // for checking without selecting and selecting without checking
    }, */
    //conditionalselect : function (node) { return this.is_leaf(node); },        
    types: {
        "default": {
            icon: "fa fa-user icon-state-info icon-lg"
        },
        file: {
            icon: "fa fa-user icon-state-warning icon-lg"
        }
    }    

})
.on('ready.jstree', function (event, data) { 
//console.log(checked_cat);
/* ////Open Only Checked Nodes
        if(!($.isEmptyObject(checked_cat))){
        console.log(checked_cat);
        $.each(checked_cat,function(i,v){
            data.instance._open_to('cattree-'+v+'');
        });            
    } */
    ////Open All Nodes
    $(this).jstree('open_all');
})

/* .on("check_node.jstree uncheck_node.jstree", function(e, data) {
    //console.log(data);
    //console.log(data.node.id + ' ' + data.node.text + (data.node.state.checked ? ' CHECKED': ' NOT CHECKED')); 
    console.log(data.node.data.text);    
}) */
.on('create_node.jstree', function (e, data) {
        console.log(data);
        console.log(data.node.parent);
        is_new_node_created = true;
        new_node_id = data.node.id;

    }) 
.on('rename_node.jstree', function (e, data) {
      //data.text is the new name:
    //console.log(data);
    //console.log(data.node.id);

/*     startLoading();
    if(is_new_node_created && data.node.id == new_node_id){
        $.ajax({
            type:'POST',
            url: site_path+'CategoryRouterX',
            data: {'module':cat_module,'tree_parent':'new-node','parent':data.node.parent,'name':data.node.text},
            success: function(val){	
                console.log(val);            
                var value = JSON.parse(val);
                console.log(value);
                $('#tree_cat').jstree(true).set_id(data.node, 'cattree-'+value.id+'');  
                is_new_node_created = false;
                new_node_id = 0;
                //$("#tree_cat").jstree('destroy');
                //$("#tree_cat").html('');
                //GetNestedTree();                 
                stopLoading();
            }
        });            
    }
    else{
        console.log(data.old);
        console.log(e.target);
        if(data.old == data.text) {
            stopLoading();
            return;
        }
        else{
            $.ajax({
                type:'POST',
                url: site_path+'CategoryRouterX',
                data: {'module':cat_module,'tree_parent':'rename-node','id':data.node.id,'name':data.node.text},
                success: function(val){	
                    console.log(val);            
                    var value = JSON.parse(val);
                    is_new_node_created = false;
                    new_node_id = 0;			  
                    stopLoading();
                }
            });              
        }
           
    }
    
         */             
    }) 
.on("delete_node.jstree", function(e, data) {
    startLoading();
     console.log(data.node.id);
    $.ajax({
        type:'POST',
        url: '',
        data: {'module':cat_module,'action':'delete','id':data.node.id},
        success: function(val){	
            console.log(val);            
            var value = JSON.parse(val);
            stopLoading();
        }
    }); 
})       
.on("move_node.jstree", function (e, data) {
    //console.log(data);
    //console.log(data.old_parent);
    startLoading();
    $.ajax({
        type:'POST',
        url: '',
        data: {'module':cat_module,'action':'move-node','id':data.node.id,'old_parent':data.old_parent,'new_parent':data.parent},
        success: function(val){	           
            try {
                var value = JSON.parse(val);
            }
            catch(err) {
                console.log(val);
                stopLoading();
            }
             //console.log(value.sql); 
            $("#tree_cat").jstree('destroy');
            $("#tree_cat").html('');
            GetNestedTree();                 
            stopLoading();
        }
    }); 
    }); 
}

  var to = false;
  $('#tree_cat_search').keyup(function () {
    if(to) { clearTimeout(to); }
    to = setTimeout(function () {
      var v = $('#tree_cat_search').val();
      $('#tree_cat').jstree(true).search(v);
    }, 250);
  }); 

$('#expand-all').click(function () {
    $treeview = $('#tree_cat');
    if ($('.jstree-open',$treeview).length){
        $treeview.jstree('close_all');
        $(this).text('Expand All');
    }else{
        $treeview.jstree('open_all');
        $(this).text('Collapse All');
    }
    
});  
    
$(document).ready(function(){    
    
});
</script>
<script>
var toolsAll = <?php echo json_encode($tools) ?>;
var tools = [];
var jsFunction = <?php echo json_encode($jsFunction) ?>;
var isReadyCommon = true;
jQuery(document).ready(function() {    
    eval(jsFunction);
    //FetchRepeatForm('Features');

});	
</script>
	</body>
	<!-- end::Body -->
</html>
