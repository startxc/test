<?php
/**
 * 代金券管理
 */
class CouponAction extends CommonAction {
	private $listRows = 20;
	private $couponDir;
	
	public function _initialize() {
		$this->couponDir = str_replace(APP_NAME."/", "upload/coupon/", ROOT_PATH);
	}
	
	public function index() {
    	$this->couponTypeList();
    }
    
    public function excelList() {
    	$this->couponExcelList();
    }
    
	public function typeList() {
    	$this->couponTypeList();
    }
    
	/**
	 * 代金券类型列表
	 */
	
	public function couponTypeList() {
		$couponModel = M('Coupon');
		$couponTypeModel = M('CouponType');
		
		$count = $couponTypeModel->count('id');
		if ($count > 0) {
            import("@.ORG.Util.Page");
            $p = new Page($count, $this->listRows);
            $page = $p->show();
            $couponTypeList = $couponTypeModel->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
            foreach ($couponTypeList as $key => $couponType) {
            	$couponTypeList[$key]['count'] = intval($couponModel->where(array('coupon_type_id' => $couponType['id']))->count('id'));
            }
        }
        
		$this->assign('couponTypeList', $couponTypeList);
		$this->assign('page', $page);
		$this->display('couponTypeList');
	}
	
	/**
	 * 添加代金券类型
	 */

	public function addCouponType() {
		if ($this->isAjax()) {
			$couponTypeModel = M('CouponType');
			$back = new \stdClass();
			
			$data = array();
			$data['name'] = $_POST['name'];
			$data['face_value'] = $_POST['face_value'];
			$data['start_time'] = strtotime($_POST['start_time']);
			$data['end_time'] = strtotime($_POST['end_time']);
			$data['min_use_value'] = $_POST['min_use_value'];
			$data['intro'] = $_POST['intro'];
			$id = $couponTypeModel->add($data);
			if (!$id) {
				$back->status = 0;
				$back->prompt = '亲,对不起,系统出现错误啦';
				ajax_return($back);
			}
			
			$back->status = 1;
		    ajax_return($back);
		}
		$this->display();
	}
	
	/**
	 * 生成代金券
	 */
	
	public function createCoupon() {
		if ($this->isAjax()) {
			$model = M();
			$couponModel = M('Coupon');
			$couponTypeModel = M('CouponType');
			$back = new \stdClass();
			$id = max(intval($_POST['id']), 0);
	    	$couponTypeInfo = $couponTypeModel->where(array('id' => $id))->find();
			
	    	$model->startTrans();
	    	for($i=0; $i<intval($_POST['couponCount']); $i++) {
		    	$data = array();
				$data['code'] = $this->getCouponCode();
				$data['face_value'] = $couponTypeInfo['face_value'];
				$data['start_time'] = $couponTypeInfo['start_time'];
				$data['end_time'] = $couponTypeInfo['end_time'];
				$data['create_time'] = time();
				$data['coupon_type_id'] = $couponTypeInfo['id'];
				$data['min_use_value'] = $couponTypeInfo['min_use_value'];
				$data['intro'] = $couponTypeInfo['intro'];
				$id = $couponModel->add($data);
				if (!$id) {
					$model->rollback();
					$back->status = 0;
					$back->prompt = '亲,对不起,系统出现错误啦';
					ajax_return($back);
				}
	    	}
	    	
	    	$model->commit();
			$back->status = 1;
		    ajax_return($back);
		}
		$couponTypeModel = M('CouponType');
		$id = max(intval($_GET['id']), 0);
		$couponTypeInfo = $couponTypeModel->where(array('id' => $id))->find();
		if (!is_array($couponTypeInfo)) {
            $this->redirect('Coupon/couponTypeList');
		}
		$this->assign('couponTypeInfo', $couponTypeInfo);
		$this->display();
	}
	
	/**
	 * 更新代金券类型
	 */

	public function updateCouponType() {
		if ($this->isAjax()) {
			$couponTypeModel = M('CouponType');
			$back = new \stdClass();
			
			$data = array();
			$data['name'] = $_POST['name'];
			$data['start_time'] = strtotime($_POST['start_time']);
			$data['end_time'] = strtotime($_POST['end_time']);
			$data['min_use_value'] = $_POST['min_use_value'];
			$data['intro'] = $_POST['intro'];
			$id = $couponTypeModel->where(array('id' => $_POST['id']))->save($data);
			if ($id === false) {
				$back->status = 0;
				$back->prompt = '亲,对不起,系统出现错误啦';
				ajax_return($back);
			}
			
			$back->status = 1;
		    ajax_return($back);
		}
		$couponTypeModel = M('CouponType');
	    $map = array();
	    $map['id'] = max(intval($_GET['id']), 0);
	    $couponTypeInfo = $couponTypeModel->where($map)->find();
		if (!$couponTypeInfo) {
			$this->redirect('Coupon/couponTypeList');
		}
		$this->assign('couponTypeInfo', $couponTypeInfo);
		$this->display();
	}
	
	/**
	 * 代金券列表
	 */
	
	public function couponList() {
		$couponModel = M('Coupon');
		
		$condition = array();
		$condition['coupon_type_id'] = $_GET['id'];
		$count = $couponModel->where($condition)->count('id');
		if ($count > 0) {
            import("@.ORG.Util.Page");
            $p = new Page($count, $this->listRows);
            $page = $p->show();
            $couponList = $couponModel->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order('id desc')->select();
        }
        
		$this->assign('couponList', $couponList);
		$this->assign('page', $page);
		$this->display('couponList');
	}
	
