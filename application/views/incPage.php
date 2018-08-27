<?php
class incPage 
{
	public static function displayLeftMenus($menus, $activeMenuItem=-1)
	{
		?>			
			<ul>
			<li class="active" data-toggle="modal" data-target="#cientityPageLoaderModal"> 
				<a href="<?php echo base_url();?>">Dashboard</a>
			</li>
			<?php			
			foreach ($menus as $taskGroupName => $subMenus)
			{				
			?>
				<li class="submenu"> 		
					<a href="#"><span><?php echo $taskGroupName;?></span> <span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<?php
						foreach($subMenus as $menuKey=>$menuItem)
						{
						?>
							<li data-toggle="modal" data-target="#cientityPageLoaderModal"><a <?php if($menuItem['taskId']==$activeMenuItem) {echo "class='active'";} ?> href="<?php echo base_url()."m/e/".$menuItem['taskId'];?>"><?php echo $menuItem['description'];?></a></li>
						<?php
						}
						?>			
					</ul>
				</li>
			<?php
			}
			?>
			<li class="active"> 
				<a href="<?php echo base_url().'user/logout';?>">Logout</a>
			</li>
			</ul>
		<?php
	}
	public static function display($whatToDisplay)
	{
		echo $whatToDisplay;
	}	
	public static function header_JS_CSS($header_JS_CSS)
	{
		foreach($header_JS_CSS as $item)
		{
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".base_url().$item."\">".PHP_EOL;
		}
	}
	public static function footer_JS_CSS($footer_JS_CSS)
	{
		foreach($footer_JS_CSS as $item)
		{
			echo " <script type=\"text/javascript\" src=\"".base_url().$item."\"></script>".PHP_EOL;
		}
	}
	public static function pageLoaderModal()
	{
		echo "
		<div id=\"cientityPageLoaderModal\" class=\"modal custom-modal fade\" role=\"dialog\">
				<div class=\"modal-dialog\">
					<div class=\"modal-content modal-md\">
						<div class=\"modal-header\">
							<h4 class=\"modal-title\">Loading...</h4>
						</div>
						<div class=\"modal-body card-box\">							
							<div class=\"loader\"></div>
						</div>
					</div>
				</div>
			</div>
		";
	}
	public static function displayAddEditModal($addEditModal='')	
	{
		echo $addEditModal;
	}
}
?>



