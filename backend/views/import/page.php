<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolSchoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '正梵掌上学校';
?>
<div class="text-center"><h1><?php echo $errorinfo;?></h1></div>
<div id="div1" class="text-center"></div>
<script>
var t = 5;  
function showTime(){  
  t -= 1;  
  document.getElementById('div1').innerHTML= t;  
  if(t==0){  
      location.href='<?php if(isset($location_url)) echo $location_url;else echo "/import/index"?>';  
  }  
  setTimeout("showTime()",1000);  
}  
 
showTime();  
</script>