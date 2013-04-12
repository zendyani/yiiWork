<?php
 
/* 
 * A bind for cache management to make it easy to activate and de-active
 *
 * @author Belakhdar Abdeldjalil<zendyani@gmail.com>
 * @link http://www.yiiframework.com/
 * @license 
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; version 2
 * of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * example of use
	
	$cache = new CacheManager;
	if(!$cache->get("indexName")){
		// process data
		$data = ....
	}else{
		$data =$cache->value('indexName',$data);
	}
			
 */
class CacheManager
{
	private $_cache=null;
	public $_time=36000; 
	private $_index=null; 
	private $_data=null; 
	 
	public function __construct(){
		
		if(Yii::app()->cache)
			$this->_cache = Yii::app()->cache;
			
	}

	public function get($index){
		try{
			if( $this->_cache!==null AND !empty($index) )
				return $this->_cache->get($index);			
		}catch(Exception $e){
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}

	public function set(){
		try{
			return $this->_cache->set($this->_index,$this->_data,$this->_time);		
		}catch(Exception $e){
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	public function value($index,$data,$time=null){
		
		if( $this->_cache!==null AND !empty($index) ){
			
			$this->_time = ($time===null)?$this->_time:$time;
			$this->_index = $index;
			$this->_data = $data;
			
			if($data=$this->get($this->_index)){
				return $data;
			}else{
				$this->set();
				return $this->_data;
			}	
		}else{
			return $data;	
		}
	}
}


?>
