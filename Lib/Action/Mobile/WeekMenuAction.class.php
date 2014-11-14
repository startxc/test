<?php
/**
 * 周菜谱
 * @author mike
 */
class WeekMenuAction extends CommonAction {
    
	public function _initialize() {
        parent::_initialize();
    }
	
	public function index() {
		$this->weekMenu();
    }
    
	/**
     * 周菜谱
     */
    
    public function weekMenu() {
    	$weekmenu = M("Weekmenu")->select();
    	$weekmenuList = array();
    	for ($i=0; $i<7; $i++) {
    		$weekmenuList[$i]['dm'] = date('d/m', strtotime('+' . ($i+1) . ' day'));
    		$weekmenuList[$i]['date'] = date('Ymd', strtotime('+' . ($i+1) . ' day'));
    		$w = date('w', strtotime('+' . ($i+1) . ' day'));
    		$weekmenuList[$i]['week'] = $w == 0 ? 7 : $w;
    		foreach($weekmenu as $value) {
    			if ($value['week'] == $weekmenuList[$i]['week']) {
    				$weekmenuList[$i]['goodsList'][] = $value;
    			}
    		}
    	}
        $this->assign("weekmenuList",$weekmenuList);
        $this->assign('title', '周菜谱');
        $this->display('weekMenu');
    }
    
    /**
     * 获取周菜谱
     */
    
    public function getWeekMenu() {
    	$week = max(intval($_GET['week']), 0);
    	$weekmenu = M("Weekmenu")->where(array('week' => $week))->select();
    	$weekmenuList = array();
    	for ($i=0; $i<7; $i++) {
    		$weekmenuList[$i]['dm'] = date('d/m', strtotime('+' . ($i+1) . ' day'));
    		$weekmenuList[$i]['date'] = date('Ymd', strtotime('+' . ($i+1) . ' day'));
    		$w = date('w', strtotime('+' . ($i+1) . ' day'));
    		$weekmenuList[$i]['week'] = $w == 0 ? 7 : $w;
    		foreach($weekmenu as $value) {
    			if ($value['week'] == $weekmenuList[$i]['week']) {
    				$weekmenuList[$i]['goodsList'][] = $value;
    			}
    		}
    	}
        $this->assign("weekmenuList",$weekmenuList);
    	$content = $this->fetch('getWeekMenu');
    	ajax_return($content);
    }
    
	/**
     * 去结算
     */
    
    public function goToOrder() {
    	$back = new stdClass();
        if (empty($_SESSION['uid'])) {
            $back->status = 0;
            ajax_return($back);
        }
        $model = M();
    	$cartModel = D('Cart');
    	$back = new stdClass();
    	$goodsIdArr = explode(',', trim($_POST['goods_id'], ','));
    	$goodsQtyArr = explode(',', trim($_POST['goods_qty'], ','));
    	$deliveryTimeArr = explode(',', trim($_POST['delivery_time'], ','));
    	$isGroupArr = explode(',', trim($_POST['is_group'], ','));
    	
    	if (empty($goodsIdArr) || empty($goodsQtyArr) || (count($goodsIdArr) != count($goodsQtyArr))) {
    		$back->status = 0;
    		$back->prompt = '参数错误';
			ajax_return($back);
    	}
    	
    	$model->startTrans();
    	$cartIds = array();
    	foreach ($goodsIdArr as $key => $goodsId) {
    		if (!empty($deliveryTimeArr[$key])) {
    			$deliveryTime = strtotime($deliveryTimeArr[$key]);
    		} else {
	    		$deliveryTime =  strtotime(date('Ymd', strtotime('+1 day')));
	    	}
    		$back = $cartModel->addToCart($goodsId, $goodsQtyArr[$key], $deliveryTime, $isGroup);
    		if ($back->status != 1) {
    			$model->rollback();
    			if ($back->status == 0) {
		    		$back->prompt = '商品不存在';
		    		ajax_return($back);
				}  elseif ($back->status == 2) {
		    		$back->prompt = '商品数量有误';
		    		ajax_return($back);
				} elseif ($back->status == 3) {
		    		$back->prompt = '更新商品数量失败';
		    		ajax_return($back);
				} elseif ($back->status == 4) {
		    		$back->prompt = '加入购物车失败';
		    		ajax_return($back);
				}
    		} else {
    			$cartIds[] = $back->cartId;
    		}
    	}
    	$model->commit();
        $cartId = implode(',', $cartIds);
        S("h_goto_order_".$_SESSION['uid'], $cartId, C('DATA_CACHE_TIME'));
        $back->cartId = S("h_goto_order_".$_SESSION['uid']);
        ajax_return($back);
    }
}
?>