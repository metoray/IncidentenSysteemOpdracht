<?php
//setup for page
require_once('include/database.php');
session_start();

//get path components in array
$path = ltrim($_SERVER['REQUEST_URI'], '/');
$path = explode('/', preg_split("/[?#]/",$path)[0]);

//get user in $user variable, redirect to login page if not set
$user = isset($_SESSION['user'])?$_SESSION['user']:null;
if($user==null){
	header('Location: /login.php');
	die();
}

//home is default, workaround for faulty .htaccess
if(count($path)==1 && $path[0] == ""){
	header('Location: /home');
	die();
}

//get all pages of website to construct menu
$root = Page::getPageStructure();
$currentPage = $root->find($path); //use array path to find current page
$content = "Page not found!"; //set error message in case page is not found
$title = "";

//make sure current page exists
if($currentPage){
	$fileName = $currentPage->getFile();
	$title = $currentPage -> getTitle();
	//make sure page sourcefile exists
	if(file_exists($fileName)){
		//check to see if user has access to the current page
		if($currentPage->hasAccess($_SESSION['user'])){
			//check to see if current page uses the HTML in this file, include and die otherwise
			if($currentPage->usesMenu()){
				ob_start();
				include($fileName);
				$content = ob_get_clean();
			}
			else{
				include($fileName);
				die();
			}
		}
		else{
			$content = "<h3>Je hebt niet de rechten voor deze pagina!</h3>";
		}
	}
	else{
		$content = "Page not found: ".$fileName;
	}
}
//start of html
?>
<!DOCTYPE html>
<html>
<head>
	<?php
	if($title) echo "<title>$title</title>"; // set page title if it has one
	?>
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<style type="text/css">
		table td.shrink {
		    white-space:nowrap
		}
		table td.expand {
		    width: 99%
		}
	</style>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.tree-toggler').click(function () {
				$(this).parent().children('ul.tree').toggle(100);
			});
			$('.tree').each(function(idx){
				if($(this).find('.active').length==0){
					$(this).toggle();
				};
			});
		});
	</script>
	<style type="text/css">
	.tree {
		margin-left: 16px !important;
	}
	</style>
</head>
<body>
	<div class="col-md-4 col-lg-2">
		<ul class="nav nav-pills nav-stacked">
<?php
function outputMenuItem($page){
	global $path;	//get the current path to this scope
	if(!($page->hasAccess($_SESSION['user']))) return false;	//don't show link if user does not have rights to this page
	if(!($page->isVisible()))	return false;	//don't show if page is invisible
	$active = ($page -> isActive($path))?'class="active" ':'';	//highlight page if it should
	echo '<li '.$active.' role="presentation">';
	if($page->hasVisibleSubpages()){
		echo '<a href="#" class="tree-toggler">'.$page->getTitle().'<span class="caret"></span></a>';	//print title of page
		echo '<ul class="nav nav-pills nav-stacked tree">';
		foreach ($page->getSubpages() as $key => $subpage) {	//call this function for sub-pages
			outputMenuItem($subpage);
		}
		echo '</ul>';
	}
	else{
		if($page->getFile()){	//only show link if it goes somewhere
			echo '<a href="'.$page->getFullPathString().'">'.$page->getTitle().'</a>';	//show singular page link
		}
	}
	echo '</li>';
	return true;	//function had output
}
foreach ($root->getSubpages() as $key => $page) {
	outputMenuItem($page);
}
?>
		</ul>
	</div>
	<div class="col-md-8 col-lg-10">
		<div class="col-md-6">
			<h3><?php echo $title; ?></h3>
		</div>
		<div class="col-md-6">
			<div class="text-right">
				<a href="/process/logout" class="btn btn-default">
					Uitloggen <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
				</a>
			</div>
		</div>
		<div class="col-md-12">
<?php
echo $content;
?>
		</div>
	</div>
</body>
</html>
