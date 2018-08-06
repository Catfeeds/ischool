
<div class="text-center"><h1><?php echo $info;?></h1></div>
<div id="div1" class="text-center"></div>
<script>
var t = 3;
function showTime(){
  t -= 1;
  document.getElementById('div1').innerHTML= t;
  if(t==0){
      location.href='<?php if(isset($location_url)) echo $location_url;else echo "/"?>';
  }
  setTimeout("showTime()",1000);
}

showTime();
</script>