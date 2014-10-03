<?php if(isset($User["sip"]) && ($User["sip"] == 'c00800_000' || $User["sip"] == 'c00800_002')) { ?>

<script language="Javascript" type="text/javascript">
<!--
/* <![CDATA[ */
var pid="s00000011866009"; 
var so = "<?php echo $User["id"];?>";
var si = "1.1.1.a8";
if (location.protocol == "https:") { var protocol = "https:"} else { var protocol = "http:" }
document.write("<img width=1 height=1 border=0 src='" + protocol + "//px.a8.net/cgi-bin/a8fly/sales?pid=" + pid + "&so=" + so + "&si=" + si + "'>");
/* ]]> */
//-->
</script>

<?php } ?>
