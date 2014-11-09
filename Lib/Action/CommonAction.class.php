<?php
define('PAGE_SQL_SPLIT', '^');
class CommonAction extends Action {
	protected $_not_auth_action = array();
	protected $_group_name = '';
	

	public function index() {
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();

		$model = $this->_D($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}

		if(empty($_GET['jump']))
		{
			$this->display ();
		}
		else
		{
			$this->display($_GET['jump']);
		}
		return;
	}
	/**
     +----------------------------------------------------------
	 * 取得操作成功后要返回的URL地址
	 * 默认返回当前模块的默认操作
	 * 可以在action控制器中重载
     +----------------------------------------------------------
	 * @access public
     +----------------------------------------------------------
	 * @return string
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	function getReturnUrl() {
		return __URL__ . '?' . C ( 'VAR_MODULE' ) . '=' . MODULE_NAME . '&' . C ( 'VAR_ACTION' ) . '=' . C ( 'DEFAULT_ACTION' );
	}
	
	/**
     +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param string $name 数据对象名称
     +----------------------------------------------------------
	 * @return HashMap
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	protected function _search($name = '') {
		//生成查询条件
		if (empty ( $name )) {
			$name = $this->getActionName();
		}
		//$name=$this->getActionName();
		$model = $this->_D( $name );
		$map = array ();
		foreach ( $model->getDbFields () as $key => $val ) {

			if (isset ( $_REQUEST [$val] ) && $_REQUEST [$val] != '') {
				$tmp = explode(PAGE_SQL_SPLIT, $_REQUEST[$val]);
				if(is_array($tmp) && count($tmp)>1)
				{
					$map[$val] = $tmp;
				}
				else
				{
					$map [$val] = $_REQUEST [$val];
				}
			}
		}

		return $map;

	}

	/**
     +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
     +----------------------------------------------------------
	 * @return void
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		
		$source_mode = clone $model;
		
		//取得满足条件的记录数
		$count = $source_mode->where ( $map )->count('1');
		if ($count > 0) {
			import ( "ORG.Page" );
			//创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '';
			}
			$p = new Page ( $count, $listRows );
			//分页查询数据

			$voList = $model->where($map)->order( $order .' '. $sort)->limit($p->firstRow . ',' . $p->listRows)->select();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				} elseif(!empty($val[0]) && !empty($val[1])) {
					$p->parameter .= "$key={$val[0]}^" . urlencode ( $val[1] ) . "&";
				}
			}


			//分页显示
			$page = $p->show ();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
		}
		Cookie::set ( '_currentUrl_', __SELF__ );
		return $voList;
	}

	function insert($name = '') {
		//B('FilterString');
		empty($name) AND $name=$this->getActionName();
		$model = $this->_D($name);
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		//保存当前数据对象
		$list=$model->add ();
		if ($list!==false) { //保存成功
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success (L('Add_New').L('Successfully'));
		} else {
			//失败提示
			$this->error (L('Add_New').L('Failured'));
		}
	}

	public function add() {
		$this->_before_add();
		$this->display ();
	}

	function _before_add(){}
	
	function read() {
		$this->edit ();
	}

	function edit() {
		$name=$this->getActionName();
		$model = $this->_D( $name );
		$id = $_REQUEST [$model->getPk ()];
		$vo = $model->getById ( $id );
		//added by: Sunvor 2011.9.8
		$this->_before_edit($vo);
		$this->assign ( 'vo', $vo );
		$this->display ();
	}
	function _before_edit(&$data = array()){}

	//this is the function deal the post
	function update($name = '') {
		//B('FilterString');
		empty($name) AND $name = $this->getActionName();
	
		$model = $this->_D( $name );
		// model->create, this function gather the post data
		if (false ===($data= $model->create ())) {
			$this->error ( $model->getError () );
		}
		// 更新数据 update data
		// $model->save(): 
		$list=$model->save ();
		if (false !== $list) {
			//成功提示
			// success
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success (L('Edit').L('Successfully'));
		} else {
			//错误提示
			// error
			$this->error (L('Edit').L('Failured'));
		}
	}
	/**
     +----------------------------------------------------------
	 * 默认删除操作
     +----------------------------------------------------------
	 * @access public
     +----------------------------------------------------------
	 * @return string
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	public function delete() {
		//删除指定记录
		$name=$this->getActionName();
		$model = $this->_D($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = $_REQUEST [$pk];
			if (isset ( $id )) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				$list=$model->where ( $condition )->setField ( 'deleted',1);
				if ($list!==false) {
					$this->success (L('Delete_Successfully') );
				} else {
					$this->error (L('Delete_Failured'));
				}
			} else {
				$this->error ( L('Illegal').L('Operate') );
			}
		}
	}
	public function foreverdelete() {
		//删除指定记录
		$name=$this->getActionName();
		$model = $this->_D($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = $_REQUEST [$pk];
			if (isset ( $id )) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				if (false !== $model->where ( $condition )->delete ()) {
					
					$this->success (L('Delete_Successfully'));
				} else {
					$this->error (L('Delete_Failured'));
				}
			} else {
				$this->error ( L('Illegal').L('Operate') );
			}
		}
		$this->forward ();
	}

	public function clear() {
		//删除指定记录
		$name=$this->getActionName();
		$model = $this->_D($name);
		if (! empty ( $model )) {
			if (false !== $model->where ( 'status=1' )->delete ()) {
				$this->assign ( "jumpUrl", $this->getReturnUrl () );
				$this->success ( L ( '_DELETE_SUCCESS_' ) );
			} else {
				$this->error ( L ( '_DELETE_FAIL_' ) );
			}
		}
		$this->forward ();
	}
	/**
     +----------------------------------------------------------
	 * 默认禁用操作
	 *
     +----------------------------------------------------------
	 * @access public
     +----------------------------------------------------------
	 * @return string
     +----------------------------------------------------------
	 * @throws FcsException
     +----------------------------------------------------------
	 */
	public function forbid() {
		$name=$this->getActionName();
		$model = $this->_D($name);
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		$condition = array ($pk => array ('in', $id ) );
		$list=$model->forbid ( $condition );
		if ($list!==false) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( L('Status').L('Disabled') );
		} else {
			$this->error  (  L('Operate').L('Failured') );
		}
	}
	public function checkPass() {
		$name=$this->getActionName();
		$model = $this->_D($name);
		$pk = $model->getPk ();
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->checkPass( $condition )) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( 'Status Approved！' );
		} else {
			$this->error  (  'Status Setting Failured!' );
		}
	}

	public function recycle() {
		$name=$this->getActionName();
		$model = $this->_D($name);
		$pk = $model->getPk ();
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->recycle ( $condition )) {

			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( L('Status').L('Recover').L('Successfully') );

		} else {
			$this->error   (  L('Status').L('Recover').L('Failured') );
		}
	}

    public function mAPI()
    {
    	$methodName = empty($_REQUEST[C('API_SWITCH')]) ? '' : $_REQUEST[C('API_SWITCH')];
    	if($methodName && method_exists($this, $methodName)) {
    		
    		$this->checkAccessRight($methodName);
    		
    		$this->_destryAPISwitch();
    		return $this->$methodName();
    	} else {
    		return $this->_defaultAPI();
    	}
    }
    
	function ajaxRespon($data, $msg = '', $status = 1) {
		$type = empty($_REQUEST['t']) ? 'XML' : strtoupper($_REQUEST['t']);
		$this->ajaxReturn($data, $msg, $status, $type);
	}
	
	function recycleBin() {
		$map = $this->_search ();
		$map ['status'] = - 1;
		$name=$this->getActionName();
		$model = $this->_D($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display();
	}

	/**
     +----------------------------------------------------------
	 * 默认恢复操作
	 *
     +----------------------------------------------------------
	 * @access public
     +----------------------------------------------------------
	 * @return string
     +----------------------------------------------------------
	 * @throws FcsException
     +----------------------------------------------------------
	 */
	function resume() {
		//恢复指定记录
		$name=$this->getActionName();
		$model = $this->_D($name);
		$pk = $model->getPk ();
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->resume ( $condition )) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success (L ('Status').L('Restore').L('Successfully') );
		} else {Log::write($model->getLastSql());
			$this->error ( L ('Status').L('Restore').L('Failured')  );
		}
	}

	function saveSort() {
		$seqNoList = $_POST ['seqNoList'];
		if (! empty ( $seqNoList )) {
			//更新数据对象
		$name=$this->getActionName();
		$model = $this->_D($name);
			$col = explode ( ',', $seqNoList );
			//启动事务
			$model->startTrans ();
			foreach ( $col as $val ) {
				$val = explode ( ':', $val );
				$model->id = $val [0];
				$model->sort = $val [1];
				$result = $model->save ();
				if (! $result) {
					break;
				}
			}
			//提交事务
			$model->commit ();
			if ($result!==false) {
				//采用普通方式跳转刷新页面
				$this->success ( L('Update_Successfully') );
			} else {
				$this->error ( $model->getError () );
			}
		}
	}
	
	protected function checkAccessRight($action, $isDieByError=true)
	{
    	import ( 'ORG.RBAC' );
    	$groupName = empty($this->_group_name) ? APP_NAME : $this->_group_name;
    	if (!$res = RBAC::AccessDecision ($groupName, $action)) {
			if (C ( 'GUEST_AUTH_ON' )) {
				if($isDieByError) $this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
				else return false;
			}
			
			if($isDieByError) {
				$this->error ( L ( '_VALID_ACCESS_' ).":".$action );
			} else {
				return false;
			}
    	}
    	
    	return true;
	}
	
	private function _defaultAPI() { $this->ajaxRespon(array());}
	
	private function _destryAPISwitch()
	{
    	unset($_REQUEST[C('API_SWITCH')]);
    	unset($_POST[C('API_SWITCH')]);
    	unset($_GET[C('API_SWITCH')]);
	}
	
	/**
	 * 根据_group_name 的设置实例化模型对象
	 * */
	protected function _D($name)
	{
		$name = empty($this->_group_name) ? $name : $this->_group_name ."/".$name;
		return D($name);
	}
		
	/**
	 * 根据_group_name 的设置实例化Action对象
	 * */
	protected function _A($name)
	{
		$name = empty($this->_group_name) ? C('DEFAULT_GROUP').'/'.$name : $this->_group_name ."/".$name;
		return A($name);
	}
	
	protected function _autocomplete(Autocomplete $obj)
	{
		$obj->Autocomplete();
	}
	
	/**
	 * 
	 * */
	protected function _setLoginLink()
	{
		$_SESSION['JUMP'] = __URL__;
	}
	
	protected function getUser(){
		return $_SESSION['user'];
	}
	
	protected function uploadImage($name,$dir,$old_file=''){
		$extends = array('jpg',  'jpeg', 'bmp', 'gif', 'png', 'dwg');
		
		$file = $_FILES[$name];
		if(empty($file)){
			return '';
		}
		$extend = strtolower(substr($file['name'],strrpos($file['name'],'.')+1));
		if(!in_array($extend,$extends)){
			return '';
		}
		$filename = time().mt_rand(100000, 999999).'.'.$extend;;
		
		$dir = 'Upload/'.$dir.'/'.date('Y/m/d/');
		$save_dir = APP_DIR.$dir;
		$destfile = $save_dir.$filename;
		
		if(!is_dir($save_dir)){
			@mk_dir($save_dir);
		}
		if(@move_uploaded_file($file['tmp_name'], $destfile))
		{
			 chmod($destfile, 0777);
			 if($old_file && is_file(APP_DIR.$old_file)){
			 	@unlink(APP_DIR.$old_file);
			 }
			 return $dir.$filename;
		}
	}
}