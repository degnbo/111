<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/17
 * Time: 16:42
 */

class Controller_Export extends Controller {

    function action_index(){
        $sql = "select a.jkorderno,a.ctrq FROM order_mains a LEFT JOIN order_frms b ON a.id=b.orderid where a.`status` in(5,7) and a.ctrq like '".date('Y-m-d', strtotime('-1 days'))."' and b.cid not like 'ctr%' and b.cid not like 'ali%' and a.kdprice>0 ORDER BY a.jkorderno";
       //echo $sql;die;
        $data = Model::factory('Common')->get_sql($sql);
//        var_dump($data);
        Tool::load('vendor/PHPExcel/Classes/PHPExcel');
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("ctos")
            ->setLastModifiedBy("ctos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        // set width
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        // �����и߶�
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);

        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

        // �������ʽ
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        // ����ˮƽ����
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //  �ϲ�
        $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');

        // ��ͷ
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '����Ʊ����')
            ->setCellValue('A2', '����������');

        // ����
        for ($i = 0, $len = count($data); $i < $len; $i++) {
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $data[$i]['jkorderno']);
//            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $data[$i]['']);
//            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $data[$i]['jkorderno']);
//            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $data[$i]['createdate']);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':D' . ($i + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':D' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('����Ʊ����');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // ���
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . date('Y-m-d',strtotime('-1 days')).'����Ʊ����' . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    function action_daoru(){
        header("Content-type: text/html; charset=utf8");
        Tool::load('vendor\PHPExcel\Classes\PHPExcel');
        $filePath = 'file/a.xls';

        $PHPExcel = new PHPExcel();

        /**Ĭ����excel2007��ȡexcel������ʽ���ԣ�����֮ǰ�İ汾���ж�ȡ*/
        $PHPReader = new PHPExcel_Reader_Excel2007();
        if(!$PHPReader->canRead($filePath)){
            $PHPReader = new PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($filePath)){
                echo 'no Excel';
                return ;
            }
        }

        $PHPExcel = $PHPReader->load($filePath);
        /**��ȡexcel�ļ��еĵ�һ��������*/
        $currentSheet = $PHPExcel->getSheet(0);
        /**ȡ�������к�*/
        $allColumn = $currentSheet->getHighestColumn();
        /**ȡ��һ���ж�����*/
        $allRow = $currentSheet->getHighestRow();
        /**�ӵڶ��п�ʼ�������Ϊexcel���е�һ��Ϊ����*/
        for($currentRow = 2;$currentRow <= $allRow;$currentRow++){
            /**�ӵ�A�п�ʼ���*/
            for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){
                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()���ַ�תΪʮ������*/
                    echo  $val."\t";
            }
            echo "</br>";
        }
        echo "\n";
    }
    function action_test(){
        $a = 'aaabbbccc';
//        echo substr($a,0,3);//aaa ������Ϊ0�Ľ�ȡ����ȡ����Ϊ3
//        echo substr($a,-3,3);//ccc ��-3��ʼ��ȡ ��ȡ����Ϊ3
//        echo str_replace('c','1',$a,$num),$num; $num���滻�Ĵ���
//        echo $num;
//        var_dump(explode('a', $a)); //������Ķ�ǰ����Ϊ�պ�һ��Ϊbbbccc

//        echo metaphone('money'); //MN

//        echo levenshtein('zhangsan','zhangsi'); // 2

//        echo str_repeat('aaa,',2); // aaa,aaa,

        echo date('Y-m-d H:i:s',strtotime('-1 H'));

    }
}