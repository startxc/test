<?php
class AccessoryExportAction extends SpsAccessoryCommonAction
{
	/**
	 * export file name
	 * @var unknown_type
	 */
	protected  $_filename = '';
	
	/**
	 * request array
	 * @var unknown_type
	 */
	protected $_request = array();
	
	/*
	 * Index page
	*/
	public function index()
	{
		$this->_autocomplete();
		$act = $this->_A('Accessory');		
		$map = array(
			'vendors' => D('Vendor')->order('name')->select(),
			'category' => CC('Sps/accessory.category'),
			'filters' => $act->_get_filters()
		);
		
		$this->assign($map);
		$this->display();
	}
	/**
	 * general export filename
	 * @param unknown_type $request
	 */
	protected function _get_filename()
	{
		if(empty($this->_filename))
		{
			$category_list = CC('Sps/accessory.category');
			$category = '';
			$with_price = '';
			$pager = '';
			$request = $this->_get_request();
			if(!empty($request['category_id']))
			{
				$category = '_'.$category_list[$request['category_id']];
			}
			if($request['with_price'])
			{
				$with_price = "_with_Price";
			}
			if(!empty($request['p']))
			{
				$pager = '-'.$request['p'];
			}
			$this->_filename = "Sample_List$category{$with_price}{$pager}.xls";
		}
		
		return $this->_filename;
	}
	public function sample_list()
	{
		set_time_limit(0);
		// general data
		$request = $this->_get_request();
		$cond = $this->_get_cond($request);
		$rows = $this->_get_data($cond);
		//$rows = $this->_sort($data);

		// make filename
		$filename = $this->_get_filename();
		
		if(!class_exists('ExcelExporter')) {
			import('@.BLL.Common.ExcelExporter');
		}
		
		if($request['category_id']==10){
			$export_config = 'Sps/export_accessory_lamp';
		}elseif ($request['category_id']==20){
			$export_config = 'Sps/export_accessory_5pkacc';
		}else{
			$export_config = 'Sps/export_accessory';
		}
		
		$export = new ExcelExporter(CC($export_config));
		
		// wheather with price
		$filters = array();
		if( ! $request['with_price'])
		{
			array_push($filters,'title_china','title_us');
		}
		
		$export->rows_sheet($rows, $filename,$filters);
		exit;
	}	
	
	/**
	 * quotation sheet
	 */
	public function quotation_sheet()
	{			
		$accessory_id = (int)$this->_request('accessory_id');
		$relation = array('price','shipping');
		$cond = array('id'=>$accessory_id,'deleted'=>0);
		$accessory = $this->_model()
			->where($cond)
			->cus_relation($relation)
			->find();

		if(!class_exists('ExcelExporter')) {
			import('@.BLL.Common.ExcelExporter');
		}
		$export = new ExcelExporter(CC('Sps/export_quotation_sheet'));
		$filename = "Quotation_Sheet_Of_".$accessory['sample_no'].".xls";
		
		$export->sheet($accessory, $filename);
	} 
	
	protected function _sort($rows)
	{
		if(empty($rows)) return array();
		
		function my_sort($a,$b)
		{
			$al = strtolower($a["vendor"]['vendor_name']);
	        $bl = strtolower($b["vendor"]['vendor_name']);
	        
	        if ($al == $bl) return 0;
	        
	        return ($al > $bl) ? +1 : -1;
		}
		
		usort($rows, 'my_sort');
		
		return $rows;
	}
	/**
	 * query data
	 * @param unknown_type $cond
	 * @param unknown_type $request
	 */
	protected function _get_data($cond)
	{
		$mod_accessory = $this->_model();
			
		$request = $this->_get_request();

		// check rows of result
		$rows_limit = CC('Sps/config.export_rows_limit_accessory');
		empty($rows_limit) AND $rows_limit = 30;
		if(empty($request['p']))
		{
			$rows_num = $mod_accessory->where($cond)->count();
			if($rows_num > $rows_limit)
			{
				$request['pages'] = ceil($rows_num/$rows_limit);
				$request['path'] = str_replace('/', '~', __INFO__);
				$request['filename'] = $this->_get_filename();
				$this->redirect('Export/download',$request,0);
			}
			else 
			{
				$request['p'] = 1;
			}
		}
		
		//$relation = array('vendor','price','shipping');
		$accessorys = $mod_accessory->where($cond)->order('id desc')->limit($rows_limit)->page($request['p'])->select();
		
		$vendor_mod = $this->_D('AccessoryVendor');
		$price_mod = $this->_D('AccessoryPrice');
		$shipping_mod = $this->_D('AccessoryShipping');
		$finish_mod = $this->_D('AccessoryFinishReview');
		$category_id = $request['category_id'];
		
		$lamp_mod = $this->_D('AccessoryLamp');
	    $pkacc_mod = $this->_D('Accessory5pkacc');
		
		foreach ($accessorys as $key=>$items){
			$accessory_id = $items['id'];
			$accessorys[$key]['vendor'] = $vendor_mod->where(array('accessory_id'=>$accessory_id))->find();;
			$accessorys[$key]['price'] = $price_mod->where(array('accessory_id'=>$accessory_id,'is_history'=>array('neq',0)))->order('id desc')->find();
			$accessorys[$key]['shipping'] = $shipping_mod->where(array('accessory_id'=>$accessory_id))->find();
			//if the category is 5pkacc or lamop
			if($category_id==10 || $category_id==20){
				$accessorys[$key]['price_history'] = $price_mod->where(array('accessory_id'=>$accessory_id,'is_history'=>0))->field('us_fob_price')->order('id asc')->limit(1)->find();;
				$accessorys[$key]['finish_review'] = $finish_mod->where(array('accessory_id'=>$accessory_id))->order('id desc')->limit(1)->find();
			    $shipping = $accessorys[$key]['shipping'];
			    if($shipping['unit']=='MM'){
			    	$shipping['unit_w_mm']=$shipping['unit_w'];
			    	$shipping['unit_w_in']=$this->mm2inch($shipping['unit_w']);
			    	$shipping['unit_d_mm']=$shipping['unit_d'];
			    	$shipping['unit_d_in']=$this->mm2inch($shipping['unit_w']);
			    	$shipping['unit_h_mm']=$shipping['unit_h'];
			    	$shipping['unit_h_in']=$this->mm2inch($shipping['unit_h']);
			    }else{
			    	$shipping['unit_w_in']=$shipping['unit_w'];
			    	$shipping['unit_w_mm']=$this->inch2mm($shipping['unit_w']);
			    	$shipping['unit_d_in']=$shipping['unit_d'];
			    	$shipping['unit_d_mm']=$this->inch2mm($shipping['unit_w']);
			    	$shipping['unit_h_in']=$shipping['unit_h'];
			    	$shipping['unit_h_mm']=$this->inch2mm($shipping['unit_h']);
			    }
			    $shipping['carton_weight_nw_kg'] = $this->lbs2kg($shipping['carton_weight_nw']);
			    $shipping['carton_weight_gw_kg'] = $this->lbs2kg($shipping['carton_weight_gw']);
			    
                $accessorys[$key]['shipping'] = $shipping;
                
                if($accessorys[$key]['unit']=='MM'){
                	$accessorys[$key]['unit_w']=$this->mm2inch($accessorys[$key]['unit_w']);
                	$accessorys[$key]['unit_d']=$this->mm2inch($accessorys[$key]['unit_w']);
                	$accessorys[$key]['unit_h']=$this->mm2inch($accessorys[$key]['unit_h']);
                }
            }
			if($category_id==10){
				$accessorys[$key]['lamp'] = $lamp_mod->where(array('accessory_id'=>$accessory_id))->find();
            }elseif($category_id==20){
				$accessorys[$key]['5pkacc'] = $pkacc_mod->where(array('accessory_id'=>$accessory_id))->find();
			}
		}
		return $accessorys;
	}
	
