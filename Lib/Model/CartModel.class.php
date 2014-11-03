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
	 * @return object $back status属性(1:加入购物车成功 0:商品不存在 1:商品数量有误 2:更新商品数量失败 3:加入购物车失败)
     */
    
    public function addToCart($goodsId, $goodsQty) {
    	$cartModel = M('Cart');
    	$back = new \stdClass();
    	
    	$goodsInfo = M('Goods')->where(array('id' => $goodsId, 'status' => 1, 'deleted' => 0))->find();
    	if (!$goodsInfo) {
    		$back->status = 0;
	        return $back;
    	}
    	
    	if (max(intval($goodsQty), 0) == 0) {
    		$back->status = 1;
	        return $back;
    	}
    	
    	//组装数据
    	$data = array();
	    $data['member_id'] = $_SESSION['uid'];
	    $data['goods_id'] = $goodsId;
	    $data['goods_name'] = $goodsInfo['name'];
	    $data['price'] = $goodsInfo['price'];
	    $data['number'] = $goodsQty;
	    $data['image'] = $goodsInfo['image'];
	    $data['create_time'] = time();
    	
	    //相同的cartId
	    $cartId = null;
	    	
    	if (!empty($_SESSION['uid'])) {
    		
	    	//获取购物车的所有商品
	    	$cartList = $cartModel->where(array('uid' => $_SESSION['uid']))->select();
	    	
	    	//判断是否存在相同的商品
		    if (is_array($cartList)) {
		    	foreach ($cartList as $key => $cart) {
		    		if ($cart['goods_id'] == $goodsId) {
		    			$cartId = $cart['id'];
		    			break;
		    		}
		    	}
		    }
		    
		    //更新数量
		    if ($cartId) {
		    	$id = $cartModel->where(array('id' => $cartId))->save(array('number' => $goodsQty));
		    	if ($id === false) {
		    		$back->status = 2;
	        		return $back;
		    	}
		    } else {
				$id = $cartModel->add($data);
				if (!$id) {
					$back->status = 3;
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
		    			$cartId = $cart['id'];
		    			$cartKey = $key;
		    			break;
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
     * 获取购物车商品
     * @return array
     */
    
    public function getCartList() {
    	$cartModel = M('Cart');
    	$goodsModel = M('Goods');
    	$cartArr = array();
    	
    	if (!empty($_SESSION['uid'])) {
    		
	    	//获取购物车的所有商品
	    	$cartList = $cartModel->where(array('uid' => $_SESSION['uid']))->select();
    	} else {
	    	$cartList = unserialize(stripslashes(cookie('cartList')));
    	}
    	
    	if (is_array($cartList)) {
	    	foreach ($cartList as $key => $cart) {
	    		$goodsInfo = $goodsModel->where(array('id' => $cart['goods_id']))->field('status, deleted')->find();
	    		if ($goodsInfo['status'] == 0 || $goodsInfo['deleted'] == 1) {
	    			
	    			//将该商品从购物车中删除
	    			$cartModel->where(array('id' => $cart['id']))->delete();
	    			unset($cartList[$key]);
	    			
	    		} else {
	    			$cartArr['data'][$key]['cart_id'] = $cart['id'];
		    		$cartArr['data'][$key]['goods_id'] = $cart['goods_id'];
		    		$cartArr['data'][$key]['goods_name'] = $cart['goods_name'];
		    		$cartArr['data'][$key]['price'] = $cart['price'];
		    		$cartArr['data'][$key]['number'] = $cart['number'];
		    		$cartArr['data'][$key]['image'] = $cart['image'];
		    		$cartArr['data'][$key]['subtotal'] = $cart['number'] * $cart['price'];
		    		$cartArr['total'] += $cartArr['data'][$key]['subtotal'];
		    		$cartArr['total_goods_qty'] += $cart['goods_qty'];
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
	    	if ($id === false) {
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
	    	if ($id === false) {
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
		    $id = $cartModel->where(array('uid' => $_SESSION['uid']))->delete();
	    	if ($id === false) {
	    		return false;
	    	}
    	} else {
    		cookie('cartList', '', time()-3600);
    	}
	    return true;
    }
}
?>