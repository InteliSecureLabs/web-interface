<?php
if ($_GET['sync_get']=='sync')
{
exec('echo yes |tee sync_get');
}
elseif ($_GET['sync_get']=='unsync')
{
exec('echo no |tee sync_get');
}
?>
<meta http-equiv="REFRESH" content="0;url=index.php">
