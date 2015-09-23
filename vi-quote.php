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
if($token === TRUE){
	switch($product){
	case 'VI|01':
		// require('VI-data-property.php');
		break;
	case 'VI|02':
		if(isset($_GET['idc'])){
			require('VI-data-customer.php');
		}
		break;
	case 'VI|03':
		if(isset($_GET['idc'])){
			require('VI-data-question.php');
		}
		break;
	case 'VI|04':
		if(isset($_GET['idc'])){
			require('VI-result-quote.php');
		}
		break;
	case 'VI|05':
		if((isset($_GET['ide']) || isset($_GET['idc'])) && isset($_GET['flag'])){
			require('VI-data-issue.php');
		}
		break;	
	}
}else{
	if($ms !== NULL){
		switch($product){
		case 'VI|01':
			if(isset($_GET['idc'])){
				require('VI-data-property.php');
			}
			break;
		case 'VI|02':
			if(isset($_GET['idc'])){
				require('VI-data-customer.php');
			}
			break;
		case 'VI|06':
			if(isset($_GET['idc'])){
				require('VI-data-question.php');
			}
			break;	
		case 'VI|03':
			if(isset($_GET['idc'])){
				require('VI-result-quote.php');
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