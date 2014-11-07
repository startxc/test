<?php
/**
 * 代金券接口
 * @author mike
 */
class CouponAction extends MobileCommonAction {
	
	/**
	 * 添加一张代金券
	 */
	
    public function addCoupon() {
    	$model = M();
    	$couponModel = M('Coupon');
    	$memberCouponModel = M('MemberCoupon');
		$back = new stdClass();
		$code = $_POST['code'];
		
		$map = array();
		$map['code'] = $code;
		$map['start_time'] = array('elt', time());
		$map['end_time'] = array('egt', time());
		$couponInfo = $couponModel->where($map)->find();
    	if (!$couponInfo) {
			$this->error("代金券不存在或已失效");
        	$back->status = 0;
            $back->prompt = "代金券不存在或已失效";
            return $back;
		}
		if ($couponInfo['use_mid'] != 0) {
			$this->error("代金券已被使用");
        	$back->status = 0;
            $back->prompt = "代金券已被使用";
            return $back;
		}
		
		$model->startTrans();
		$data = array();
		$data['member_id'] = $_SESSION['uid'];
		$data['coupon_id'] = $couponInfo['id'];
		$data['name'] = $couponInfo['name'];
		$data['coupon_code'] = $couponInfo['code'];
		$data['face_value'] = $couponInfo['face_value'];
		$data['start_time'] = $couponInfo['start_time'];
		$data['end_time'] = $couponInfo['end_time'];
		$data['intro'] = $couponInfo['intro'];
		$data['create_time'] = time();
		$id = $memberCouponModel->add($data);
		if (!$id) {
			$model->rollback();
			$this->error("亲,对不起,系统出现错误啦");
        	$back->status = 0;
            $back->prompt = "亲,对不起,系统出现错误啦".$memberCouponModel->getLastSql();
            return $back;
		}
		$id = $couponModel->where(array('id' => $couponInfo['id']))->save(array('use_mid' => $_SESSION['uid']));
		if ($id === false) {
			$model->rollback();
			$this->error("亲,对不起,系统出现错误啦");
        	$back->status = 0;
            $back->prompt = "亲,对不起,系统出现错误啦";
            return $back;
		}
		
		$model->commit();
		$this->success("操作成功");
		$back->status = 1;
		return $back;
    }
    
	/**
	 * 根据代金券ID获取一张代金券
	 */
	
	public function getCouponById() {
		$memberCouponModel = M('MemberCoupon');
		$id = max(intval($_GET['id']), 0);
		$memberCouponInfo = $memberCouponModel->where(array('id' => $id, 'member_id' => $_SESSION['uid']))->find();
		$this->ajaxRespon($memberCouponInfo);
		return $memberCouponInfo;
    }
    
	/**
	 * 根据券号获取一张代金券
	 */
	
	public function getCouponByCode() {
		$memberCouponModel = M('MemberCoupon');
		$code = $_GET['code'];
		$memberCouponInfo = $memberCouponModel->where(array('coupon_code' => $code, 'member_id' => $_SESSION['uid']))->find();
		$this->ajaxRespon($memberCouponInfo);
		return $memberCouponInfo;
    }
    
	/**
	 * 获取用户所有代金券
	 */
	
	public function getCouponList() {
		$couponModel = M('Coupon');
    	$memberCouponModel = M('MemberCoupon');
    	$couponStatus = in_array($_GET['coupon_status'], array('use', 'used', 'exceed')) ? $_GET['coupon_status'] : '';
		
		$map = array();
		$map['member_id'] = $_SESSION['uid'];
		if ($couponStatus == 'use') {
			$map['start_time'] = array('elt', time());
			$map['end_time'] = array('egt', time());
			$map['used'] = 0;
		}
		if ($couponStatus == 'used') {
			$map['used'] = 1;
		}
		if ($couponStatus == 'exceed') {
			$map['end_time'] = array('lt', time());
		}
    	$count = $memberCouponModel->where($map)->count('id');
    	if ($count > 0) {
            import("@.ORG.Util.Page");
            $p = new Page($count, 10);
            $page = $p->show();
            $memberCouponList = $memberCouponModel->where($map)->limit($p->firstRow . ',' . $p->listRows)->order('create_time desc')->select(); 
        }
		
		$this->ajaxRespon($memberCouponList);
        $this->assign('coupon_status', $couponStatus);
        $this->assign('page', $page);
		return $memberCouponList;
	}
    
	/**
     * 获取用户代金券各状态统计
     */
    
    public function getCouponStatusCount() {
    	$memberCouponModel = M('MemberCoupon');
    	$memberCouponList = $memberCouponModel->where(array('member_id' => $_SESSION['uid']))->field('id, start_time, end_time, used')->select();
    	foreach ($memberCouponList as $key => $memberCoupon) {
    		if ($memberCoupon['start_time'] <= time() && $memberCoupon['end_time'] >= time() && $memberCoupon['used'] == 0) {
    			$useArr[] = $memberCoupon;
    		} elseif ($memberCoupon['used'] == 1) {
    			$usedArr[] = $memberCoupon;
    		} elseif ($memberCoupon['end_time'] < time()) {
    			$exceedArr[] = $memberCoupon;
    		}
    	}
    	$couponStatusCount = array(
	    	'coupon_num' => count($memberCouponList),
	    	'use_num' => count($useArr),
	    	'used_num' => count($usedArr),
	    	'exceed_num' => count($exceedArr)
    	);
    	$this->ajaxRespon($couponStatusCount);
		ajax_return($couponStatusCount);
    }
    
    /**
     * 获取即将过期的代金券数
     */
    
    public function getExpireCouponNumber() {
    	$memberCouponModel = M('MemberCoupon');
    	$days = max(intval($_GET['days']), 0);
    	$map = array();
		$map['member_id'] = $_SESSION['uid'];
		$map['start_time'] = array('elt', time());
		$map['end_time'] = array(array('egt', time()), array('lt', time() + 3600 * 24 * $days));
		$map['used'] = 0;
		$couponNumber = $memberCouponModel->where($map)->count('id');
		$this->ajaxRespon($couponNumber);
		return $couponNumber;
    }
}