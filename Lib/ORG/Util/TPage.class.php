<?php

/**
 * 自定义分页类
 *
 * @author kewen
 */
class TPage {
    // 页数跳转时要带的参数
    public $parameter;
    // 每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow;
    // 总页面数
    protected $totalPages;
    // 总行数
    protected $totalRows;
    // 当前页数
    protected $nowPage;
    // 分页显示定制
    protected $config  =	array('prev'=>'<','next'=>'>');
    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows   每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows='',$parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->nowPage  = !empty($_GET[C('VAR_PAGE')])?intval($_GET[C('VAR_PAGE')]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }
    
    // 配置语言包
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }
    
    /**
     +----------------------------------------------------------
     * 基本分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function top() {
        //链接初始化
        if(0 == $this->totalRows) return '';
        $p = C('VAR_PAGE');
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a class='prev' href='".$url."&".$p."=$upRow'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }
        if ($downRow <= $this->totalPages){
            $downPage="<a class='next' href='".$url."&".$p."=$downRow'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        return '<span class="page_num"><strong class="red">' . $this->nowPage . '</strong>/' . $this->totalPages . '</span>' . $upPage . $downPage ;
    }
    
    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show() {
        //链接初始化
        if($this->totalPages<2) return '';
        $p = C('VAR_PAGE');
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='".$url."&".$p."=$upRow'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }
        if ($downRow <= $this->totalPages){
            $downPage="<a class='next_page' href='".$url."&".$p."=$downRow'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        $pagestr = "";
        // 1 2 3 4 5
        if($this->totalPages<6) {
            for($i=1; $i<=$this->totalPages; $i++) {
                $pagestr .= ($i==$this->nowPage) ? '<a class="current" href="' . $url . "&" .$p. '=' . $i . '">' .$i. '</a>' : '<a href="' . $url . "&" .$p. '=' . $i . '">' .$i. '</a>';
            }
            $pagestr = '<span class="pager">' . $pagestr . '</span>';
        } 
        // 1 ... 7 8 9 10 11 ... 20
        else {
            $temp = array();
            for($i=1; $i<=$this->totalPages; $i++) {
                if($i==1) {
                    $temp[0] = '<a href="' . $url . "&" .$p. '=' . $i . '">' .$i. '</a>';
                }elseif($i>1) {
                    if($i>$this->nowPage-3 && $i<$this->nowPage) {
                        $temp[2] .= '<a href="' . $url . "&" .$p. '=' . $i . '">' .$i. '</a>';
                    } else if($i==$this->nowPage) {
                        $temp[2] .= '<a class="current" href="' . $url . "&" .$p. '=' . $i . '">' .$i. '</a>';
                    } else if($i>$this->nowPage && $i<$this->nowPage+3) {
                        $temp[2] .= '<a href="' . $url . "&" .$p. '=' . $i . '">' .$i. '</a>';
                    }
                    if($i==$this->totalPages && $this->nowPage<$this->totalPages-2) {
                        $temp[4] = '<a href="' . $url . "&" .$p. '=' . $i . '">' .$i. '</a>';
                    }
                } 
            }
            $temp[1] = ($this->nowPage>4) ? '<i>...</i><span class="pager">' : '';
            $temp[3] = ($this->nowPage<$this->totalPages-3) ? '</span><i>...</i>' : '';
            ksort($temp);
            if($this->nowPage>4 && ($this->nowPage<$this->totalPages-3)) {
                $pagestr .= implode(" ", $temp);
            } elseif($this->nowPage<5) {
                $temp[3] = empty($temp[3]) ? "</span>" : $temp[3];
                $pagestr .= '<span class="pager">' . implode(" ", $temp);
            } elseif($this->nowPage>$this->totalPages-4) {
                $pagestr .= implode(" ", $temp) .  "</span>";
            } 
        }
        unset($temp);
        $go = '';
        if($this->totalPages>5) {
            $go .= '<input id="js_jump_input" class="number" value="">';
            $go .= '<a class="go" title="跳转" href="javascript:;" onclick="goToPage(\'js_jump_input\');"> 跳 转 </a>';
            $go .= '<script type="text/javascript">
                                    //跳转到指定的分页
                                    var max_page = ' .$this->totalPages. ';
                                    function goToPage(id)
                                    {
                                            var input_num = document.getElementById(id).value;
                                            if(isNaN(input_num) || input_num <= 0) {
                                                    alert(\'请输入大于0的数字!\'); 
                                                    return;
                                            }
                                            if(input_num > max_page) {
                                                    input_num = max_page;
                                            }
                                            var url = "' . $url . "&" .$p. '=" + input_num;
                                            location.href = url;
                                    }
                    </script>';
        }
        return $upPage . $pagestr . $downPage . $go;
    }

}

?>