	/**
	 * 生成代金券Excel
	 */
	
	public function createCouponExcel() {
		$couponModel = M('Coupon');
		$couponTypeModel = M('CouponType');
		$fileName = $this->couponDir.'/c'.date('YmdHis').$this->random(8).'.xls';
		
		$couponList = $couponModel->where(array('coupon_type_id' => $_GET['id']))->order('create_time desc')->select();
		$excelStr = '<table><tr><th>名称</th><th>券号</th><th>面值</th>
	    <th>已被使用</th><th>开始时间</th><th>结束时间</th></tr>';
		
		if (is_array($couponList)) {
			foreach ($couponList as $key => $coupon) {
				$name = $couponTypeModel->where(array('id' => $coupon['coupon_type_id']))->getField('name');
				$isUse = $coupon['use_mid'] > 0 ? '是' : '否';
	            $startTime = date('Y-m-d H:i:s', $coupon['start_time']);
	            $endTime = date('Y-m-d H:i:s', $coupon['end_time']);
	            
	            $excelStr.='<tr>';
				$excelStr.='<td>'.$name.'</td>';
				$excelStr.='<td>'.$coupon['code'].'</td>';
				$excelStr.='<td>'.$coupon['face_value'].' 元</td>';
				$excelStr.='<td>'.$isUse.'</td>';
				$excelStr.='<td>'.$startTime.'</td>';
				$excelStr.='<td>'.$endTime.'</td>';
				$excelStr.='</tr>';
			}
			$excelStr.='</table>';
		        
			import("@.ORG.Util.File");
	        $fileObj = new File($fileName, 'w+');
			$fileObj->write($excelStr);
			$this->redirect('Coupon/couponExcelList');
		} else {
			$this->error('实体代金券数量为0张，无法备份');
		}
	}
	
	/**
	 * 代金券Excel列表
	 */
	
	public function couponExcelList() {
		$dirArray = array();
		
		$filePathArray = $this->listFile($this->couponDir);
	    if (is_array($filePathArray)) {
	    	foreach ($filePathArray as $key => $filePath) {
		    	$dirArray[$key]['name'] = basename($filePath);
				$dirArray[$key]['size'] = $this->tosize(filesize($filePath));
				$dirArray[$key]['time'] = date('Y-m-d', fileatime($filePath));
				$dirArray[$key]['path'] = base64_encode($filePath);
	    	}
	    }

		$this->assign('dirArray', $dirArray);
		$this->display('couponExcelList');
	}
	
	/**
	 * 获取代金券号
	 */

	private function getCouponCode() {
		$couponModel = M('Coupon');
		$code = null;
		
		//代金券号规则为"CP"+8位随机数字
		$code = "CP" . $this->random(8);
		$couponPo = $couponModel->where(array('code' => $code))->find();
		if (is_array($couponPo)) {
			$code = $this->getCouponCode();
		}
		return $code;
	}
	
	/**
	 * 随机生成字符串
	 */
	
	private function random($len = 6) {
		$len = intval($len);
		if ($len > 32) $len = 32;
		$str = md5(uniqid(mt_rand(), true));
		return substr($str, 0, $len);
	}
	
	/**
	 * 遍历文件夹
	 */

	private function listFile($dir) {
		$filePathArray = array();
		$tmpFilePathArray = array();
		if ($handle = opendir($dir)) {
			while (($file = readdir($handle)) !== false) {
				if ($file !="." && $file !="..") {
					if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
						$tmpFilePathArray = listFile($dir . DIRECTORY_SEPARATOR . $file);
						$filePathArray = array_merge($filePathArray, $tmpFilePathArray);
					} else {
						$filePathArray[] = $dir . DIRECTORY_SEPARATOR . $file;
					}
				}
			}
			fclose($handle);
		}
		return $filePathArray;
	}
	
	/**
	 * 文件尺寸转换，将大小将字节转为各种单位大小
	 */
	
	private function tosize($bytes) {       	 	     	  //自定义一个文件大小单位转换函数
		if ($bytes >= pow(2,40)) {      		      //如果提供的字节数大于等于2的40次方，则条件成立
			$return = round($bytes / pow(1024,4), 2); //将字节大小转换为同等的T大小
			$suffix = "TB";                        	  //单位为TB
		} elseif ($bytes >= pow(2,30)) {  		      //如果提供的字节数大于等于2的30次方，则条件成立
			$return = round($bytes / pow(1024,3), 2); //将字节大小转换为同等的G大小
			$suffix = "GB";                           //单位为GB
		} elseif ($bytes >= pow(2,20)) {  		      //如果提供的字节数大于等于2的20次方，则条件成立
			$return = round($bytes / pow(1024,2), 2); //将字节大小转换为同等的M大小
			$suffix = "MB";                           //单位为MB
		} elseif ($bytes >= pow(2,10)) {  		      //如果提供的字节数大于等于2的10次方，则条件成立
			$return = round($bytes / pow(1024,1), 2); //将字节大小转换为同等的K大小
			$suffix = "KB";                           //单位为KB
		} else {                     			      //否则提供的字节数小于2的10次方，则条件成立
			$return = $bytes;                         //字节大小单位不变
			$suffix = "Byte";                         //单位为Byte
		}
		return $return . " " . $suffix;               //返回合适的文件大小和单位
	}
}