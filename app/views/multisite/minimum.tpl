<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?=$Lang?>" prefix="schema: http://schema.org/">
<head>
<?=$Head?>
</head>
<body>

<div id="col1">
    &nbsp;
</div>

<div id="col2">
    <div id="content">
    <!-- googleoff: snippet -->
    <nav id="nav" role="navigation"><?=$Nav?></nav>
    <!-- googleon: snippet -->

    <header>
    <h1 property="schema:headline"><?=$Title?></h1><br>
    <?=$LangAltHref?>
    </header>

    <div role="main"><?=$Content?></div>
    </div>
</div>

<div id="col3">
    &nbsp;
</div>

</body>
</html>
