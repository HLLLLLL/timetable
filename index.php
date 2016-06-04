





<?php 
	header("Conetnt_Type:text/plain;charset=utf-8");
	if(!isset($_POST['count']) || empty($_POST['count'])){
		echo "参数错误没有count";
	}

	$data = $_POST["data"];
	/*
		处理POST过来的数据，账号密码
		将他们放在$arr数组里

	*/
	$arr = array();
	$cnt = 0;
	$content = explode( "账号:", $data);
	foreach ($content as $key => $value) {
		$content_1 = explode("密码:", $value);
		$arr[$cnt] = $content_1;
		$cnt++;
	}

	if(count($arr)<=2){
		echo "至少需要输入两个人的数据";
	}

	echo "<br>";
	/*
		通过账号密码调用API获取课表，并把每个人的课表放在数组Array里

	*/
	$count = $_POST['count'];
	$Array = array();
	for ($i=1; $i <= $count; $i++) {
		if($i==1){
			$temp = get_timetable($arr[$i][0], $arr[$i][1]);

		}
		if($i>1){
			$temp = get_timetable($arr[$i][0], $arr[$i][1]);

		}
		$Array[$i] = getNewtable($temp);
	}

	for ($i=2; $i <= $count; $i++) { 
		if( $i == 2 ) {
			$arr_1 = compare($Array[1], $Array[2]);
		}
		if( $i > 2 ){
			$arr_1 = compare($arr_1, $Array[$i]);
		}
	}
	show($arr_1);
	function show($arr_1){
		/*
			展示空课时间
			以后可以换成课表，通过颜色表示是否有空余时间
		*/
		for ($i=1; $i <=7 ; $i++) { 
			for ($k=1; $k <=5 ; $k++) { 
				if($arr_1[$i][$k]==0){
					echo "星期".$i."第".$k."节有时间";
					echo "<br>";
				}
			}
		}
	}

	function compare($arr_1, $arr_2){

		/*
			对比两个人的课表
			并创建一个新的课表
			空同空课用0表示
		*/
		$arr_3 = array();
		for ($i=1; $i <= 7; $i++) { 
			for ($k=1; $k <= 5; $k++) { 
				if($arr_1[$i][$k]==0 && $arr_2[$i][$k]==0){
					$arr_3[$i][$k] = 0;
				} else {
					$arr_3[$i][$k] = 1;
				}
			}
		}
		return $arr_3;
	}

	function get_timetable($sid, $password){
		/*
			调用API获取个人课表
		*/
		$url = "http://api.sky31.com/edu_course.php?role=2015551416&hash=2d845916eb150f94cb384b7d8685d052&sid=$sid&password=$password";
		$content = file_get_contents($url);

		$content = json_decode($content, true);
		if($content['msg'])
		return $content;
	}

	function getNewtable($test){
		$arr = array();
		// 遍历课表
		/**
		 * 创建一个新数组，数组中1表示有课，0表示没课
		 */

		for ($i=1; $i <= 7; $i++) {
			for ($k=1; $k <= 5; $k++) { 
				if (!empty($test['data'][$i][$k]) || !empty($test['data'][$i][$k][1])) {
					if (count($test['data'][$i][$k]) >= 2 ) {
						foreach ($test['data'][$i][$k] as $key => $value) {
							/**
								这里还有一个细节没有写好
								如果课程是分3-12周
								和12周以后
								第一节课从1-3
								到从1-2
							**/
							switch ($value['section_start']) {
								case 1:
									$arr[$i][1] = 1;
									break;
								case 3:
									$arr[$i][2] = 1;
									break;
								case 5:
									$arr[$i][3] = 1;
									break;
								case 7:
									$arr[$i][4] = 1;
									break;	
								case 9:
									$arr[$i][5] = 1;
									break;
							}
						}
					}
					$arr[$i][$k] = 1;
				} else {
					$arr[$i][$k] = 0;
				}
			}
		}
		return $arr;
	}
 ?>