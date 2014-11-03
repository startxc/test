<?php

/*
 * 进度模型
 * author： kewen
 */

class PhaseModel extends CommonModel {
    
    public function count() {        
        $detail = S('h_charity_phase_x');
        if(empty($detail)) {
            $Phase = M("Charity_phase");        
            $tmp = $Phase->where("is_delete = 0")->sum("paid_money");
            $detail = intval($tmp);
            S('h_charity_phase_x', $detail);
        }
        return $detail;
    }
    
    public function clear() {
        S('h_charity_phase_x', null);
    }
    
}