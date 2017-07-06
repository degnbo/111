<?php
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
vendor('tcpdf.tcpdf');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'logo_example.jpg';
		$this->Image($image_file, 10, 10, 15, '15', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('droidsansfallback', 'B',20);
		//$this->SetFont('helvetica', 'B', 20);
		// Title
		$this->Cell(0, 15, '<< 界拓自由潜水日志 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');

$pdf->SetTitle('自由潜水pdf');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font

//$pdf->SetFont('times', 'BI', 12);
$pdf->SetFont('droidsansfallback', 'B',12);

// add a page
$pdf->AddPage();
//$img_file = 'images/image_demo.jpg';
//PDF输出的方式。I，在浏览器中打开；D，以文件形式下载；
//F，保存到服务器中；S，以字符串形式输出；E：以邮件的附件输出。
//$pdf->Image($img_file,30, 40,150, 150, '', '', '', false, 300, '', false, false, 0);
$time=date("Y年m月d日",time());
$rq=get_xqtime($time);
// set some text to print
$txt = <<<EOD
<div>
<div style="text-align:center">{$time} {$rq}</div>

<div>
	<img src="{$data['a1']}"  width="300px" height="200px"/>
	<img src="{$data['a2']}"  width="300px" height="200px"/>
	<img src="{$data['a3']}"  width="300px" height="200px"/>
</div>
<table border="1" cellpadding="1" cellspacing="0" >
    <tr>
        <th style="height:50px;text-align:center">最大深度<br>Deep Dive</th>
        <th style="text-align:center">下潜时间(秒)DESCENT<br>{$maxdowntime}</th>
        <th style="text-align:center">下潜速度(米/秒)DESCENT SPEED<br>{$maxdownspeed}</th>
    </tr>
    <tr style="height:30px">
        <th style="text-align:center">深度(米)DEPTH<br>{$maxdepth}</th>
        <th style="text-align:center">下潜时长(秒)<br>{$maxtotal_time}</th>
        <th style="text-align:center">上升速率(米/秒)ASCENT SPEED<br>{$maxupspeed}</th>
    </tr>
</table>
<table  border="1" cellpadding="1" cellspacing="0">
 <tr style="text-align:center">
        <th >顺序<br>NO</th>
        <th>深度<br>DEPTH</th>
        <th>下潜时间<br>DESCENT T</th>
        <th>上潜时间<br>ASCENT T</th>
        <th>总时长<br>TOTAL T</th>
        <th>上潜速率<br>ASCENT S</th>
        <th>下潜速率<br>DESCENT S</th>
    </tr>
EOD;
foreach($llist as $k=>$v){
	$html.="<tr><td>".$v['sort_num']."</td><td>".$v['depth']."</td><td>".$v['downtime']."</td>".
	"<td>".$v['uptime']."</td><td>".$v['total_time']."</td> <td>".$v['upspeed']."</td> <td>".$v['downspeed']."</td></tr>";
}

$jw="</table></div>";

$txt.=$html.$jw;
// print a block of text using Write()
//$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
$pdf->writeHTMLCell(0, 0, '', '', $txt, 0, 1, 0, true, '', true);

// ---------------------------------------------------------
$filename=$_SERVER['DOCUMENT_ROOT'];
//dump($_SERVER);
//echo $filename;die;
$filename=$filename."Public/pdf/example_".$id.".pdf";


//Close and output PDF document
$result=$pdf->Output($filename, 'F');//die;

//============================================================+
// END OF FILE
//============================================================+
