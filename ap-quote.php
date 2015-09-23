<?php
$product = '';
if(isset($_GET['pr'])){
	$product = base64_decode($_GET['pr']);
}else{
	echo '<meta http-equiv="refresh" content="0;url=index.php" >';
}

include('header.inc.php');
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".fancybox").fancybox({
		
	});
});
</script>
<div id="content-main">
	<section id="main">
<?php
//echo $product;
if($token === TRUE){
	switch($product){
	case 'AP|01':
		// require('BS-data-property.php');
		break;
	case 'AP|02':
		if(isset($_GET['idc'])){
			require('AP-data-customer.php');
		}
		break;
	case 'AP|03':
		if(isset($_GET['idc'])){
			require('AP-data-question.php');
		}
		break;
	case 'AP|04':
		if(isset($_GET['idc'])){
			require('AP-result-quote.php');
		}
		break;
	case 'AP|048':
		if(isset($_GET['idc']) && isset($_GET['flag'])){
			require('AP-save-share.php');
		}
		break;
	case 'AP|05':
		if((isset($_GET['ide']) || isset($_GET['idc'])) && isset($_GET['flag'])){
			require('AP-data-issue.php');
		}
		break;	
	}
}else{
	if($ms !== NULL){
		switch($product){
		case 'AP|01':
			if(isset($_GET['idc'])){
				require('AP-data-property.php');
			}
			break;
		case 'AP|02':
			if(isset($_GET['idc'])){
				require('AP-data-customer.php');
			}
			break;
		case 'AP|06':
			if(isset($_GET['idc'])){
				require('AP-data-question.php');
			}
			break;	
		case 'AP|03':
			if(isset($_GET['idc'])){
				require('AP-result-quote.php');
			}
			break;
		}
	}else{
		include('index-content.inc.php');
	}
}
?>
	</section>
</div>	
<?php
include('footer.inc.php');
?>