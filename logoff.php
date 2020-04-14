<?
session_start();
session_destroy();
print("<script language='JavaScript'>self.location.href=\"index.php\";</script>");
?>