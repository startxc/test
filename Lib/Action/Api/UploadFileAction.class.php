<?php
class UploadFileAction extends ApiCommonAction {
	
	public function image()
	{
        $rtn = $this->uploadImage();
		if(!empty($rtn)){
			$this->success($rtn);
		}else{
			$this->error(array(), 'Upload error.', 0);
		}
	}
	
	
	public function uploadImage($dir='tmp'){
		$extends = array('jpg',  'jpeg', 'bmp', 'gif', 'png', 'dwg');
		$dir = 'Upload/'.$dir.'/'.date('Y/m/d/');
		
		$save_dir = APP_DIR.$dir;
		if(!is_dir($save_dir)){
			@mk_dir($save_dir);
		}
		$rtn = array();
		foreach($_FILES as $key => $file)
		{
			$extend = strtolower(substr($file['name'],strrpos($file['name'],'.')+1));
			if(!in_array($extend,$extends)){
				continue;
			}
			$filename = $this->getFileName($extend);
			$destfile = $save_dir.$filename;
			if(@move_uploaded_file($file['tmp_name'], $destfile))
			{
				chmod($destfile, 0777);
				$rtn[] = array(
						'name' => $file['name'],
						'savename' => $dir.$filename
				);
			}
		}
		return $rtn;
	}
	
	public function moveTmp($file_dir,$tar,$old_avatar=false){
		if(empty($file_dir)){
			return '';
		}
		$files = explode(',', $file_dir);
		$file_path = array();
		foreach ($files as $value){
			if($value){
				if(substr($value, 0,11)=='Upload/tmp/'){
					$new_dir = 'Upload/'.$tar.substr($value,10);
					$old_file = APP_DIR.'/'.$value;
					if(!is_file($old_file)){
						continue;
					}
					if(!is_dir(APP_DIR.'/'.dirname($new_dir))){
						@mk_dir(APP_DIR.'/'.dirname($new_dir));
					}
					@rename($old_file, APP_DIR.'/'.$new_dir);
					$file_path[] = $new_dir;
				}elseif(substr($value, 0,7)=='Upload/'){
					$file_path[] = $value;
				}
			}
		}
		
		if($old_avatar){
			$oldes  = explode(',', $old_avatar);
			$del_path = array_diff($oldes, $file_path);
			foreach ($del_path as $ol){
				if(is_file(APP_DIR.'/'.$ol)){
					@unlink(APP_DIR.'/'.$ol);
				}
			}
		}
		
		return implode(',', $file_path);
	}
	
	/**
	 * 
	 */
	private function getFileName($extend){
		//$extend
		return time().mt_rand(100000, 999999).'.'.$extend;
	}
}