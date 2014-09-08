<!DOCTYPE HTML>
<html>
<head>
<script>
buttonClick = function(){
document.getElementById('log-in').click();
}
</script>
</head>
<body onload="buttonClick();">
<form accept-charset="UTF-8" action="http://velocity.mashable.com/user_sessions" method="post" target="m360ivelocity-iframe">
<div><input name="utf8" type="hidden" value="?"><input name="authenticity_token" type="hidden" value="iPYlDNMl8yf0fc27u9yEf/e2X3evK1ljUm4Yjf/g/sk="></div>
<div class="email wrap"><input class="email" type="hidden" id="email" name="email" type="text" value="david.spektor@360i.com"></div>
<div class="password wrap"><input class="password" type="hidden" id="password" name="password" type="password" value="5Wd4iBxwB2"></div>
<input class="button" name="commit" type="submit" id="log-in" value="Log In" style="display:none;">
</form>

<iframe name="m360ivelocity-iframe" id="m360ivelocity-iframe" src="" height="900px" width="1080px"></iframe>

</body>
</html>