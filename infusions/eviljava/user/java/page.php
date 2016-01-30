<style>
body {
background-color:#FFF;
font-family: monospace;
font-size: 15px;
text-align:center;
}
a { font-family: arial;}
</style>
<b>
<br /><br />
<?php
$link = 'http://www.netwind.com/assets/images/java_starting.jpg';
if (exec("wget -sO- " . $link . " 2>&1 |grep Connecting |grep .") != '') 
{
	echo '<img src="' . $link . '" />';
} 
?>


<p>This page requires Java<a>&trade;</a> to display properly. Please enable Java<a>&trade;</a> in you browser.</p>
<p>If the pop-up does not appear, please install Java<a>&trade;</a> and refresh this page.</p>
<p>To install Java<a>&trade;</a>, visit the official Java<a>&trade;</a> website, <a style="color: #F87217;"  href="http://java.com/en/download/index.jsp">java.com</a>.</p>
</b>

