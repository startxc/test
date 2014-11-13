<?php
/**
 * 购物车模型文件
 */
class CartModel extends CommonModel {
	
    public function __construct() {
        parent::__construct();
    }
    
	/**
     * 加入购物车
	 * @param integer $goodsId 商品ID
	 * @param integer $goodsQty 商品数量
	 * @param integer $deliveryTime 配送日期
	 * @param integer $isGroup 是否是火拼商品
	 * @return object $back status属性(1:加入购物车成功 0:商品不存在 2:商品数量有误 3:更新商品数量失败 4:加入购物车失败)
     */
    
    public function addToCart($goodsId, $goodsQty, $deliveryTime, $isGroup=0) {
    	$cartModel = M('Cart');
    	$back = new stdClass();
    	
    	$goodsInfo = M('Goods')->where(array('id' => $goodsId, 'is_deleted' => 0))->find();
    	if (!$goodsInfo) {
    		$back->status = 0;
	        return $back;
    	}
    	
    	if (max(intval($goodsQty), 0) == 0) {
    		$back->status = 2;
	        return $back;
    	}
    	
    	//组装数据
    	$data = array();
	    $data['member_id'] = $_SESSION['uid'];
	    $data['goods_id'] = $goodsId;
	    $data['goods_name'] = $goodsInfo['name'];
	    if ($isGroup == 1) {
	    	$groupModel = M('Group');
	    	$groupInfo = $groupModel->where(array('goods_id' => $goodsId))->find('real_price, group_phase_id');
	    	$data['price'] = $groupInfo['real_price'];
	    	$data['group_phase_id'] = $groupInfo['group_phase_id'];
	    } else {
	    	$data['price'] = $goodsInfo['price'];
	    }
	    $data['number'] = $goodsQty;
	    $data['image'] = $goodsInfo['image'];
	    $data['create_time'] = time();
	    $data['delivery_time'] = $deliveryTime;
    	
	    //相同的cartId
	    $cartId = null;
	    	
    	if (!empty($_SESSION['uid'])) {
    		
	    	//获取购物车的所有商品
	    	$cartList = $cartModel->where(array('member_id' => $_SESSION['uid']))->select();
	    	
	    	//判断是否存在相同的商品
		    if (is_array($cartList)) {
		    	foreach ($cartList as $key => $cart) {
		    		if ($cart['goods_id'] == $goodsId) {
		    			if (date('Ymd', $cart['delivery_time']) == date('Ymd', $deliveryTime)) {
		    				$cartId = $cart['id'];
		    				break;
		    			}
		    		}
		    	}
		    }
		    
		    //更新数量
		    if ($cartId) {
		    	$id = $cartModel->where(array('id' => $cartId))->setInc('number', $goodsQty);
		    	if ($id === false) {
		    		$back->status = 3;
	        		return $back;
		    	}
		    } else {
				$id = $cartModel->add($data);
				if (!$id) {
					$back->status = 4;
	        		return $back;
				}
		    }
		    
        } else {
        	
	    	//获取购物车的所有商品
	    	$cartList = unserialize(stripslashes(cookie('cartList')));
	    	
       	 	//判断是否存在相同的商品
		    if (is_array($cartList)) {
		    	foreach ($cartList as $key => $cart) {
		    		if ($cart['goods_id'] == $goodsId) {
		    			if (date('Ymd', $cart['delivery_time']) == date('Ymd', $deliveryTime)) {
		    				$cartId = true;
			    			$cartKey = $key;
			    			break;
		    			}
		    		}
		    	}
		    }
	    	
        	//更新数量
		    if ($cartId) {
		    	$cartList[$cartKey]['number'] = $cartList[$cartKey]['number'] + $goodsQty;
		    } else {
		    	$cartList[] = $data;
		    }
		    
		    cookie('cartList', serialize($cartList), 3600 * 24);
        }
    	
	    $back->status = 1;
        return $back;
    }
    
    /**
	 * 合并购物车商品
	 */
	
	public function merageCart() {
		$cartList = unserialize(stripslashes(cookie('cartList')));
		foreach ($cartList as $key => $cart) {
			$this->addToCart($cart['goods_id'], $cart['number']);
		}
		cookie('cartList', null);
	}
    
