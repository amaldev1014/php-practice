<!-- http://localhost/listings_new/validate_id_platform_name.php -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Validate ID / Platform Name</title>

<style>
	.error-txt{
		background: #f00;
		color: #ff0;
		padding: 4px;
		display: none;
		border-radius: 3px;
		font-size: 18px;
	}
</style>

</head>
<body>

ID: <input type="text" name="platform_id" style="width: 40px;" autocomplete="off" disabled="">
Platform Name: <input type="text" name="platform_name" autocomplete="off">
<br><br>
<span class="error-txt"></span>


<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
	$(function(){
		$('[name="platform_name"]').focus();
		
		let platform_name_value = '';
		
		$(document).on('input', '[name="platform_name"]', function(){
			platform_name_value = $(this).val();
			
			if( platform_name_value.length < 4 ){
				$('.error-txt').css({'display': 'inline-block'});
				$('.error-txt').html( "'Platform Name' must 4 or more characters!" );
			}
			else{
				// Code here to make error message disappear
			}
			
			// console.log( platform_name_value );
		});
	});
</script>

</body>
</html>