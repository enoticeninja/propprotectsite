<?php
include_once("bootstrap.php");
include_once("common.php");
$module = '';
include_once("RouterX.php");
$title = 'Categories | Quick Manage';
$page_group = 'ProductGroup';
$__page_active = 'NewPage';
$__sub_active = 'ProductGroup';
$previous_page_link = '';
$previous_page = '';
$jsFunction = '';
$tools = array();
$cat_module = 'products';
$jsFunction = 
"
GetNestedTreeCommon({'module':'products','tree_parent':'get-all-post','product_id':0});
";
?>
<?php 
/* if(isset($_REQUEST['tree_parent'])){
    include_once DIR_SITE_ROOT.'themes/default/demo/stree_ajax_data.php';
} */
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<?php include ''.$tpl_path.'tplHead.php' ?>
<link href="<?php echo get_core_theme_path() ?>assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
<style>

.action-buttons{
	position:absolute;
	top:10px;
	right:20px;
}

.img100{
	width:100px;
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
<!-- END HEAD -->

<body class="page-md page-header-fixed has-material-sidebar">

<!-- BEGIN HEADER -->
<?php include $tpl_path.'tplHeader.php' ?>
<!-- END HEADER -->
<div class="clearfix">
</div>

<!-- BEGIN CONTAINER -->
<div class="page-container">
<?php include $tpl_path.'tplMaterialSidebar.php' ?>
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">

			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
			
                        <div class="col-md-12">
                            <div class="portlet card light tasks-widget bordered">
                                <div class="portlet-title">
                                    <div class="caption font-green">
                                        <i class="fa fa-cogs"></i>Product Category  </div>
                                    <div class="actions">
                                        <a href="javascript:;" class="btn green" id="expand-all"> Collapse All</a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row text-center" style="padding:30px">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="input-group">
                                                <div class="input-icon">
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
                        </div>


                        
            </div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->

</div>
<!-- END CONTAINER -->

<?php include ''.$tpl_path.'tplFooter.php' ?>

<?php include ''.$tpl_path.'tplFooterJs.php' ?>
<script src="<?php echo get_core_theme_path() ?>assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script>

var cat_module = '<?php echo $cat_module ?>';
var checked_cat;
function GetNestedTree(){
    startLoading();
    $.ajax({
        type:'POST',
        url: '<?php echo $site_path ?>CategoryRouterX',
        data: {'tree_parent':'get-all-post','product_id':0},
        success: function(val){		
            var value = JSON.parse(val);
            //console.log(value);			
            t(value.json);
            checked_cat = value.checked;
            
            stopLoading();
        }
    });	
}
function GetNestedTreeCommon(data){
    startLoading();
    $.ajax({
        type:'POST',
        url: '<?php echo $site_path ?>CategoryRouterX',
        data: data,
        success: function(val){		
            var value = JSON.parse(val);
            //console.log(value);			
            t(value.json);
            checked_cat = value.checked;
            
            stopLoading();
        }
    });	
}

function customMenu($node) {
    // The default set of all items
	var items =  {
		"Create": {
			"separator_before": false,
			"separator_after": false,
			"label": "Create New Sub Category",
			"action": function (obj) { 
				$node = tree.jstree('create_node', $node);
				tree.jstree('edit', $node);
			}
		},
		"Rename": {
			"separator_before": false,
			"separator_after": false,
			"label": "Rename Category",
			"action": function (obj) { 
				tree.jstree('edit', $node);
			}
		},                         
		"Remove": {
			"separator_before": false,
			"separator_after": false,
			"label": "Remove Category",
			"action": function (obj) { 
				tree.jstree('delete_node', $node);
			}
		},                         
		"Attributes": {
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
				GetForm('edit-form',$node.data.id,'ProductCategory','modalForm');
				CommonFunc2({'action':'edit-form','id':$node.data.id,'module':'ProductCategory','modalForm':'modalForm'},'','');
			}
		}
	};

    if ($node.data.id == 1) {
        // Delete the "delete" menu item
        delete items.Rename;
        delete items.Remove;
        delete items.Edit;
        delete items.Attributes;
    }

    return items;
}
var tree;
var checked_nodes = [];
var is_new_node_created = false;
var new_node_id;
var t = function(datta) {
    tree = $("#tree_cat").jstree({
    plugins: ["contextmenu","wholerow","checkbox","dnd","types","conditionalselect","search"], 
    "contextmenu":{         
        "items": customMenu
    },    
    core: {
        themes: {
            responsive: !1         
        },
        check_callback: !0,
        data: datta
    },
    checkbox: {       
      three_state : false, // to avoid that fact that checking a node also check others
      whole_node : false,  // to avoid checking the box just clicking the node 
      tie_selection : false // for checking without selecting and selecting without checking
    },
    //conditionalselect : function (node) { return this.is_leaf(node); },        
    types: {
        "default": {
            icon: "fa fa-folder icon-state-warning icon-lg"
        },
        file: {
            icon: "fa fa-file icon-state-warning icon-lg"
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

.on("check_node.jstree uncheck_node.jstree", function(e, data) {
    //console.log(data);
    //console.log(data.node.id + ' ' + data.node.text + (data.node.state.checked ? ' CHECKED': ' NOT CHECKED')); 
    console.log(data.node.data.text);    
})
.on('create_node.jstree', function (e, data) {
        console.log(data);
        console.log(data.node.id);
        is_new_node_created = true;
        new_node_id = data.node.id;
    }) 
.on('rename_node.jstree', function (e, data) {
      //data.text is the new name:
    console.log(data);
    console.log(data.node.id);

    startLoading();
    if(is_new_node_created && data.node.id == new_node_id){
        $.ajax({
            type:'POST',
            url: 'CategoryRouterX',
            data: {'module':cat_module,'tree_parent':'new-node','parent':data.node.parent,'name':data.node.text},
            success: function(val){	
                console.log(val);            
                var value = JSON.parse(val);
                //console.log(value);			
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
                url: 'CategoryRouterX',
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
    
                     
    }) 
.on("delete_node.jstree", function(e, data) {
    startLoading();
     console.log(data.node.id);
    $.ajax({
        type:'POST',
        url: 'CategoryRouterX',
        data: {'module':cat_module,'tree_parent':'delete-node','id':data.node.id},
        success: function(val){	
            console.log(val);            
            var value = JSON.parse(val);
            stopLoading();
        }
    }); 
})    
.on("move_node.jstree", function (e, data) {
    console.log(data);
    console.log(data.old_parent);
    startLoading();
    $.ajax({
        type:'POST',
        url: 'CategoryRouterX',
        data: {'module':cat_module,'tree_parent':'move-node','id':data.node.id,'old_parent':data.old_parent,'new_parent':data.parent},
        success: function(val){	           
            try {
                var value = JSON.parse(val);
            }
            catch(err) {
                console.log(val);
                stopLoading();
            }
             console.log(value.sql); 
            //$("#tree_cat").jstree('destroy');
            //$("#tree_cat").html('');
            //GetNestedTree();                 
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

function RepeatForm(holder,module){
    $('#'+holder+'').append(toolsAll[module]['repeat']);
}


function FetchRepeatForm(module){
	startLoading(); 
		$.ajax({
			type: "POST",
			url: "",
			data: {'type':'repeat-form','module':module},
			success: function(val){
                console.log(val);
				var value = JSON.parse(val);			
				toolsAll[value.tools.parent]['repeat'] = value.html;
                console.log(toolsAll);
                stopLoading();			
			}
           
		});		
 stopLoading();

}

function Save(data,form){
	startLoading(); 
    if(data.hasOwnProperty('module')){
        tools = toolsAll[data.module];
    }
    var isReady = true; 
    var isValidated = true; 
    if (tools.hasOwnProperty('validation')) {    
        $.each(tools['validation'],function(tField,tOptions){
            var tVal = $('#'+tField+'').val();
         
            vCheck = ValidateFields(tVal,tOptions)
        
            if(vCheck.result === false){
                showErrorOnElement(tField,vCheck.message);
                isValidated = false;
            }        
        });
    }

    if (tools.hasOwnProperty('required')) {
        isReady = CheckRequiredAssoc(tools['required']);
    }

	if(isReadyCommon && isReady && isValidated) {	
		//var formData = $('#'+form+'').serializeArray();	
        if(typeof form !== 'undefined'){
            var formData = $('#'+form+'').serializeArray();
            if(typeof data !== 'undefined' && ($.isPlainObject(data))){
                console.log($.isPlainObject(data));
                $.each(data,function(n,v){
                    formData.push({name:n, value:v});
                });
            }       
        }
        else{
            formData = data;
        } 
        console.log(formData);
        console.log(data);
			$.ajax({
			type:'POST',
			url: '',
			data: formData,
			success: function(val){
                console.log(val);
				var value = JSON.parse(val);
                console.log(value);
				if(value.debug){
					console.log(val);					
				}	
				if(value.ajaxresult == true) {
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}
					else 
					{
						showMessage('success','Added', 'Success');							
					}
				}
				else {
					showMessage('error','Could Not Save', 'Error!');
					if (value.hasOwnProperty('jsFunction')) {
						 eval(value.jsFunction);
					}                    
				}
				stopLoading();				
			}
		});	

	}
	else {
		
		stopLoading();
	
	}
}

function Update(data,form){
	//calledFrom is where function is called from
	startLoading();
    var isReady = true; 
    var isValidated = true; 
    if (tools.hasOwnProperty('validation')) {    
        $.each(tools['validation'],function(tField,tOptions){
            var tVal = $('#'+tField+'').val();
            vCheck = ValidateFields(tVal,tOptions)

            if(vCheck.result === false){
                showErrorOnElement(tField,vCheck.message);
                isValidated = false;
            }        
        });
    }
   
    if (tools.hasOwnProperty('required')) {
        isReady = CheckRequiredAssoc(tools['required']);
    }  
    
	if(isReady && isValidated) {	
		//var formData = $('#'+form+'').serializeArray();
        if(typeof form !== 'undefined'){
            var formData = $('#'+form+'').serializeArray();
            if(typeof data !== 'undefined' && !$.isEmptyObject(data)){
                $.each(data,function(n,v){
                    formData.push({name:n, value:v});
                });
            }       
        }
        else{
            formData = data;
        }        
        //console.log(formData);
			$.ajax({
			type:'POST',
			url: '',
			data: formData,
			success: function(val){
				var value = JSON.parse(val);
				if(value.debug){
					console.log(val);					
				}
				//console.log(value);			
                if(value.ajaxresult == true) {
                    if (value.hasOwnProperty('jsFunction')) {
                         eval(value.jsFunction);
                    }
                    showSweetSuccess('Success','Updated Succesfully');
                    stopLoading();    
                }
                else {
                    showSweetError('Error','Could Not Update Details!!!');
                    stopLoading();      
                }
				stopLoading();				
			}
		});	

	}
	else {
		stopLoading();			
	}
}
	
</script>
</body>
<!-- END BODY -->
</html>