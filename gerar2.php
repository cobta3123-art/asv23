<?php
header("Content-Type: image/png");

// Caminhos
$imgPath  = "https://i.ibb.co/DP1xJ3w3/model1.png";
$fontUrl  = "https://asvas2.vercel.app/Chalk%20Board.ttf";
$fontPath = __DIR__ . "/fonts/ChalkBoard.ttf"; // caminho local no servidor

// Se a fonte não existir no servidor, baixa e salva
if (!file_exists($fontPath)) {

    // Cria a pasta /fonts se não existir
    if (!is_dir(__DIR__ . "/fonts")) {
        mkdir(__DIR__ . "/fonts", 0777, true);
    }

    // Baixa a fonte
    $fontData = file_get_contents($fontUrl);

    if (!$fontData) {
        die("❌ Erro ao baixar fonte do Vercel");
    }

    // Salva a fonte localmente
    if (!file_put_contents($fontPath, $fontData)) {
        die("❌ Não consegui salvar a fonte no servidor");
    }
}

// Nome digitado
$texto = $_GET['name'] ?? 'Sem nome';

// Abre imagem
$img = imagecreatefrompng($imgPath);

// Converte para truecolor
$tmp = imagecreatetruecolor(imagesx($img), imagesy($img));
imagecopy($tmp, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
imagedestroy($img);
$img = $tmp;

/* -----------------------------------------------------
   Efeito de escurecimento (ajuda a simular espelho real)
------------------------------------------------------ */
$overlay = imagecreatetruecolor(imagesx($img), imagesy($img));
$cinza = imagecolorallocatealpha($overlay, 0, 0, 0, 110);
imagefill($overlay, 0, 0, $cinza);
imagecopymerge($img, $overlay, 0, 0, 0, 0, imagesx($img), imagesy($img), 20);
imagedestroy($overlay);

/* -----------------------------------------------------
   Cor do texto
------------------------------------------------------ */
$corTexto = imagecolorallocatealpha($img, 178, 55, 55, 30);
$sombra   = imagecolorallocate($img, 20, 20, 20); // sombra

// posição
$x = 200;
$y = 209;
$tamanho = 80;
$rotacao = -8;

/* -----------------------------------------------------
   Sombra
------------------------------------------------------ */
imagettftext(
    $img,
    $tamanho,
    $rotacao,
    $x + 2,
    $y + 2,
    $sombra,
    $fontPath,
    $texto
);

/* -----------------------------------------------------
   Texto principal
------------------------------------------------------ */
imagettftext(
    $img,
    $tamanho,
    $rotacao,
    $x,
    $y,
    $corTexto,
    $fontPath,
    $texto
);

/* -----------------------------------------------------
   Desfoque leve para integrar ao vidro
------------------------------------------------------ */
if (function_exists('imagefilter')) {
    imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
}

// Saída
imagepng($img);
imagedestroy($img);
?>
