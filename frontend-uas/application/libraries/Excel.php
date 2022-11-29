<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php"; 
 
class Excel extends PHPExcel {

	protected $workSheet = NULL;
	protected $pageSetup = NULL;
	protected $title = "";
	protected $fileName = "";
	protected $records = array();
    protected $rowConfig = array();

    public function __construct($orientation='P', $format='A4')
	{
        parent::__construct();
        $this->workSheet = $this->getActiveSheet();
        $this->pageSetup = $this->workSheet->getPageSetup();
        $orientation=='P'?($this->pageSetup->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT))
        	:($orientation=='L'?$this->pageSetup->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE):$this->pageSetup->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT));
        $format=='A4'?($this->pageSetup->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4))
        	:($format=='Letter'?($this->pageSetup->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER)):$this->pageSetup->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4));
    }

    public function setRecords($records){
    	$this->records = $records;
    }

    public function getRecords(){
    	return $this->records;
    }

    public function setTitle($title){
    	$this->title = $title;
    }

    public function getTitle(){
    	return $this->title;
    }

    public function setFileName($fileName){
    	$this->fileName = $fileName;
    }

    public function getFileName(){
    	return $this->fileName;
    }

    public function prepareRowConfig($_data_config){
		$prepared = array();
		foreach (json_decode(urldecode($_data_config)) as $key => $value) {
			$prepared[] = array(
				'header'  	=> $value[0],
				'dataindex' => $value[1]
			);
            if($prepared[$key]['header']=='Modified By' || $prepared[$key]['header']=='Modified Date' || $prepared[$key]['header']=='Created By' || $prepared[$key]['header']=='Created Date'){unset($prepared[$key]);};
			if(isset($value['width'])) $prepared[$key] = intval($value['width']);
		}

		return $prepared;
	}

	public function setRowConfig($rowConfig){
		$this->rowConfig = $rowConfig;
	}

	public function getRowConfig(){
		return $this->rowConfig;
	}

    private function inccol($str, $inc){
        for ($i=0; $i < $inc; $i++) $str++;

        return $str;
    }

    public function createPage(){
    	$startRow = 2;
    	$activeSheetIndex = 0;

    	if(strlen($this->title)>0) $this->workSheet->setTitle($this->title);
    	
    	$this->workSheet->setCellValue('A1');

    	$asciiA = 'A';
        // $this->workSheet->mergeCells( ('A1:'.(chr($asciiA + sizeof($this->rowConfig))).'1' ) ); // Punya vicki
    	$this->workSheet->mergeCells( ('A1:'.($this->inccol($asciiA, sizeof($this->rowConfig))).'1' ) );
    	$this->workSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		if(strlen($this->title)>0) $this->workSheet->setCellValue('A1', $this->title);

		$row = $startRow;
		$this->workSheet->setCellValue('A'.$startRow, 'No.');

    	foreach ($this->rowConfig as $col_idx => $col) {
    		$this->workSheet->setCellValue( ($this->inccol($asciiA, $col_idx+1).$row), $col['header'] );
    	}

    	$row++;

    	foreach ($this->records as $row_idx => $rec) {
	    		$this->workSheet->setCellValue( ('A'.$row), ($row_idx+1) );
    		foreach ($this->rowConfig as $col_idx => $col) {
	    		$this->workSheet->setCellValueExplicit( ($this->inccol($asciiA, $col_idx+1).$row), $rec->{$col['dataindex']} );
    		}
    		$row++;
    	}

        PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
        foreach(range('A',( $this->inccol($asciiA, sizeof($this->rowConfig)) )) as $columnID) {
            $this->workSheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

    	header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->fileName.'"');
		header('Cache-Control: max-age=0');
					 
		$objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel2007');  
		$objWriter->save('php://output');
    }

}