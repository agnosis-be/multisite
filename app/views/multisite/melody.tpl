<!DOCTYPE html>
<html lang="<?=$Lang?>" prefix="schema: http://schema.org/">
<head>
<?=$Head?>
</head>
<body>

<div id="page">

<header>

<!-- googleoff: snippet -->
<nav style="width: 100%;" role="navigation">
<div style="float: left; text-align: left; width: 13%">
<?=$LangAltHref?>
</div>
<div style="float: right; text-align: right; width: 70%">
<?=$Nav?>
</div>
</nav>
<!-- googleon: snippet -->

<h1><span property="schema:headline"><?=$Title?></span> <span id="ipa"><?=$BgTxt?></span></h1>

<div id="bg" resource="http://<?=$URL?>/#me" property="schema:image" content="http://<?=$URL?>/static/file/<?=$BgImg?>"><img src="/static/file/<?=$BgImg?>" alt="<?=$FullName?>"></div>

</header>

<div id="content" role="main">
    <?=$Content?>
</div>

<footer>
<!-- googleoff: snippet -->
<nav style="text-align: center" role="navigation"><?=$Nav?></nav>
<!-- googleon: snippet -->
</footer>

</div>

</body>
</html>
