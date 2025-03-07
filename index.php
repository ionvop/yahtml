<?php

$url = $_SERVER['REQUEST_URI'];
$url = explode("?", $url)[0];

if (file_exists("src/routes{$url}index.yaml") == false) {
    http_response_code(404);
    exit;
}

$style = "";
$script = "";
$globalStyle = "";
$globalScript = "";
$data = file_get_contents("src/routes{$url}index.yaml");
$data = yaml_parse($data);

if (file_exists("src/routes{$url}style.yaml")) {
    $style = file_get_contents("src/routes{$url}style.yaml");
    $style = yaml_parse($style);
}

if (file_exists("src/routes{$url}script.js")) {
    $script = file_get_contents("src/routes{$url}script.js");
}

if (file_exists("src/style.yaml")) {
    $globalStyle = file_get_contents("src/style.yaml");
    $globalStyle = yaml_parse($globalStyle);
}

if (file_exists("src/script.js")) {
    $globalScript = file_get_contents("src/script.js");
}

function RenderContent($data) {
    if (is_array($data)) {
        if (array_is_list($data)) {
            foreach ($data as $element) {
                RenderContent($element);
            }

            return;
        }

        $tag = array_keys($data)[0];
        $element = $data[$tag];

        if ($tag == "import") {
            $data = file_get_contents("src/{$element}");
            $data = yaml_parse($data);
            echo RenderContent($data);
            return;
        }

        echo "<{$tag} ";

        if (is_array($element) == false) {
            echo ">";
            echo $element;
            echo "</{$tag}>";
            return;
        }

        foreach ($element as $key => $value) {
            if ($key == "_") {
                continue;
            }

            if ($key == "style") {
                echo "style=\"";

                foreach ($value as $attribute => $attributeValue) {
                    echo "{$attribute}: {$attributeValue}; ";
                }

                echo "\" ";
                continue;
            }

            $value = htmlentities($value);
            echo "{$key}=\"{$value}\" ";
        }

        echo ">";

        if (isset($element["_"])) {
            RenderContent($element["_"]);
        }

        echo "</{$tag}>";
        return;
    }

    echo $data;
}

function RenderStyles($style) {
    if (is_array($style)) {
        foreach ($style as $selector => $styles) {
            $selector = str_replace("|", ":", $selector);
            echo "{$selector} {";

            foreach ($styles as $attribute => $attributeValue) {
                echo "{$attribute}: {$attributeValue}; ";
            }

            echo "} ";
        }
    }
}

function SetBaseHref($url) {
    for ($i = 1; $i < substr_count($url, "/"); $i++) {
        echo "../";
    }
}

?>

<html>
    <head>
        <base href="<?php SetBaseHref($url); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            <?php RenderStyles($globalStyle); ?>
            <?php RenderStyles($style); ?>
        </style>
    </head>
    <body>
        <?php RenderContent($data); ?>
    </body>
    <script>
        <?php echo $globalScript; ?>
        <?php echo $script; ?>
    </script>
</html>