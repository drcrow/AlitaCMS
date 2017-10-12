<?php
require_once('home_nav.php');
?>
<div class="container">

<?php
//require_once('home_sidebar.php');

switch(@$selectMode){
	case 'list':
		require_once('home_content.php');
		break;
	case 'add':
		require_once('home_content_add.php');
		break;
	default:
		require_once('home_main.php');
}

/*
if(isset($_GET['edit'])){
	require_once('home_content_add.php');
}elseif(isset($_GET['add'])){
	require_once('home_content_add.php');
}elseif(isset($selectedType)){
	require_once('home_content.php');
}else{
	require_once('home_main.php');
}
*/
?>
        

</div>