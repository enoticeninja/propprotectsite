<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function sendEmailCommonPhpMailer($data)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.enoticeninja.com/lawyer/send_email.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$dataPost = array();
	$dataPost['to'] = $data['email'];
	$dataPost['subject'] = $data['subject'];
	$body = getHtmlBody($data);
	$dataPost['message'] = $body;
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataPost));
	$server_output = curl_exec($ch);
	curl_close($ch);
	/* if ($server_output == "OK") {
	} else {
	} */
}

function getHtmlBody($data)
{
	$template = $data['template'];
	$templateName = '';
	if ($template == 'reset-password') {
		$templateName = 'ForgotPasswordEmail.html';
	} else if ($template == 'notification') {
		if (!empty($data['notice_data'])) $data['table'] = createNoticeTable($data['notice_data']);
		else $data['table'] = '';
		unset($data['notice_data']);
		$templateName = 'NotificationEmail.html';
	} else if ($template == 'reminder') {
		$templateName = 'ReminderEmail.html';
	} else if ($template == 'verification') {
		$templateName = 'VerificationEmail.html';
	} else if ($template == 'old') {
		$templateName = 'old.html';
	} else if ($template == 'general') {
		$templateName = 'general.html';
	}
	$body = '';

	$templateFile = __DIR__ . '/email_templates/' . $templateName;
	if (!empty($templateName)) {
		$body = file_get_contents($templateFile);
		if (isset($data['notice_data'])) unset($data['notice_data']);
		if (isset($data)) {
			foreach ($data as $k => $v) {
				$body = str_replace('{' . strtoupper($k) . '}', $v, $body);
			}
		}
	}
	$timestamp = time();
	//file_put_contents($timestamp.'.html', $body);
	return $body;
}

function createNoticeTable($data)
{

	$html = '';
	$newData = array();
	if (empty($data)) return $html;
	$labels = getNoticeLabels();
	foreach ($data as $key => $value) {
		if (!empty($value)) {
			if (isset($labels[$key])) $newData[$labels[$key]] = $value;
		}
	}
	$trs = '';
	$i = 1;
	foreach ($newData as $key => $value) {
		$css = 'background-color:#e8e8e8';
		if ($i % 2 == 0) {
			$css = '';
		}
		$trs .= '
			<tr style="' . $css . '">
				<td class="td-name">
					' . $key . '
				</td>
				<td class="td-value" >' . $value . '</td>
			</tr>
		';
		$i++;
	}
	if ($trs != '') {
		$html = '
		<table class="notice-details-table"  align="center" border="0" cellpadding="0" cellspacing="0" width="100%" >
			<tbody>
			' . $trs . '                                       
			</tbody>
		</table>';
	}
	return $html;
}

function getNoticeLabels()
{
	$arr = array(
		'notice_id' => 'Notice Id',
		'user_id' => 'User Id',
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
?>