<?php
$allowed_sizes = array(
    '75x75', '50x50', '100x100', '150x150', '200x200', '250x250', '120x120'
);

$_dir = dirname($_REQUEST['src']);
if (strpos($_dir, '/images') > 0) {
    $_dir = substr($_dir, strpos($_dir, '/images') + 1);
}
$_file = substr($_REQUEST['src'], strrpos($_REQUEST['src'], '/') + 1, strrpos($_REQUEST['src'], '.') - (strrpos($_REQUEST['src'], '/') + 1));
$_type = substr($_REQUEST['src'], strrpos($_REQUEST['src'], '.') + 1);

list($file, $wh, $method, $bgcolor) = explode('_', $_file);
if (!in_array($wh, $allowed_sizes)) {
    die('Недопустимый размер изображения!');
}
$src = $_dir . '/' . $file . '.' . $_type;
if (!file_exists($src)) {
    $src = 'images/no_image.jpg';
}

$cache = $_dir . '/cache/' . $_file . '.' . $_type;


if (!file_exists($cache) || filemtime($cache) < filemtime($src)) {
    $_type = substr($src, strrpos($src, '.') + 1);
    $_type = strtr($_type, 'ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', 'abcdefghijklmnopqrstuvwxyzабвгдеёжзийклмнопрстуфхцчшщъыьэюя');
    switch ($_type) {
        case 'jpg':
        case 'jpeg':
            //header('Content-type: image/jpeg');
            if (trim($wh) == '') {
                $img_size = getimagesize($src);
                $wh = $img_size[0] . 'x' . $img_size[1];
            }

            list($w, $h) = explode('x', $wh);
            if ($w >= 1024 || $h >= 1024) {
                die('Слишком большой размер картинки');
            }
            $img = imagecreatefromjpeg($src);
            $img = imageSmartResize($img, $w, $h, $method, $bgcolor);
            imagejpeg($img, $cache);
            break;
        case 'gif':
            //header('Content-type: image/gif');
            if (trim($wh) == '') {
                readfile($src);
                die;
            } else {
                list($w, $h) = explode('x', $wh);
                if ($w >= 1024 || $h >= 1024) {
                    die('Слишком большой размер картинки');
                }
            }
            $img = imagecreatefromgif($src);
            $img = imageSmartResize($img, $w, $h, $method, $bgcolor);
            imagegif($img, $cache);
            break;
        case 'png':
            //header('Content-type: image/png');
            if (trim($wh) == '') {
                readfile($src);
                die;
            } else {
                list($w, $h) = explode('x', $wh);
                if ($w >= 1024 || $h >= 1024) {
                    die('Слишком большой размер картинки');
                }
            }
            $img = imagecreatefrompng($src);
            $img = imageSmartResize($img, $w, $h, $method, $bgcolor);
            imagepng($img, $cache);
            break;
    }
}
$_type = strtr($_type, 'ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', 'abcdefghijklmnopqrstuvwxyzабвгдеёжзийклмнопрстуфхцчшщъыьэюя');

echo $cache;
return;

switch ($_type) {
    case 'jpg':
    case 'jpeg':
        header('Content-type: image/jpeg');
        break;
    case 'gif':
        header('Content-type: image/gif');
        break;
    case 'png':
        header('Content-type: image/png');
        break;
}
readfile($cache);
die;

function imageSmartResize($imSrc, $dstW, $dstH, $method = 2, $bkgColorHex = 'FFFFFF')
{
    /*
    method=1 - обрезать картинку
    method=2 - уменьшать картинку, добавлять белое поле
    method=3 - уменьшать картинку
    */
    if (trim($method) == '') {
        $method = 3;
    }
    if (trim($bkgColorHex) == '') {
        $bkgColorHex = 'FFFFFF';
    }

    $bkgColor = array();
    for ($i = 0; $i < 6; $i += 2) {
        $bkgColor[] = hexdec(substr($bkgColorHex, $i, 2));
    }

    $srcX = $srcY = $destX = $destY = 0;

    $w = imagesx($imSrc);
    $h = imagesy($imSrc);

    switch ($method) {
        case 3:
            if ($w / $dstW < $h / $dstH) {
                $destH = $dstH;
                $destW = ceil($w * $dstH / $h);
            } else {
                $destW = $dstW;
                $destH = ceil($h * $dstW / $w);
            }
            $imDst = imagecreatetruecolor($destW, $destH);
            $srcX = $srcY = 0;
            break;
        case 2:
            $ratio = $w / $dstW > $h / $dstH ? $dstW / $w : $dstH / $h;
            $destW = ceil($w * $ratio);
            $destH = ceil($h * $ratio);
            $destX = ceil(($dstW - $destW) / 2);
            $destY = ceil(($dstH - $destH) / 2);
            $imDst = imagecreatetruecolor($dstW, $dstH);
            break;
        case 1:
        default:
            if ($w / $dstW > $h / $dstH) {
                $ratio = $dstH / $h;
                $srcX = floor(($w - $dstW / $ratio) / 2);
                $srcY = 0;
                $w = floor($dstW / $ratio);
                $h = floor($dstH / $ratio);
            } else {
                $ratio = $dstW / $w;
                $srcX = 0;
                $srcY = floor(($h - $dstH / $ratio) / 2);
                $w = floor($dstW / $ratio);
                $h = floor($dstH / $ratio);
            }
            $destW = ceil($w * $ratio);
            $destH = ceil($h * $ratio);
            $destX = ceil(($dstW - $destW) / 2);
            $destY = ceil(($dstH - $destH) / 2);
            $imDst = imagecreatetruecolor($dstW, $dstH);
            break;
    }
    $bkg = imagecolorallocate($imDst, $bkgColor[0], $bkgColor[1], $bkgColor[2]);
    imagefilledrectangle($imDst, 0, 0, $dstW, $dstH, $bkg);
    imagecopyresampled($imDst, $imSrc, $destX, $destY, $srcX, $srcY, $destW, $destH, $w, $h);
    if ($dstH > 190 && $dstW > 190) {
        $imDst = addon_image($imDst);
    }
    return $imDst;
}

function addon_image($img, $logoImage = 'images/logo_bg.png')
{
    //добавление копирайта
    $logoImage = imagecreatefrompng($logoImage);
    $imgW = imagesx($img);
    $imgH = imagesy($img);
    $logoW = ImageSX($logoImage);
    $logoH = ImageSY($logoImage);
    if ($imgW > $logoW && $imgH > $logoH) {
        imagecopyresampled($img, $logoImage, ($imgW - $logoW), ($imgH - $logoH), 0, 0, $logoW, $logoH, $logoW, $logoH);
    }
    return $img;
}

?>
