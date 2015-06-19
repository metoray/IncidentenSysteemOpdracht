<?php
require_once('include/database.php');

$path = ltrim($_SERVER['REQUEST_URI'], '/');
$path = explode('/', $path);

$root = Page::getPageStructure();
$currentPage = $root->find($path);
$content = "Page not found!";
$title = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
if($currentPage){
	$fileName = $currentPage->getFile();
	$title = $currentPage -> getTitle();
	if(file_exists($fileName)){
		ob_start();
		include($fileName);
		$content = ob_get_clean();
	}
	else{
		$content = "Page not found: ".$fileName;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php $title; ?></title>
	<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.tree-toggler').click(function () {
				$(this).parent().children('ul.tree').toggle(100);
			});
			$('ul.tree').each(function(idx){
				if($(this).find('li.active').length==0){
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
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
<?php
function outputMenuItem($page){
	global $path;
	$active = ($page -> isActive($path))?'class="active" ':'';
	echo '<li '.$active.' role="presentation">';
	if($page->hasSubpages()){
		echo '<a href="#" class="tree-toggler">'.$page->getTitle().'<span class="caret"></span></a>';
		echo '<ul class="nav nav-pills nav-stacked tree">';
		foreach ($page->getSubpages() as $key => $subpage) {
			outputMenuItem($subpage);
		}
		echo '</ul>';
	}
	else{
		echo '<a href="'.$page->getFullPathString().'">'.$page->getTitle().'</a>';
	}
	echo '</li>';
}
foreach ($root->getSubpages() as $key => $page) {
	outputMenuItem($page);
}
?>
		</ul>
	</div>
	<div class="col-md-10">
<?php
echo $content;
?>
	</div>
</body>
</html>