<?php if ( !('BASEPATH')) exit('No direct <span id="g4x6410v0710_6" class="g4x6410v0710">script access</span> allowed');

require_once APPPATH.'third_party/tcpdf/tcpdf'.EXT;

class Pdf extends TCPDF
{
	# Controller name
	protected $ctr = '';

	#Format:
		#default: 0
		#custom : 1
	protected $format 			= 'default';
	protected $title 			= "";
	protected $fileName 		= "file.pdf";
	protected $numberingConfig 	= array('width' => 30,'headerWidth' => 30,'align' => 'center');
	protected $rowConfig 		= array();
	protected $records 			= array();
	protected $useNumbering		= true;
	protected $MAX_ITEMS		= 15;

	public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false)
	{
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->SetMargins(PDF_MARGIN_LEFT, '28', PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$this->SetFont('helvetica', '', 10);
		$this->setPrintHeader(false);
		$this->setPrintFooter(false);
    }

	public function set_ctr($_param)
	{
		# Set controller name
		$this->ctr = $_param;
	}
	
	public function set_header($_param)
	{
		# Page header
	}

	public function set_footer($_param)
	{
		# Page footer
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

	public function setFileName($fileName){
		$this->fileName = $fileName;
	}

	public function getFileName(){
		return $this->fileName;
	}

	public function setRecords($records){
		$this->records = $records;
	}

	public function getRecords(){
		return $this->records;
	}

	public function setMaxItems($maxItems){
		$this->MAX_ITEMS = $maxItems;
	}

	public function getMaxItems(){
		return $this->records;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getTitle(){
		return $this->title;
	}

	public function useNumbering($useNumbering=TRUE){
		$this->useNumbering = $useNumbering;
	}

	public function setNumberingConfig($numberingConfig){
		$this->numberingConfig = $numberingConfig;
	}

	public function printDefaultTemplateHeader(&$txt){
		//PRINT ROW HEADER TO PDF
		$txt .= '<tr>';
		//PRINT NUMBERING HEADER TO PDF
		if($this->useNumbering && sizeof($this->numberingConfig)>0):
			$txt .= '<th'
				. (  isset($this->numberingConfig['headerWidth']) 	? (' width="'.$this->numberingConfig['headerWidth'].'"') : ''  )
				. (  isset($this->numberingConfig['headerHeight']) 	? (' height="'.$this->numberingConfig['headerHeight'].'"') : ''  )
				. (  isset($this->numberingConfig['headerAlign']) 	? (' align="'.$this->numberingConfig['headerAlign'].'"') : ''  );
			if( isset($this->numberingConfig['headerFontSize']) || isset($this->numberingConfig['headerBorder']) || isset($this->numberingConfig['headerBorderLeft']) || isset($this->numberingConfig['headerBorderTop']) || isset($this->numberingConfig['headerBorderBottom']) || isset($this->numberingConfig['headerBorderRight']) ):
			$txt .= ' style="'
				. (  isset($this->numberingConfig['headerFontSize']) 		? (' font-size:'			.$this->numberingConfig['headerFontSize']			.';') : ''  )
				. (  isset($this->numberingConfig['headerBorder']) 			? (' border:'				.$this->numberingConfig['headerBorder']				.';') : ''  )
				. (  isset($this->numberingConfig['headerBorderLeft']) 		? (' border-left:'			.$this->numberingConfig['headerBorderLeft']			.';') : ''  )
				. (  isset($this->numberingConfig['headerBorderTop']) 		? (' border-top:'			.$this->numberingConfig['headerBorderTop']			.';') : ''  )
				. (  isset($this->numberingConfig['headerBorderBottom']) 	? (' border-bottom:'		.$this->numberingConfig['headerBorderBottom']		.';') : ''  )
				. (  isset($this->numberingConfig['headerBorderRight']) 	? (' border-right:'			.$this->numberingConfig['headerBorderRight']		.';') : ''  );
				//default header border
				if( !isset($this->numberingConfig['headerBorder']) && !isset($this->numberingConfig['headerBorderLeft']) && !isset($this->numberingConfig['headerBorderTop']) && !isset($this->numberingConfig['headerBorderBottom']) && !isset($this->numberingConfig['headerBorderRight']) ):
				$txt .= (' border: 1px solid black;');
				endif;
			$txt .= '"';
			endif;

			//default header border
			if( !isset($this->numberingConfig['headerFontSize']) && !isset($this->numberingConfig['headerBorder']) && !isset($this->numberingConfig['headerBorderLeft']) && !isset($this->numberingConfig['headerBorderTop']) && !isset($this->numberingConfig['headerBorderBottom']) && !isset($this->numberingConfig['headerBorderRight']) ):
			$txt .= ' style="border: 1px solid black;"';
			endif;
			

			$txt .= '>'.(  (isset($this->numberingConfig['header']) && strlen($this->numberingConfig['header'])>0)  ?$this->numberingConfig['header']:'No.').'</th>';
		endif;
		//PRINT COLUMN HEADER TO PDF
		foreach ($this->rowConfig as $col_idx => $col) {
			$txt .= '<th'
				. (  isset($col['headerWidth']) 	? (' width="'.$col['headerWidth'].'"') : ''  )
				. (  isset($col['headerHeight']) 	? (' height="'.$col['headerHeight'].'"') : ''  )
				. (  isset($col['headerAlign']) 	? (' align="'.$col['headerAlign'].'"') : ''  );
			if( isset($col['headerFontSize']) || isset($col['headerBorder']) || isset($col['headerBorderLeft']) || isset($col['headerBorderTop']) || isset($col['headerBorderBottom']) || isset($col['headerBorderRight']) ):
			$txt .= ' style="'
				. (  isset($col['headerFontSize']) 		? (' font-size:'			.$col['headerFontSize']			.';') : ''  )
				. (  isset($col['headerBorder']) 			? (' border:'				.$col['headerBorder']				.';') : ''  )
				. (  isset($col['headerBorderLeft']) 		? (' border-left:'			.$col['headerBorderLeft']			.';') : ''  )
				. (  isset($col['headerBorderTop']) 		? (' border-top:'			.$col['headerBorderTop']			.';') : ''  )
				. (  isset($col['headerBorderBottom']) 	? (' border-bottom:'		.$col['headerBorderBottom']		.';') : ''  )
				. (  isset($col['headerBorderRight']) 	? (' border-right:'			.$col['headerBorderRight']		.';') : ''  );
				//default header border
				if( !isset($col['headerBorder']) && !isset($col['headerBorderLeft']) && !isset($col['headerBorderTop']) && !isset($col['headerBorderBottom']) && !isset($col['headerBorderRight']) ):
				$txt .= (' border: 1px solid black;');
				endif;
			$txt .= '"';
			endif;
			
			//default header border
			if( !isset($col['headerFontSize']) && !isset($col['headerBorder']) && !isset($col['headerBorderLeft']) && !isset($col['headerBorderTop']) && !isset($col['headerBorderBottom']) && !isset($col['headerBorderRight']) ):
			$txt .= ' style="border: 1px solid black;"';
			endif;

			$txt .= '>'.$col['header'].'</th>';
		}
		$txt .= '</tr>';
	}

	public function createPage()
	{
		$this->setHeaderData("kcj_logo.png", 28, 'PT. Commuter Line Jabodetabek (KCJ)', 'Jl. Ir. H. Djuanda I, RT. 8 / RW. 1, Pasar Baru, Sawah Besar, RT.8/RW.1, Ps. Baru, Sawah Besar, Kota Jakarta Pusat, DKI Jakarta 10120');
		$this->setHeaderFont(array('', 'Italic', 16));
		$this->AddPage();

		$txt = '';
		// PRINT TITLE TO PDF
		if(strlen($this->title)>0):
			$txt  .= '<h1 align="center"><b>'.$this->title.'</b></h1>';
			$txt  .= '<br><br><br>';
		endif;

		$totalPage = ceil(sizeof($this->records)/$this->MAX_ITEMS);
		for ($page=1;$page<=$totalPage;$page++):
			if($page>1): $this->AddPage(); endif;
		//START PRINT TABLE TO PDF
		$txt .= '<table width="100%" cellpadding="6">';

		//PRINT ROW HEADERS
		$this->printDefaultTemplateHeader($txt);

		//PRINT RECORDS TO PDF
		for($idx=0;$idx<$this->MAX_ITEMS and ((($page-1)*$this->MAX_ITEMS)+$idx) < sizeof($this->records);$idx++):
			$itemIndex = ($idx+(($page-1)*$this->MAX_ITEMS));
			$itemNumbering = $itemIndex+1;
			$rec = $this->records[$itemIndex];

			$txt .= '<tr>';
			if($this->useNumbering):
				$txt .= '<td';

				if(sizeof($this->numberingConfig)>0):
				$txt .=	  (  isset($this->numberingConfig['width']) ? (' width="'.$this->numberingConfig['width'].'"') : ''  )
						. (  isset($this->numberingConfig['height']) ? (' height="'.$this->numberingConfig['height'].'"') : ''  )
						. (  isset($this->numberingConfig['align']) ? (' align="'.$this->numberingConfig['align'].'"') : ''  );

				if( isset($this->numberingConfig['fontSize']) || isset($this->numberingConfig['border']) || isset($this->numberingConfig['borderLeft']) || isset($this->numberingConfig['borderTop']) || isset($this->numberingConfig['borderBottom']) || isset($this->numberingConfig['borderRight']) ):
				$txt .= ' style="'
					. (  isset($this->numberingConfig['fontSize']) 			? (' font-size:'		.$this->numberingConfig['fontSize']			.';') : ''  )
					. (  isset($this->numberingConfig['border']) 			? (' border:'			.$this->numberingConfig['border']			.';') : ''  )
					. (  isset($this->numberingConfig['borderLeft']) 		? (' border-left:'		.$this->numberingConfig['borderLeft']		.';') : ''  )
					. (  isset($this->numberingConfig['borderTop']) 		? (' border-top:'		.$this->numberingConfig['borderTop']		.';') : ''  )
					. (  isset($this->numberingConfig['borderRight']) 		? (' border-right:'		.$this->numberingConfig['borderRight']		.';') : ''  );
					//default row numbering border
					if( !isset($col['border']) && !isset($col['borderLeft']) && !isset($col['borderTop']) && !isset($col['borderBottom']) && !isset($col['borderRight']) ):
					$txt .= (' border-left: 1px solid black;border-right: 1px solid black;');
						if( ( ( ($idx+1)%$this->MAX_ITEMS) == 0 ) ):
						$txt .= (' border-bottom: 1px solid black;');
						endif;
					endif;
				$txt .= '"';
				endif;

				//default row numbering border
				if( !isset($this->numberingConfig['fontSize']) && !isset($col['border']) && !isset($col['borderLeft']) && !isset($col['borderTop']) && !isset($col['borderBottom']) && !isset($col['borderRight']) ):
				$txt .= (' style="border-left: 1px solid black;border-right: 1px solid black;');
					if( ( ( ($idx+1)%$this->MAX_ITEMS) == 0 ) ): //LAST ITEM IN PAGE
					$txt .= (' border-bottom: 1px solid black;');
					endif;
				$txt .= '"';
				endif;
				endif; //ENDIF sizeof($this->numberingConfig)>0
				$txt .= '>'.$itemNumbering.'</td>';
			endif;
			foreach ($this->rowConfig as $col_idx => $col) {
				$txt .= '<td'
					. (  isset($col['width']) 	? (' width="'.$col['width'].'"') : ''  )
					. (  isset($col['height']) 	? (' height="'.$col['height'].'"') : ''  )
					. (  isset($col['align']) 	? (' align="'.$col['align'].'"') : ''  );
				if( isset($col['fontSize']) || isset($col['border']) || isset($col['borderLeft']) || isset($col['borderTop']) || isset($col['borderBottom']) || isset($col['borderRight']) ):
				$txt .= ' style="'
					. (  isset($col['fontSize']) 		? (' font-size:'			.$col['fontSize']			.';') : ''  )
					. (  isset($col['border']) 			? (' border:'				.$col['border']				.';') : ''  )
					. (  isset($col['borderLeft']) 		? (' border-left:'			.$col['borderLeft']			.';') : ''  )
					. (  isset($col['borderTop']) 		? (' border-top:'			.$col['borderTop']			.';') : ''  )
					. (  isset($col['borderBottom']) 	? (' border-bottom:'		.$col['borderBottom']		.';') : ''  )
					. (  isset($col['borderRight']) 	? (' border-right:'			.$col['borderRight']		.';') : ''  );
					//default row border
					if( !isset($col['border']) && !isset($col['borderLeft']) && !isset($col['borderTop']) && !isset($col['borderBottom']) && !isset($col['borderRight']) ):
						if(!$this->useNumbering):
						$txt .= (' border-left: 1px solid black;border-right: 1px solid black;');
						else:
						$txt .= (' border-right: 1px solid black;');
						endif;

						if( ( ( ($idx+1)%$this->MAX_ITEMS) == 0 ) ):
						$txt .= (' border-bottom: 1px solid black;');
						endif;
					endif;
				$txt .= '"';
				endif;
				
				//default row border
				if( !isset($col['fontSize']) && !isset($col['border']) && !isset($col['borderLeft']) && !isset($col['borderTop']) && !isset($col['borderBottom']) && !isset($col['borderRight']) ):
					if(!$this->useNumbering):
					$txt .= (' style="border-left: 1px solid black;border-right: 1px solid black;');
					else:
					$txt .= (' style="border-right: 1px solid black;');
					endif;

					if( ( ( ($idx+1)%$this->MAX_ITEMS) == 0 ) ):
					$txt .= (' border-bottom: 1px solid black;');
					endif;
					$txt .= '"';
				endif;

				$txt .= '>'.$rec->$col['dataindex'].'</td>';
			}
			$txt .= '</tr>';

		endfor; //ENDFOR ITEMS

		//EMPTY ROWS FILLER
		while($idx<$this->MAX_ITEMS){
			$txt .= '<tr>';
			if($this->useNumbering):
				$txt .= '<td';

				if(sizeof($this->numberingConfig)>0):
				$txt .=	  (  isset($this->numberingConfig['width']) ? (' width="'.$this->numberingConfig['width'].'"') : ''  )
						. (  isset($this->numberingConfig['height']) ? (' height="'.$this->numberingConfig['height'].'"') : ''  )
						. (  isset($this->numberingConfig['align']) ? (' align="'.$this->numberingConfig['align'].'"') : ''  );

				if( isset($this->numberingConfig['fontSize']) || isset($this->numberingConfig['border']) || isset($this->numberingConfig['borderLeft']) || isset($this->numberingConfig['borderTop']) || isset($this->numberingConfig['borderBottom']) || isset($this->numberingConfig['borderRight']) ):
				$txt .= ' style="'
					. (  isset($this->numberingConfig['fontSize']) 			? (' font-size:'		.$this->numberingConfig['fontSize']			.';') : ''  )
					. (  isset($this->numberingConfig['border']) 			? (' border:'			.$this->numberingConfig['border']			.';') : ''  )
					. (  isset($this->numberingConfig['borderLeft']) 		? (' border-left:'		.$this->numberingConfig['borderLeft']		.';') : ''  )
					. (  isset($this->numberingConfig['borderTop']) 		? (' border-top:'		.$this->numberingConfig['borderTop']		.';') : ''  )
					. (  isset($this->numberingConfig['borderRight']) 		? (' border-right:'		.$this->numberingConfig['borderRight']		.';') : ''  );
					//default row numbering border
					if( !isset($col['border']) && !isset($col['borderLeft']) && !isset($col['borderTop']) && !isset($col['borderBottom']) && !isset($col['borderRight']) ):
					$txt .= (' border-left: 1px solid black;border-right: 1px solid black;');
						if( ( ( ($idx+1)%$this->MAX_ITEMS) == 0 ) ):
						$txt .= (' border-bottom: 1px solid black;');
						endif;
					endif;
				$txt .= '"';
				endif;

				//default row numbering border
				if( !isset($this->numberingConfig['fontSize']) && !isset($col['border']) && !isset($col['borderLeft']) && !isset($col['borderTop']) && !isset($col['borderBottom']) && !isset($col['borderRight']) ):
				$txt .= (' style="border-left: 1px solid black;border-right: 1px solid black;');
					if( ( ( ($idx+1)%$this->MAX_ITEMS) == 0 ) && ( $itemNumbering==sizeof($this->records) ) ): //LAST ITEM IN PAGE
					$txt .= (' border-bottom: 1px solid black;');
					endif;
				$txt .= '"';
				endif;
				endif; //ENDIF sizeof($this->numberingConfig)>0
				$txt .= '></td>';
			endif;
			foreach ($this->rowConfig as $col_idx => $col) {
				$txt .= '<td'
					. (  isset($col['width']) 	? (' width="'.$col['width'].'"') : ''  )
					. (  isset($col['height']) 	? (' height="'.$col['height'].'"') : ''  )
					. (  isset($col['align']) 	? (' align="'.$col['align'].'"') : ''  );
				if( isset($col['fontSize']) || isset($col['border']) || isset($col['borderLeft']) || isset($col['borderTop']) || isset($col['borderBottom']) || isset($col['borderRight']) ):
				$txt .= ' style="'
					. (  isset($col['fontSize']) 		? (' font-size:'			.$col['fontSize']			.';') : ''  )
					. (  isset($col['border']) 			? (' border:'				.$col['border']				.';') : ''  )
					. (  isset($col['borderLeft']) 		? (' border-left:'			.$col['borderLeft']			.';') : ''  )
					. (  isset($col['borderTop']) 		? (' border-top:'			.$col['borderTop']			.';') : ''  )
					. (  isset($col['borderBottom']) 	? (' border-bottom:'		.$col['borderBottom']		.';') : ''  )
					. (  isset($col['borderRight']) 	? (' border-right:'			.$col['borderRight']		.';') : ''  );
					//default row border
					if( !isset($col['border']) && !isset($col['borderLeft']) && !isset($col['borderTop']) && !isset($col['borderBottom']) && !isset($col['borderRight']) ):
						if(!$this->useNumbering):
						$txt .= (' border-left: 1px solid black;border-right: 1px solid black;');
						else:
						$txt .= (' border-right: 1px solid black;');
						endif;

						if( ( ( ($idx+1)%$this->MAX_ITEMS) == 0 ) ):
						$txt .= (' border-bottom: 1px solid black;');
						endif;
					endif;
				$txt .= '"';
				endif;
				
				//default row border
				if( !isset($col['fontSize']) && !isset($col['border']) && !isset($col['borderLeft']) && !isset($col['borderTop']) && !isset($col['borderBottom']) && !isset($col['borderRight']) ):
					if(!$this->useNumbering):
					$txt .= (' style="border-left: 1px solid black;border-right: 1px solid black;');
					else:
					$txt .= (' style="border-right: 1px solid black;');
					endif;

					if( ( ( ($idx+1)%$this->MAX_ITEMS) == 0 ) ):
					$txt .= (' border-bottom: 1px solid black;');
					endif;
					$txt .= '"';
				endif;

				$txt .= '></td>';
			}
			$txt .= '</tr>';
			$idx++;
		}

		//FINISH PRINT TABLE TO PDF
		$txt .= '</table>';
		$this->writeHTML($txt, true, false, false, false, '');
		$txt = '';
		endfor; //ENDFOR PAGE
		
		$this->Output($this->fileName, 'I');
	}
	
}