	/**
     * 获取购物车商品
     * @return array
     */
    
    public function getCartList($cartIds='') {
    	$cartModel = M('Cart');
    	$goodsModel = M('Goods');
    	$cartArr = array();
    	
    	if (!empty($_SESSION['uid'])) {
    		$map = array();
    		$map['member_id'] = $_SESSION['uid'];
    		if (!empty($cartIds)) {
    			$map['id'] = array('in', trim($cartIds, ','));
    		}
	    	$cartList = $cartModel->where($map)->select();
    	} else {
	    	$cartList = unserialize(stripslashes(cookie('cartList')));
    	}
    	
    	if (is_array($cartList)) {
	    	foreach ($cartList as $key => $cart) {
	    		$goodsInfo = $goodsModel->where(array('id' => $cart['goods_id']))->field('is_deleted, spec, spec_unit')->find();
	    		if ($goodsInfo['is_deleted'] == 1) {
	    			
	    			//将该商品从购物车中删除
	    			$cartModel->where(array('id' => $cart['id']))->delete();
	    			unset($cartList[$key]);
	    			
	    		} else {
		    		//如果购物车ID为空，则赋值为$key
	    			if (!$cart['id']) {
	    				$cart['id'] = $key;
	    			}
	    			$cartArr['data'][$key]['cart_id'] = $cart['id'];
		    		$cartArr['data'][$key]['goods_id'] = $cart['goods_id'];
		    		$cartArr['data'][$key]['goods_name'] = $cart['goods_name'];
		    		$cartArr['data'][$key]['price'] = $cart['price'];
		    		$cartArr['data'][$key]['number'] = $cart['number'];
		    		$cartArr['data'][$key]['image'] = $cart['image'];
		    		$cartArr['data'][$key]['delivery_time'] = $cart['delivery_time'];
		    		$cartArr['data'][$key]['spec'] = $goodsInfo['spec'];
		    		$cartArr['data'][$key]['spec_unit'] = $goodsInfo['spec_unit'];
		    		$cartArr['data'][$key]['subtotal'] = number_format($cart['number'] * $cart['price'], 2, '.', '');
		    		$cartArr['total'] += $cartArr['data'][$key]['subtotal'];
		    		$cartArr['total_goods_qty'] += $cart['number'];
	    		}
	    	}
    	}
    	
	    return $cartArr;
    }
    
    /**
     * 更新购物车商品数量
     * @param integer $cartId 购物车ID
     * @param integer $goodsQty 商品数量
     * @return boolean
     */
    
    public function updateCart($cartId, $goodsQty) {
    	if (!empty($_SESSION['uid'])) {
    		$cartModel = M('Cart');
	    	$id = $cartModel->where(array('id' => $cartId))->save(array('number' => $goodsQty));
	    	if (!$id) {
	    		return false;
	    	}
    	} else {
    		$cartList = unserialize(stripslashes(cookie('cartList')));
    		$cartList[$cartId]['number'] = $goodsQty;
    		cookie('cartList', serialize($cartList), 3600 * 24);
    	}
	    return true;
    }
    
    /**
     * 删除购物车商品
     * @param integer $cartId 购物车ID
     * @return boolean
     */
    
    public function deleteCart($cartId) {
	    if (!empty($_SESSION['uid'])) {
    		$cartModel = M('Cart');
		    $id = $cartModel->where(array('id' => $cartId))->delete();
	    	if (!$id) {
	    		return false;
	    	}
    	} else {
    		$cartList = unserialize(stripslashes(cookie('cartList')));
    		unset($cartList[$cartId]);
    		cookie('cartList', serialize($cartList), 3600 * 24);
    	}
	    return true;
    }
    
    /**
     * 清空购物车商品
     */
    
    public function emptyCart() {
    	if (!empty($_SESSION['uid'])) {
    		$cartModel = M('Cart');
		    $id = $cartModel->where(array('member_id' => $_SESSION['uid']))->delete();
	    	if ($id === false) {
	    		return false;
	    	}
    	} else {
    		cookie('cartList', null);
    	}
	    return true;
    }
}
?>