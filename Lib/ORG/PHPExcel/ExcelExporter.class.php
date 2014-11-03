<?php
/**
 * Excel reader based on PHPExcel
 * @author leo
 */
class ExcelExporter
{
	/**
	 * export cell config
	 * @var array
	 */
	protected $_config;
	
	/**
	 * current sheet
	 * @var obj
	 */
	protected $_actSheet;
	
	/**
	 * writer
	 * @var obj
	 */
	protected $_writer;
	
	/**
	 * show type config in $_config
	 * @var array
	 */
	protected $_show_type = array('txt','col','fun','vars','image');
	
	/**
	 * current row index
	 * @var int
	 */
	protected $_cur_row = 1;
	
	/**
	 * alignment
	 * @var Array
	 */
	protected $_alignment = array(
		'setHorizontal' => array(
			'left' => 'PHPExcel_Style_Alignment::HORIZONTAL_LEFT',
			'center' => 'PHPExcel_Style_Alignment::HORIZONTAL_CENTER',
			'right' => 'PHPExcel_Style_Alignment::HORIZONTAL_RIGHT',
			'justify' => 'PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY'
		),
		'setVertical' => array(
			'top' => 'PHPExcel_Style_Alignment::VERTICAL_TOP',
			'center' => 'PHPExcel_Style_Alignment::VERTICAL_CENTER',
			'bottom' => 'PHPExcel_Style_Alignment::VERTICAL_BOTTOM',
			'justify' => 'PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY'
		)
	);
	
	public function __construct($config)
	{
		$this->_config = $config;
		$this->_init();
	}

	/**
	 * initialize export setting
	 */
	protected function _init()
	{
		Vendor('PHPExcel.PHPExcel');
		Vendor('PHPExcel.Writer.Excel5');
		Vendor('PHPExcel.Worksheet.Drawing');
		Vendor('PHPExcel.Style.Alignment');
		
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
		
		$objPHPExcel = new PHPExcel();
		$this->_writer = new PHPExcel_Writer_Excel5($objPHPExcel);
		
		$objPHPExcel->setActiveSheetIndex(0);
		$this->_actSheet = $objPHPExcel->getActiveSheet();
	}
	
