<html>
<head>
<title>Pineapple Control Center</title>
<script  type="text/javascript" src="jquery.min.js"></script>
</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<?php require('../includes/navbar.php'); ?>

<table border="0" width="100%"><tr><td align="left" valign="top" width="80%">
<pre>

<?php
if(isset($_POST[traceroutehost])) {

$cmd = "traceroute $_POST[traceroutehost]";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline\n");}

}
?>

</pre>
</td><td valign="top" align="left" width="*">
<pre>



<font color="white">
                    \               
                  \  \          
                \  \  \</font><font color="green">              
<,  .v ,  // </font><font color="white">) ) )  )  )</font><font color="green">                  
 \\; \// //     </font><font color="white">/  /  /</font><font color="green">                          
  ;\\|||//;       </font><font color="white">/  /</font><font color="yellow">
 ,'<\/><\/`         </font><font color="white">/</font><font color="yellow">                    
,.`X/\><\\>`                      
;>/>><\\><\/`                        
|<\\>>X/<>/\|
`<\/><\/><\\;                            
 '/\<>/\<>/'                       
   `<\/><;`</font><font color="white">wifi_pineapple</font>

</pre>
</td></tr></table>
</body>
</html>
