<?php 
include_once(DIR_SITE_ROOT."bootstrap.php");
include_once(DIR_SITE_ROOT."common.php");
require_once DIR_OTHER_CLASSES.'Class.ManageLeads.php'; 
$__page_active = 'NewPage';
$jsFunction = '';
$toolsAll = array();
$module = 'lead';////SHOULD MATCH THE PAGE NAME TO BE REDIRECTED TO AFTER SAVE
include_once('Adapter.php');

$classCustomer = new ManageLeads($db_conx);
$custOptions = $classCustomer->getNewForm(array('return_form_as'=>'form_rows_with_buttons'));
$encodedCustOptions = json_encode($custOptions);
?>
<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->

<head>
    <?php include_once $tpl_path.'tplHead.php'; ?>

</head>
<!-- end::Head -->
<!-- end::Body -->

<body class="<?php echo get_body_class() ?>">
    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <?php include_once $tpl_path.'tplHeader7.php'; ?>
        <!-- begin::Body -->
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
            <?php include_once $tpl_path.'tplSidebar7.php'; ?>
            <div class="m-grid__item m-grid__item--fluid m-wrapper">
                <!-- BEGIN: Subheader -->
                <!-- END: Subheader -->
                <div class="m-content">
                    <!--Begin::Section-->
                    <div class="container">
                        <div class="m-portlet">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon">
                                            <i class="flaticon-user m--font-success"></i>
                                        </span>
                                        <h3 class="m-portlet__head-text m--font-success">
                                            Customer
                                        </h3>

                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="row">
                                    <div class="col-md-12" id="cust-form-container"> </div>
                                    <div class="col-md-12 text-center" id="cust-form-buttons-container"> </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Body -->
        <?php include_once $tpl_path.'tplFooter.php'; ?>
    </div>
    <!-- end:: Page -->
    <?php //include_once $tpl_path.'tplQuickSidebar.php'; ?>

    <?php //include_once $tpl_path.'tplQuickNav.php'; ?>
    <?php include_once $tpl_path.'tplFooterJs.php'; ?>

</body>
<!-- end::Body -->

<script>
var form = $('#table-form');
var tbody = $('#table-tbody');
var status = $('#status-message');
var module = '<?php echo $module ?>';
var __MODULE__ = '<?php echo $module ?>';
var tools = [];
var toolsAll = [];
var toolsAll = <?php echo(json_encode($toolsAll)) ?> ;
//toolsAll = '<?php echo json_encode($toolsAll) ?>;
var jsFunction = <?php echo json_encode($jsFunction) ?> ;
var isReadyCommon = true;
var ajaxCallBacks = {};
var table1, tableOptns;
var select2Data = {};


$(document).ready(function() {
    eval(jsFunction);
    var custOptns = <?php echo($encodedCustOptions) ?> ;

    /* 	custOptns.custom_field_width = {};
    	custOptns.custom_field_width['title'] = '1';
    	custOptns.custom_field_width['firstname'] = '3';
    	custOptns.custom_field_width['lastname'] = '3';
    	custOptns.custom_field_width['email'] = '3';
    	custOptns.custom_field_width['mobile'] = '2';
    	custOptns.custom_field_width['address1'] = '6';
    	custOptns.custom_field_width['address2'] = '6'; */
    var custFormClass = new FormGenerator(custOptns);
    custFormClass.actionCallBacks = {
        'save': 'redirectToEdit',
        'update': 'showCustomerUpadtedToast'
    };
    var custForm = custFormClass.getSingleForm();
    $('#cust-form-container').html(custForm.form);
    custFormClass.executeAllJs();

});

function showCustomerUpadtedToast() {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    toastr.success("Customer Updated Succesfully", "Updated");
}

function redirectToEdit(data) {
    window.location = site_path + '' + module + '/Edit/' + data['data']['id'];
}

function UncheckMaster() {
    $('#table-master-check').prop('checked', true);
    $('#table-master-check').trigger('click');
    $('#table-master-check').closest('.md-checkbox').removeClass('partial-check');
}


$('body').on('keydown change blur', '.monitor-input', function(e) {
    var id = $(e.target).attr('id');
    var parent = $(e.target).data('unique_id');
    if ($(this).hasClass('bs-select')) { ////// IF BOOTSTRAP SELECT
        id = $(this).closest('.bootstrap-select').find('select').attr('id');
        parent = $('#' + id).data('unique_id');
    }
    tools = toolsAll[parent];
    if (typeof tools['is_multiple_form'] !== 'undefined' && tools['is_multiple_form']) {
        var tools_index = $(e.target).attr('data-tools-index');
        tools = tools['tools'][tools_index];
    }
    ///check for required first
    ///check for validation
    ///check for dulpicate
    var is_field_empty = false;
    var is_field_validated = true;
    var not_duplicate = true;
    if (e.type == 'focusout') {
        resetElementState(id);
        if ($(this).is('[field-required]')) {
            var assocArr = [];
            assocArr = tools['required'];
            if ($.trim($(this).val()) === '') {
                is_field_empty = true;
                showErrorOnElement(id, tools['required'][id]['message']);
            }
            //CheckRequiredAssoc(assocArr);
        }
        if ($(this).is('[field-validate]') && !is_field_empty) {
            var elemVal = $(this).val();
            var vOptions = tools['validation'][id];
            vCheck = ValidateFields(elemVal, vOptions, id);
            if (!vCheck['result']) {
                tools['validation'][id]['status'] = false;
                showErrorOnElement(id, vCheck['message']);
                is_field_validated = false;
                //console.log(vCheck);	
            } else {
                tools['validation'][id]['status'] = true;
                showSuccessOnElement(id, vCheck['message']);
                is_field_validated = true;
                //console.log(vCheck);
            }

        }
        if ($(this).is('[field-check-duplicate]') && !is_field_empty && is_field_validated) {

            not_duplicate = CheckForDuplicateModule(tools.module, id);
        }
        if (!is_field_empty && is_field_validated && not_duplicate) {
            resetElementState(id);
        }
    }

});
</script>

</html>