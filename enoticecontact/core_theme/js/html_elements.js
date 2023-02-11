    function getModalForm($data,$header_buttons,$ajax){
		
        var $title = ($data['portlet-title'] == '');	
        var $sub_title = isset($data['portlet-sub-title'])? $data['portlet-sub-title']:$title+' Details';
        var $actionMethod = '';
        if(isset($data['form-submit']) && $data['form-submit']){
            $actionMethod = 'method="POST" action="'+$data['form-submit-url']+'"';
        }
        else{
            $actionMethod = 'method="POST" onSubmit="return false;"'; 
        }
        var $headerBtnTemp = '';
        if($header_buttons){
			$headerBtnTemp = 
			'\
			<div class="modal-button-group pull-right">\
			<a href="javascript:;" class="btn btn-circle btn-icon-only fullscreen white" ><i class=" fullscreen-iconn fa fa-expand"></i></a>\
			<a href="javascript:;" class="btn btn-circle btn-icon-only white" onClick="CloseBsModal(\'\',this)"><i class=" fa fa-close"></i></a>\
			</div>\
			';
        }
        var $return = ''; 
        $return += 
                '\
                <div class="modal-dialog" style="width:100%">\
                    <div class="modal-content z-depth-5">\
                        <div class="modal-header" style="padding:0px">\
                        <div class="modal-title fancy-modal-title" style="top: 20px;">\
                            <div class="caption">\
                                <span class="bold uppercase">'+$title+'</span>\
                                \
                            </div>\
                            '+$headerBtnTemp+'\
                        </div>\
                        </div>\
                        <div class="modal-body" id="modal-body" style="padding:20px">\
                            <form role="form" id="'+$data['form-id']+'" '+$actionMethod+'>\
                            '+$data['hidden']+'\
                            <div class="row" style="margin-top: 20px;">\
							'+$data['form']+'\
                            </div>\
                            </form>\
                            <div class="col-md-12 text-center">\
                            '+$data['buttons']+'\
                            </div>\
                             <div class="row text-center" id="'+$data['tools']['parent']+'-popover-result">\
							 </div>\
                        </div>\
                        <div class="modal-footer">\
                        </div>\
                    </div>\
                </div>\
                ';	
        return $return;
    }
