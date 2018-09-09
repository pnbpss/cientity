<?php
/**
 * Class entityRecipes 
 * @author Panu Boonpromsook <pnbpss@gmail.com>
 * 
 * entityRecipes declares and supplies an information each entity for class formResponses and mainForm.
 * Information in this file will be varied on table in database, or table design. 
 * 
 * For none Thai reader, you can ignore Thai comment in this file. It provided for easier Thai reader to understand the code. The contexts are same as English. 
 */
class entityRecipes {
	
	private $default_header_JS_CSS;
	private $default_footer_JS_CSS;
	private $recipes = [];
	/**
	 * default header of Javascript and CSS file linked
	 */
	function __construct() {		
	
		$this->default_header_JS_CSS=[		
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		];
		/**
		 * default footer  Javascript and CSS file linked
		 */
		$this->default_footer_JS_CSS=[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		];
		/**
		 * recipes is array of entity properties, these are information that will be use to 
		 * - construct SQL for search in filter row
		 * - select data from table
		 * - etc.
		 * devClasses and devClassEnrollists will be use as examples, so I will put a comment to described each Item one by one.
		 * Please see the descriptions in devClasses_recipes.php and devClassEnrollists_recipes.php for more details.
		 */
		$this->recipes = [
			'dummy'=>[]			
			,'devTest001'=>[
				'descriptions' => 'just for test 001'
			]		
			,'repExpenseReports'=>[
				'customized'=>true
				,'descriptions'=>'Expense reports'
			]			
		];
		
		$recipesFiles = scandir(APPPATH."libraries/entityRecipes");
		foreach($recipesFiles as $file){
			$endWith = substr($file,(strlen($file)-12),12);
			//echo $endWith;
			if(strtolower($endWith)=='_recipes.php'){
				$recipes=[];
				require APPPATH."libraries/entityRecipes/".$file;
				$this->mergeRecipes($recipes);
			}
		}		
	}
	private function mergeRecipes($recipes){
		foreach($recipes as $key=>$recipe){$this->recipes[$key]=$recipe;}
	}
	function getRecipes(){
		return $this->recipes;
	}
	function getDescriptions($entityName){		
		return $this->recipes[$entityName];		
	}
	function getAllDescriptions(){
		return $this->recipes;
	}
	function getEntityName($taskId){
		$CI =& get_instance();
		$CI->load->database();
		$q = $CI->db->query("select taskName from {$CI->db->dbprefix}sysTasks where id=".$CI->db->escape($taskId).";");
		$row = $q->row();
		if(isset($row)){
			return $row->taskName;
		}else{
			return '404';
		}
	}
	private function infokeysArray(){
		$info = $this->recipes;
		$newArray = [];
		foreach(array_keys($info) as $key){
			array_push($newArray,$key);
		}
		return $newArray;
	}
	function entityNameByURISegment($uriSegment3){
		$entityInfoKey = $this->infokeysArray();		
		$property = explode("_", $uriSegment3);
		$entityOrdinal = (int) $property[0];
		return $entityInfoKey[$entityOrdinal];
	}
	function default_footer_JS_CSS(){
		return $this->default_footer_JS_CSS;
	}
	function default_header_JS_CSS(){
		return $this->default_header_JS_CSS;
	}
} //end of class entityRecipes
