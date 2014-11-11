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
        $this->display('weekMenu');
    }
    
    /**
     * 获取周菜谱
     */
    
    public function getWeekMenu() {
    	$week = max(intval($_GET['week']), 0);
    	$weekmenu = M("Weekmenu")->where(array('week' => $week))->select();
    	foreach ($weekmenu as $key => $menu) {
    		$weekmenu[$key]['goods_image'] = picture($weekmenu[$key]['goods_image'], '', 'product');
    	}
    	ajax_return($weekmenu);
    }
}
?>