	/**
	 * get request parameters
	 */
	protected function _get_request()
	{
		if(empty($this->_request))
		{
			$request['market_time'] = $this->_request('market_time');
			$request['vendor_no'] = $this->_request('vendor_no');
			$request['category_id'] = $this->_request('category_id');
			$request['p'] = (int)$this->_request('p');
			
			foreach($request as $k => $v)
			{
				if(empty($v)) unset($request[$k]);
			}
			$request['with_price'] = $this->_request('with_price');
			$request['with_price'] = $request['with_price'] == 1 ? true: false;
			
			$this->_request = $request;
		}
			
		return $this->_request;
	}
	
	/**
	 * get condition
	 */
	protected function _get_cond($request)
	{
		$cond = array('deleted'=>0);
		
		if(!empty($request['market_time']))
		{
			$cond['market_time'] = date('Y-m-d',strtotime($request['market_time']));
		}
		
		if(!empty($request['vendor_no']))
		{
			$cond['id'] = array('in', $this->_get_accessory_list($request['vendor_no']));	
		}
		
		if(!empty($request['category_id']))
		{
			$cond['category_id'] = array('eq', $request['category_id']);	
		}
		
		return $cond;
	}
	
	protected function _get_accessory_list($vendor_no)
	{
		if(empty($vendor_no)) return array(0);
		
		$cond = array('vendor_no' => $vendor_no);
		
		$accessory_list = $this->_D('AccessoryVendor')->field('accessory_id')->where($cond)->select();
		$id_ary = array();
		foreach($accessory_list as $v)
		{
			$id_ary[] = $v['accessory_id'];
		}
		
		return  $id_ary;
	}
	protected function _model()
	{
		return $this->_D('Accessory');
	}
	
	
	
	/**
	 * 毫米转换成英寸
	 *
	 * @param float $mm
	 */
	private function mm2inch($mm){
		if(trim($mm)==='') return '';
		return $this->nubmer2float($this->getFloatFromStr($mm)/25.39999918);
	}
	
	/**
	 * 毫米转换成英寸
	 *
	 * @param float $mm
	 */
	private function inch2mm($inch){
		if(trim($inch)==='') return '';
		return $this->nubmer2float($this->getFloatFromStr($inch)*25.39999918);
	}
	
	/**
	 * LBS单位的数据转为KG单位的数据
	 *
	 * @return float
	 */
	private function lbs2kg($lbs){
		if(trim($lbs)==='') return '';
		return $this->nubmer2float($this->getFloatFromStr($lbs)*0.45359237);
	}
	
	/**
	 * KG单位的数据转为KG单位的数据
	 *
	 * @return float
	 */
	private function kg2lbs($kg){
		if(trim($kg)==='') return '';
		return $this->nubmer2float($this->getFloatFromStr($kg)/0.45359237);
	}
	
	/**
	 * 根据字符串来取得浮点类型的数据
	 *
	 * @param string $str
	 */
	private function getFloatFromStr($str){
		 return floatval($str);
	}
	
	private function nubmer2float($number,$decimal=2){
		return number_format($number,$decimal,'.','');
	}
}