<?php 

$client_name = 'XYZ Corp';
$insert_id = '987654';
$data['company_name'] = 'Blackale';
$data['address'] = '123 sdfg';
$data['city'] = 'Delhi';
$data['zip'] = '122001';
$data['phone'] = '9999578451';
$data['date'] = date('Y-m-d H:i:s');
$data['product_name'] = 'Quarterly Term Plan';
$data['price'] = '5000';
$data['price'] = '5000';
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
            .title{
                text-align:left;
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

    <img src="header.png" style="width:100%">
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
        <div style="text-align: center;font-size:20pt"><img src="notice.jpg" style="height:400px"></div>

        <br />
     ';


    $table1 = '
    <table class="two-tables" width="100%" style="font-size: 9pt; border-collapse: collapse;padding:0;" cellpadding="4">
        <tbody>
            <!-- ITEMS HERE -->
                    <tr>
                        <td class=""><span class="title">Subtotal</span>: <span class="value">DASfsadfs asdf</span></td>
                    </tr>
                    <tr>
                        <td class=""><span class="title">Subtotal</span>: <span class="value">DASfsadfs asdf</span></td>
                    </tr>
                    <tr>
                        <td class=""><span class="title">Subtotal</span>: <span class="value">DASfsadfs asdf</span></td>
                    </tr>
                </tbody>
            </table>';  

    $table2 = '
    <table  class="two-tables" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="4">
        <tbody>
            <!-- ITEMS HERE -->
                <tr>
                    <td class=""><span class="title">Subtotal</span>: <span class="value">DASfsadfs asdf</span></td>
                </tr>
                <tr>
                    <td class=""><span class="title">Subtotal</span>: <span class="value">DASfsadfs asdf</span></td>
                </tr>
                <tr>
                    <td class=""><span class="title">Subtotal</span>: <span class="value">DASfsadfs asdf</span></td>
                </tr>
            </tbody>
        </table>';  

    $html .= '
    <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; margin:0px" >

        <tbody width="100%">
            <!-- ITEMS HERE -->
                    <tr>   
                        <td style="padding:0px" width="50%">
                        '.$table1.'
                        </td>  
                        <td style="padding:0px" width="50%">
                        '.$table2.'
                        </td>
                    </tr>
                    </tbody>
                </table>
            </body>
    
        </html>
    ';