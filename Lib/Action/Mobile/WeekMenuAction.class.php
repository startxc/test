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
    
    public function weekMenu() {//S("h_selected_goods_".$_SESSION['uid'], null);
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
    	$dates = array();
    	$selectedGoods = unserialize(S("h_selected_goods_".$_SESSION['uid']));
    	foreach ($selectedGoods as $key => $vo) {
    		$dates[] = $vo['date'];
    		foreach ($vo['goodsList'] as $k => $goods) {
    			$selectedGoods[$key]['goodsList'][$k]['image'] = M('Goods')->where(array('id' => $goods['goods_id']))->getField('image');
    		}
    	}
    	$this->assign("dates",implode(',', $dates));
    	$this->assign("selectedGoods",$selectedGoods);
        $this->assign("weekmenuList",$weekmenuList);
        $this->assign('title', '周菜谱');
        $this->display('weekMenu');
    }
    
    /**
     * 获取周菜谱
     */
    
    public function getWeekMenu() {
    	if (!$this->isAjax()) {
            $this->redirect('Index/index');
        }
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
     * 添加周菜谱
     */
    
    public function addWeekMenu() {
    	$categoryList = D("Goods")->getCategoryList();
		$param = array(
			"size"=>20,
		);
		if(!empty($_GET['cid'])){
			$param['category_id'] = intval($_GET['cid']);
			$category = M("Category")->where("id={$param['category_id']}")->getField("name");
			$this->assign("category",$category);
		}
		$goodsList = D("Goods")->getGoodsList($param);
		$this->assign("categoryList",$categoryList);
		$this->assign("goodsList",$goodsList);
		if ($this->isAjax()) {
			$content = $this->fetch('getGoodsList');
    		ajax_return($content);
		}
		$selectedGoods = unserialize(S("h_selected_goods_".$_SESSION['uid']));
		$flag = false;
		foreach ($selectedGoods as $key => $vo) {
			if ($vo['date'] == $_GET['date']) {
				$flag = true;
			}
		}
		if (!$flag) {
			$this->assign('flag', 1);
		}
		$this->assign('date', $_GET['date']);
		$this->assign('selectedGoods', $selectedGoods);
    	$this->assign('title', '添加周菜谱');
    	$this->display();
    }
    
    /**
     * 保存已选择的商品
     */
    
    public function saveChooseGoods() {
    	if (!$this->isAjax()) {
            $this->redirect('Index/index');
        }
    	$selectedGoods = array();
    	foreach ($_POST['delivery_time'] as $key => $vo) {
    		$isSame = false;
    		foreach ($selectedGoods as $k => $v) {
    			if ($_POST['delivery_time'][$key] == $v['date']) {
    				$selectedGoods[$k]['goodsList'][] = array(
		    			'goods_id' => $_POST['goods_id'][$key],
			    		'goods_qty' => $_POST['goods_qty'][$key]
		    		);
		    		$isSame = true;
    			}
    		}
    		if (!$isSame) {
    			$selectedGoods[$key]['dm'] = date('d/m', strtotime($_POST['delivery_time'][$key]));
	    		$selectedGoods[$key]['date'] = $_POST['delivery_time'][$key];
	    		$w = date('w', strtotime($_POST['delivery_time'][$key]));
	    		$selectedGoods[$key]['week'] = $w == 0 ? 7 : $w;
	    		$selectedGoods[$key]['goodsList'][] = array(
	    			'goods_id' => $_POST['goods_id'][$key],
		    		'goods_qty' => $_POST['goods_qty'][$key]
	    		);
    		}
    	}
    	S("h_selected_goods_".$_SESSION['uid'], serialize($selectedGoods), C('DATA_CACHE_TIME'));
    }
    
	/**
     * 去结算
     */
    
    public function goToOrder() {
    	if (!$this->isAjax()) {
            $this->redirect('Index/index');
        }
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