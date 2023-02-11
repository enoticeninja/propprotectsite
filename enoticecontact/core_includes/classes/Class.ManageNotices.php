<?php
class ManageNotices extends Ajax
{
    /*Do not edit after this line*/
    public $dbTable = 'tbl_notice';
    public $common_title = 'Notices';
    public $userData = array();
    public $userID = '';
    public $session = [];
    public $config = array(
        'id' => 'id',
        'name' => 'id',
    );
    public $form_configuration = array();
    public $module = '';

    public function __construct($dbConn = '')
    {
        if ($dbConn != '') {
            $this->dbConn = $dbConn;
        }
        $this->module = $this->dbTable;
        $this->setFunctionMappings();
    }

    public function setFunctionMappings()
    {
        $this->functionMappings['new'] = 'getNewForm';
        $this->functionMappings['new_ajax'] = 'getNewAjaxForm';
        $this->functionMappings['edit'] = 'getEditForm';
        $this->actionMappings = array_flip($this->functionMappings);
        return $this->functionMappings;
    }
    public function getDataForApi()
    {
        $id = $_REQUEST['id'];
        //print_r($date);
        $sql = "SELECT * FROM `$this->dbTable` WHERE notice_id='$id' LIMIT 1";
        $query = $this->query($sql);
        $allRows = array();
        $num_rows = mysqli_num_rows($query);
        if($num_rows < 1){
            return 'Not Found';
        }
        $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
        //print_r(mysqli_error($this->dbConn));
        unset($row['servey_hissa_number']);
        unset($row['cts_hissa_number']);
        unset($row['gat_hissa_number']);
        unset($row['notify_property_id']);
        unset($row['source']);
        unset($row['user_id']);
        $pdf_url = $this->generatePdfNotice($row);
        $row['pdf_url'] = $pdf_url;
        //$row['image_test'] = "".SITE_PATH."header.png";
        return $row;
    }
    public function generatePdfNotice($data)
    {
        $path = DIR_ROOT;
        require_once $path . '/vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);
		$mpdf->showImageErrors = false;
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Notice");
        $mpdf->SetAuthor("Nano");
        $mpdf->SetWatermarkText("eNotice Ninja");
        $mpdf->showWatermarkText = true;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');
        $html = $this->getPdfTemplate($data);
        $mpdf->WriteHTML($html);
        $mpdf->Output(DIR_ROOT . 'invoices/' . $data['notice_id'] . '.pdf', 'F');
        $pdf_url = SITE_PATH.'invoices/'.$data['notice_id'].'.pdf';
        return $pdf_url;
    }
    function getPdfTemplate($data)
    {
        $html = '<html>
        <head>
            <style>
                body {
                    font-family: sans-serif;
                    font-size: 10pt;
                }
    
                p {
                    margin: 0pt;
                }
    
                table.items {
                    border: 0.1mm solid #000000;
                }
    
                td {
                    vertical-align: top;
                }
    
                .items td {
                    border-left: 0.1mm solid #000000;
                    border-right: 0.1mm solid #000000;
                }
    
                .two-tables  {
                    text-align: center;
                    border: 0.1mm solid #000000;
                    font-variant: small-caps;
                }
                .two-tables td{
                    border-bottom: 0.1mm solid #000000;
                }
				.name-value-td{
					text-align:center;
					margin:20px;
					border-bottom:1px solid #ccc;
				}
                .title{
                    text-align:left;
					font-weight:bold;
                }
                .value{
                    text-align:right;
                }
    
                .header-image{
                    
                }
            </style>
        </head>
    
        <body>
    
            <!--mpdf
        <htmlpageheader name="myheader">
			<img src="'.SITE_PATH.'header.png" style="width:100%">
        </htmlpageheader>
    
        <htmlpagefooter name="myfooter">
        <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 1mm; ">
        Page {PAGENO} of {nb}
        </div>
        </htmlpagefooter>
    
        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myfooter" value="on" />
        mpdf-->
    
            <div style="text-align: center;font-size:20pt">Notice</div>
            <div style="text-align: center;font-size:20pt"><img src="'.$data['image'].'" style="height:400px"></div>
    
            <br />
         ';
        unset($data['image']);
        unset($data['notice_id']);
        unset($data['society_name_value']);
        unset($data['notify_property_id']);
        unset($data['notify']);
        $newData = array();
        $labels = $this->getLabels();
        
        foreach($data as $key=>$value){
            if(!empty($value)){
                if(isset($labels[$key]))$newData[$labels[$key]] = $value;
            }
        }
        list($array1, $array2) = array_chunk($newData, ceil(count($newData) / 2),true);
        //print_r($array1);
        $trHtml1 = '';
        foreach($newData as $key1=>$value1){
            $trHtml1 .=
            '
            <tr>
                <td class="name-value-td"><span class="title">'.$key1.'</span>:</td><td class="name-value-td"> <span class="value">'.$value1.'</span></td>
            </tr>            
            ';
        }

        $table1 = '
        <table class="two-tables" width="100%" style="font-size: 9pt; border-collapse: collapse;padding:0;" cellpadding="4">
            <tbody>
                <!-- ITEMS HERE -->
                '.$trHtml1.'
            </tbody>
        </table>';  
    
 
    
        $html .= '
        <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; margin:0px" >
    
            <tbody width="100%">
                <!-- ITEMS HERE -->
                         
                            '.$trHtml1.'
                        
                        </tbody>
                    </table>
                </body>
        
            </html>
        ';
        return $html;
    }

    public function getLabels(){
        $arr = array (
            'notice_id' => 'Notice Id',
            'user_id' => 'User Id',
            'date' => 'Date',
            'type_notice' => 'Type Notice',
            'state' => 'State',
            'city' => 'City',
            'taluka' => 'Taluka',
            'village' => 'Village',
            'property_type' => 'Property Type',
            'area_name' => 'Area Name',
            'survey_number' => 'Survey Number',
            'glr_survey_number' => 'Glr Survey Number',
            'hissa_number' => 'Hissa Number',
            'gat_number' => 'Gat Number',
            'cts_number' => 'Cts Number',
            'zonenumber' => 'Zonenumber',
            'sector_no' => 'Sector No',
            'propertynumber' => 'Propertynumber',
            'tp_number' => 'Tp Number',
            'fp_number' => 'Fp Number',
            'plot_number' => 'Plot Number',
            'unit_no' => 'Unit No',
            'floor_no' => 'Floor No',
            'block_number' => 'Block Number',
            'bulding_number' => 'Bulding Number',
            'bulding_name' => 'Bulding Name',
            'wing_number' => 'Wing Number',
            'society_name' => 'Society Name',
            'publisher_profile' => 'Publisher Profile',
            'publisher_name' => 'Publisher Name',
            'publisher_contact' => 'Publisher Contact',
            'owner_name' => 'Owner Name',
            'newspaper' => 'Newspaper',
            'newspaper_edition' => 'Newspaper Edition',
            'notice_date' => 'Notice Date',
            'others' => 'Others',
            'image' => 'Image',
            'remark' => 'Remark',
            'source' => 'Source',
            'notify' => 'Notify',
            'servey_hissa_number' => 'Servey Hissa Number',
            'cts_hissa_number' => 'Cts Hissa Number',
            'gat_hissa_number' => 'Gat Hissa Number',
            'notify_property_id' => 'Notify Property Id',
        );
        return $arr;
    }
}