	protected function header($filename)
	{
		ob_end_clean();//清除缓冲区,避免乱码
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		
		//针对不同的浏览器，采用不同的filename策略，消除火狐空格问题
	    //IE下载,中文文件名时乱码问题
        if (preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])) {//IE浏览器
            header('Content-Disposition: attachment; filename="' . str_replace("+", "%20", urlencode($filename)) . '"');
        } else if (preg_match("/Firefox/", $_SERVER["HTTP_USER_AGENT"])) {  //firefox浏览器
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
        } else {   //谷歌及非主流浏览器
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
		
		header("Content-Transfer-Encoding:binary");
	}
	
	protected function render()
	{
		$this->_writer->save('php://output');
	} 
	
	/**
	 * 
	 * export sheet with fixed format
	 * @param array $data
	 */
	public function sheet($data,$filename)
	{
		$this->header($filename);
	
		$this->sheet_setting();
		
		foreach($this->_config['cells'] as $k => $cell)
		{
			if(empty($cell['pos'])) continue;
			
			$this->style_setup($cell);
			
			$str = $this->cell_display($data, $cell);
			
			$this->_actSheet->setCellValueExplicit($cell['pos'], $str , PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		$this->render();
	}
	
	/**
	 * export sheet with data list(one table)
	 */
	public function rows_sheet($rows, $filename,$filters = array())
	{
		$this->header($filename);
	
		$params['rows_sum'] = count($rows);
		$this->sheet_setting($params);

		if(isset($this->_config['table_count']))
		{
			for($i=1;$i<=$this->_config['table_count'];$i++)
			{
				$table_key = 'cells'.$i;
				$table_cells = $this->_config[$table_key];
				$table_filter = $fiters[$table_key];
				$table_rows = $rows[$table_key];
				
				$this->generate_rows($table_cells, $table_rows, $table_filter);
			}
		}
		else
		{
			$this->generate_rows($this->_config['cells'], $rows, $filters);
		}
		
		$this->render();
	}
	
	/**
	 * generate table rows
	 * @param array $cells
	 * @param array $rows
	 * @param array $filters
	 */
	protected function generate_rows($config_cells, $rows, $filters = array())
	{
		// write table head and collection content cells
		$collect_cells = array();
		$i = 'A';
		$offset = array(); // control offset 
		$offset_counter = 0;

		// first line of table head
		foreach($config_cells[0] as $k => $cell)
		{
			if(empty($cell['txt']) OR (!empty($filters) AND in_array($k, $filters))) continue;
			
			$pos = $i.$this->_cur_row;
			if($cell['is_parent'] AND $offset_counter > 0)
			{
				$offset[$k] = $offset_counter;
				$offset_counter = 0;
			}
			else if( ! $cell['is_parent'])
			{
				$offset_counter += 1; 
			}

			$this->style_setup($cell,$pos);
			
			$this->_actSheet->setCellValueExplicit($pos, $cell['txt'], PHPExcel_Cell_DataType::TYPE_STRING);		
			
			if( ! $cell['is_parent']) $collect_cells[$k] = $cell;
			else $collect_cells[$k] = array();
			
			$offset_i = isset($cell['merge_cells']) ? $cell['merge_cells'][0] : 1;
			if(isset($cell['cell_width'])){
				$this->_actSheet->getColumnDimension($i)->setWidth($cell['cell_width']);
			}
			$this->increase_col($i, $offset_i);
		}
		! empty($config_cells[0]) AND $this->_cur_row++;
		

		// second line of table head
		$i = 'A';
		foreach($config_cells[1] as $k => $cell)
		{
			if(empty($cell['txt']) OR (!empty($filters) AND in_array($cell['parent'], $filters))) continue;
			
			if(!empty($cell['parent']) AND array_key_exists($cell['parent'], $offset))
			{
				$this->increase_col($i, $offset[$cell['parent']]);
				unset($offset[$cell['parent']]);
			}			
			$pos = $i.$this->_cur_row;

			
			$this->style_setup($cell,$pos);
			
			$this->_actSheet->setCellValueExplicit($pos, $cell['txt'], PHPExcel_Cell_DataType::TYPE_STRING);

			if(!empty($cell['parent']))
			{
				$collect_cells[$cell['parent']][] = $cell;
			}
			
			$i++;
		}
		! empty($config_cells[1]) AND $this->_cur_row++;
		
		// make cells
		$cells = array();
		foreach ($collect_cells as $cell)
		{
			if(!array_key_exists('show_type', $cell))
			{
				foreach($cell as $c)
				{
					array_push($cells, $c);
				}
			}
			else 
			{
				array_push($cells, $cell);
			}
		}
		
		// generate contents
		foreach ($rows as $k => $data)
		{
			$c = 'A';
			foreach ($cells as $cell)
			{
				$pos = $c.$this->_cur_row;
				$data['row_index'] = $k+1;
				$str = $this->cell_display($data, $cell,$pos);
				
				//$this->draw_border($pos);
				
				// draw cell bgcolor
				if( isset($cell['cell_bgcolor']) )
				{
					@eval('$fun='.$cell['cell_bgcolor']);
					$argb = $fun($data);
					if( ! empty($argb))
					{
						$this->draw_bgcolor($pos, $argb);
					}
				}

				// cell data type
				if( isset($cell['data_type']) )
				{
					@eval('$fun='.$cell['data_type']);
					$data_type = $fun($data);
					if( ! empty($data_type))
					{
						if($data_type == 1)	// percentage
						{	
							if($str == '') // invalid
							{
								$this->_actSheet->setCellValueExplicit($pos, $str , PHPExcel_Cell_DataType::TYPE_STRING);
							}
							else
							{
								$objStyle = $this->_actSheet->getStyle($pos);
								$objStyle->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_0);
								$this->_actSheet->setCellValueExplicit($pos, $str , PHPExcel_Cell_DataType::TYPE_NUMERIC); 
							}
						}
						elseif($data_type == 2)	// number
						{
							if($str == '') // no value
							{
								$this->_actSheet->setCellValueExplicit($pos, $str , PHPExcel_Cell_DataType::TYPE_STRING);
							}
							else
							{
								$objStyle = $this->_actSheet->getStyle($pos);
								$objStyle->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
								$this->_actSheet->setCellValueExplicit($pos, $str , PHPExcel_Cell_DataType::TYPE_NUMERIC);
							}					
						}
						else // string
						{
							$this->_actSheet->setCellValueExplicit($pos, $str , PHPExcel_Cell_DataType::TYPE_STRING);
						}
					}
				}
				else
				{
					$this->_actSheet->setCellValueExplicit($pos, $str , PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				
				$c++;
			}
			$this->_cur_row++;
		}

		//Log::write(__LINE__.': '.date('Y-m-d H:i:s',time()).' Mem: '.memory_get_usage());

		return $this;
	}
	
	protected  function sheet_setting($params = null)
	{
		// set width
		if(is_array($this->_config['cell_width']))
		{
			$cell_width = $this->_config['cell_width'];
			//for($i=$cell_width[0];$i<=$cell_width[1];$i++)
			for($i=$cell_width[0],$len_end = strlen($cell_width[1]);$len_i=strlen($i),$len_i <$len_end || ($len_i==$len_end && $i<=$cell_width[1]); $i++)//防止 (A,BH)的BUG
			{
				$this->_actSheet->getColumnDimension($i)->setWidth($cell_width[2]);
			}
		}
		
		if(!empty($this->_config['row_height']) AND !empty($params['rows_sum']))
		{
			for($i=3;$i<=$params['rows_sum']+2;$i++)
			{
				$this->_actSheet->getRowDimension($i)->setRowHeight($this->_config['row_height']);
			}
		}
	}
	
	/**
	 * display style of current cell
	 * @param array $cell
	 */
	protected function style_setup($cell, $cell_pos = null)
	{
		if(empty($cell_pos))
		{
			$cell_pos = $cell['pos'];
		}
		if(empty($cell_pos)) return;

		// display border and merge
		$merge_cells = $cell['merge_cells'];
		if(isset($merge_cells) AND count($merge_cells) == 2)
		{
			// format like : merge_cells => array(1,2); 
			if(is_numeric($merge_cells[0]) AND is_numeric($merge_cells[1]))
			{
				$merge_start = $merge_end = $cell_pos;
				
				preg_match('/([A-Za-z]+)(\d+)/', $cell_pos,$start);
				$col = $start[1];
				for($i=1;$i<=$merge_cells[0];$i++,$col++)
				{
					$row = $start[2];
					for($j=1;$j<=$merge_cells[1];$j++,$row++)
					{
						$pos = $col.$row;
						$this->draw_border($pos);
						
						// merge end cell
						if($i==$merge_cells[0] AND $j==$merge_cells[1])
						{
							$merge_end = $pos;
						}
					}
				}
			}
			else 
			{
				$merge_start = $merge_cells[0];
				$merge_end = $merge_cells[1];
				
				preg_match('/([A-Za-z]+)(\d+)/', $merge_cells[0],$start);
				preg_match('/([A-Za-z]+)(\d+)/', $merge_cells[1],$end);
				
				for($i=$start[2];$i<=$end[2];$i++)
				{
					for($j=$start[1];$j<=$end[1];$j++)
					{
						$pos = $j.$i;
						$this->draw_border($pos);
					}
				}
			}
			// merge cells
			$this->_actSheet->mergeCells("$merge_start:$merge_end");
		}
		else
		{
			$this->draw_border($cell_pos);
		}
	
		
		$objStyle = $this->_actSheet->getStyle($cell_pos);
		
		// display font
		$font = $cell['font_style'];
		if(!empty($font) AND !empty($this->_config['font']) AND array_key_exists($font, $this->_config['font']))
		{
			$font_style = $this->_config['font'][$font];
			$objFont = $objStyle->getFont();
			foreach($font_style as $k => $v)
			{
				$objFont->{$k}($v);
			}
		}
		
		// display background color
		if(!empty($cell['background_color']))
		{
			$objFill = $objStyle->getFill();  
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
			$objFill->getStartColor()->setARGB($cell['background_color']);
		}
		
		// display align
		if(!empty($cell['align']) OR !empty($this->_config['align']))
		{
			$align = !empty($cell['align']) ? $cell['align'] : $this->_config['align'];
			$objAlign = $objStyle->getAlignment(); 
			foreach($align as $k => $v)
			{
				eval('$align='.$this->_alignment[$k][$v].';');
				$objAlign->{$k}($align);
			}
		}
		
		// set width & height
		if(!empty($cell['height']))
		{
			preg_match('/(\d)/', $cell_pos,$m);
			$row = (int)$m[1];
			$this->_actSheet->getRowDimension($row)->setRowHeight($cell['height']);
		} 
		if(!empty($cell['width']))
		{
			preg_match('/([A-Za-z]+)/', $cell_pos,$m);
			$this->_actSheet->getColumnDimension($m[1])->setWidth($cell['width']);
		}
	}
	
	/**
	 * draw border
	 * @param $pos
	 */
	protected function draw_border($pos)
	{
		if(empty($pos)) return;
		
		$objStyle = $this->_actSheet->getStyle($pos);
		
 		$objBorder = $objStyle->getBorders();  
		$objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		$objBorder->getTop()->getColor()->setARGB('FF000000');
		$objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
		$objBorder->getBottom()->getColor()->setARGB('FF000000');  
		$objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		$objBorder->getLeft()->getColor()->setARGB('FF000000'); 
		$objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);   
		$objBorder->getRight()->getColor()->setARGB('FF000000');
	}
	
	/**
	 * draw background color
	 * @param $pos
	 * @param @argb
	 */
	protected function draw_bgcolor($pos,$argb)
	{
		if(empty($pos) AND empty($argb)) return;
		
		$objStyle = $this->_actSheet->getStyle($pos);
		$objFill = $objStyle->getFill();  
		$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
		$objFill->getStartColor()->setARGB($argb);
 
	}
	
	/**
	 * general text of cell
	 * @param Array $data
	 * @param Array $cell
	 */
	public function cell_display($data,$cell, $cell_pos = null)
	{
		if(!in_array($cell['show_type'], $this->_show_type))
		{
			return 'Error:'.$cell['show_type'];
		}
		
		if(empty($cell_pos))
		{
			$cell_pos = $cell['pos'];
		}		
		
		$str = '';
		switch ($cell['show_type'])
		{
			case 'col':
					$str = $data[$cell['col']];
					break;
			case 'txt':
					$str = $cell['txt'];
					break;				
			case 'fun':
					@eval('$fun='.$cell['fun']);
					$str = $fun($data);
					break;
			case 'vars':
					if(array_key_exists($cell['var_key'], $this->_config['vars']))
					{
						$str = $this->_config['vars'][$cell['var_key']][$data['review_type']];
					}
					break;
			case 'image':
					if( ! empty($cell_pos))
					{
						@eval('$fun='.$cell['fun']);
						$src = $fun($data);
						if(!empty($src) AND file_exists($src))
						{
							$objDrawing = new PHPExcel_Worksheet_Drawing();  
							$objDrawing->setPath($src);  
							$objDrawing->setOffsetX(5); 
							$objDrawing->setOffsetY(5);
							$objDrawing->setCoordinates($cell_pos);  
							$objDrawing->setResizeProportional(false);// turn off resize auto
							
							if(is_array($cell['setting']))
							{
								foreach ($cell['setting'] as $k=>$v)
								{
									$objDrawing->{$k}($v);
								}
							}
							
							$objDrawing->setWorksheet($this->_actSheet); 
							unset($objDrawing);
						}
					}
					break;
		}		
		$str == null AND $str = '';
		unset($cell);
		
		return $str;
	}
	/**
	 * generate column index with offset
	 * @param Char $col
	 * @param Int $offset
	 */
	protected function increase_col(& $col, $offset)
	{
		for($i=0;$i<$offset;$i++)
		{
			$col++;
		}
	}
}