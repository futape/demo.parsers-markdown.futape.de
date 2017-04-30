<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Futape.Parsers / Markdown</title>
        <link href="resources/css/bootstrap.min.css" rel="stylesheet" />
        <link href="resources/css/bootstrap-theme.min.css" rel="stylesheet" />
    </head>
    <body>
        <main class="container">
            <header>
                <h1>Futape.Parsers / Markdown</h1>
            </header>
            
            <h2>Render Markdown</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="markdownSource">Markdown</label>
                    <textarea class="form-control" id="markdownSource" name="markdownSource" rows="20"></textarea>
                </div>
                <div class="form-group">
                    <label for="markdownFile">Markdown File</label>
                    <input type="file" id="markdownFile" name="markdownFile" />
                </div>
                
                <button type="submit" class="btn btn-primary" name="markdownRender">Render</button>
            </form><?php
            
            if (array_key_exists('markdownRender', $_POST)) {
                $markdown = array_key_exists('markdownSource', $_POST) ? $_POST['markdownSource'] : null;
                $file = (array_key_exists('markdownFile', $_FILES) && $_FILES['markdownFile']['error'] === UPLOAD_ERR_OK)
 ? $_FILES['markdownFile']['tmp_name'] : null;
                
                if ($markdown !== null || $file !== null) {
                    include_once './includes/Futape.Parsers/Markdown/Parser.php';
                
                    $parser = new Futape\Parsers\Markdown\Parser();
                
                    if ($file !== null) {
                        $rendered = $parser->renderFile($file);
                    } else {
                        $rendered = $parser->render($markdown);
                    } ?>
                    
                    <h2>Rendered HTML</h2>
                    <div class="well"><?php echo $rendered; ?></div><?php
                }
            } ?>
            
            <footer>
                <p>Source code available on GitHub: <a href="#">Website</a>, <a href="https://github.com/futape/parsers-markdown">Library</a></p>
            </footer>
        </main>
    </body>
</html>
