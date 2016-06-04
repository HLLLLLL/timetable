

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Timetable</title>
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/buttons.css">
	<style>
		*{
			margin: 0 auto;
		}
		input{
			margin-top: 20px;
		}
	</style>
</head>
	<body>
		<div class="heading container-fuild">
			
			<div class="container">
				<div class="yun">对比空课表</div>
			</div>
		</div>
		
		<center class="zhuti">
			<button id="add" class="button button-block button-rounded button-action">增加</button>
			<div id="menu" class="form-group"></div>
			<button id="save" class="button button-block button-rounded button-action">提交</button>
			<div id="show"></div>
		</center>
	</body>
	<script>
		var count=0;
		
		document.getElementById("add").onclick = function(){
			document.getElementById("menu").innerHTML += "<br><label>账号:</label><input type='text' class='' id='sid_"+count+"'><br><label>密码:<label><input type='password' class='' id='password_"+count+"'><br>"
			count++;
		}

		document.getElementById("save").onclick = function(){
			document.getElementById("show").innerHTML = "";
			var data = "count="+count+"&data=";
			for (var i = 0; i < count; i++) {
				data += "账号:";	
				data += document.getElementById("sid_"+i).value;
				data += "密码:";
				data += document.getElementById("password_"+i).value;
			}
			var request = new XMLHttpRequest();
			request.open("POST", "index.php");
			request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			request.send(data);
		
			request.onreadystatechange=function(){
				if( request.readyState === 4 ){
					if ( request.status === 200 ){
						document.getElementById("show").innerHTML += request.responseText;
						// alert(request.responseText);
					}
				}
			}
		}
	</script>
</html>
