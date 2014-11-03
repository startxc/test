<?php

/*
 * 产品模型
 * author： kewen
 */

class ProductModel extends CommonModel {
    
    public function listCategory() {
        return $this->query('select * from ' . $this->tablePrefix . 'category' . ' order by porder asc');
    }

    //添加父类节点
    public function parentNode($id) {
        $info = $this->query('select * from ' . $this->tablePrefix . 'category where id=\'' . $id . '\' limit 1');
        return $info[0];
    }

    //更新全部父类子节点
    public function childNode($id, $nodeid) {
        $result = $this->execute('update ' . $this->tablePrefix . 'category set childs=CONCAT(childs,\'' . $nodeid . ',\') where id=\'' . $id . '\' limit 1');
        return ($result === false ? false : true);
    }

    //更新全部子节点的pindex
    public function updateChildNode($oorder, $norder) {
        $result = $this->execute('update ' . $this->tablePrefix . 'category set porder=REPLACE(porder,\'' . $oorder . '\', \''. $norder .'\') where porder like \'%' . $oorder . '%\'');
        return ($result === false ? false : true);
    }
    
    //删除父类中的childs
    public function deleteChildsNode($childId) {
        $result = $this->execute('update ' . $this->tablePrefix . 'category set childs=REPLACE(childs,\'' . $childId . ',\', \'\') where childs like \'%' . $childId . ',%\'');
        return ($result === false ? false : true);
    }
    
    //更新预定商品数量
    public function updateGoodsGroupNumber($orderno_arr){
        foreach($orderno_arr as $orderno){
            $member_id = M("Order")->where("order_no={$orderno}")->getField("member_id");
            $role_type = M("Member")->where("id={$member_id}")->getField("role_type");
            if($role_type == 2){
                break;
            }            
            $order_id = M("Order")->where("order_no='{$orderno}'")->getField("id");
            $order_goods = M("Order_goods")->field("prebuyid,number")->where("order_id={$order_id}")->select();
            foreach($order_goods as $goods){
                $goods_group = M("Goods_group")->where("id={$goods['prebuyid']}")->find();
                if(!empty($goods_group)){  
                        $group_data = array(
                            "id"=>$goods_group['id'],
                            "order_number"=>array("exp","`order_number`+".$goods['number']),
                        );
                        if(($goods_group['order_number']+$goods['number']>=$goods_group['order_moq']) && $goods_group['order_status']==0){
                            $group_data['order_status'] = 1;
                            $group_data['success_time'] = time();
                            $data = array(
                                "goods_id"=>$goods_group['goods_id'],
                                "goods_name"=>$goods_group['goods_name'],
                                "order_moq"=>$goods_group['order_moq'],
                                "order_number"=>0,
                                "deposit"=>$goods_group['deposit'],
                                "sale_count"=>0,
                                "status"=>1,
                                "order_status"=>0,
                                "begin_time"=>time(),
                                "end_time"=>strtotime("+1 month"),
                                "finish_time"=>$goods_group['finish_time'],
                                "create_time"=>time(),
                                "update_time"=>time()
                            );
                            M("Goods_group")->data($data)->add();
                        }
                        M("Goods_group")->data($group_data)->save();
                }
            }
        }
    }
    
    //整批购买成功之后更新孵化作品的批次
    public function updateHatchBatch($orderno_arr){
        foreach($orderno_arr as $orderno){
             $order = M("Order")->field("id,is_batch")->where("order_no='{$orderno}'")->find();
             if($order['is_batch'] == 1){
                 $goods_id = M("Order_goods")->where("order_id={$order['id']}")->getField("goods_id");
                 $hatch_id = M("Goods")->where("id={$goods_id}")->getField("hatch_id");
                 $is_buyout = M("Hatch")->where("id={$hatch_id}")->getField("is_buyout");
                 if($is_buyout){
                     $batch_day = strtotime("+5 days",strtotime(date("Y-m-d 23:59:59")));
                     $sql = "update ps_hatch set batch=batch+1,batch_day='{$batch_day}' where id={$hatch_id}";
                 }else{
                     $sql = "update ps_hatch set batch=batch+1 where id={$hatch_id}";
                 }
                 $model = new Model();
                 $model->execute($sql);
             }
        }
    }
    
}

?>
