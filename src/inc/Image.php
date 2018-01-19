<?php
/** IMAGE FUNCTION */

function createImg($path, $filename, $id, $quality = 100)
{
    $newFileName = urlParsing($filename);
    $small       = $path . $id . '-150x150-' . $newFileName;
    $medium      = $path . $id . '-350x350-' . $newFileName;
    $big         = $path . $id . '-700x700-' . $newFileName;

    if (file_exists($small)) {
        unlink($small);
    }

    if (file_exists($medium)) {
        unlink($medium);
    }

    if (file_exists($big)) {
        unlink($big);
    }

    $file = $path . $filename;

    smart_resize_image($file, $small, 150, 150, $proporsional, $quality);
    smart_resize_image($file, $medium, 350, 350, $proporsional, $quality);
    smart_resize_image($file, $big, 700, 700, $proporsional, $quality);

    unlink($path . $filename);

    return array(
        'big'    => $id . '-700x700-' . $newFileName,
        'medium' => $id . '-350x350-' . $newFileName,
        'small'  => $id . '-150x150-' . $newFileName,
    );
}

function smart_resize_image($file, $newName, $width = 0, $height = 0, $proportional = false, $quality = 100)
{
    $output = 'file';

    if ($height <= 0 && $width <= 0) {
        return false;
    }

    if ($file === null) {
        return false;
    }

    # Setting defaults and meta
    $info                         = getimagesize($file);
    $image                        = '';
    $final_width                  = 0;
    $final_height                 = 0;
    list($width_old, $height_old) = $info;
    $cropHeight                   = $cropWidth                   = 0;

    # Calculating proportionality
    if ($proportional) {
        if ($width == 0) {
            $factor = $height / $height_old;
        } elseif ($height == 0) {
            $factor = $width / $width_old;
        } else {
            $factor = min($width / $width_old, $height / $height_old);
        }

        $final_width  = round($width_old * $factor);
        $final_height = round($height_old * $factor);
    } else {
        $final_width  = ($width <= 0) ? $width_old : $width;
        $final_height = ($height <= 0) ? $height_old : $height;
        $widthX       = $width_old / $width;
        $heightX      = $height_old / $height;

        $x          = min($widthX, $heightX);
        $cropWidth  = ($width_old - $width * $x) / 2;
        $cropHeight = ($height_old - $height * $x) / 2;
    }

    # Loading image to memory according to type
    switch ($info[2]) {
        case IMAGETYPE_JPEG:$image = imagecreatefromjpeg($file);
            break;
        case IMAGETYPE_GIF:$image = imagecreatefromgif($file);
            break;
        case IMAGETYPE_PNG:$image = imagecreatefrompng($file);
            break;
        default:return false;
    }

    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor($final_width, $final_height);
    if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
        $transparency = imagecolortransparent($image);
        $palletsize   = imagecolorstotal($image);
        if ($transparency >= 0 && $transparency < $palletsize) {
            $transparent_color = imagecolorsforindex($image, $transparency);
            $transparency      = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
            imagefill($image_resized, 0, 0, $transparency);
            imagecolortransparent($image_resized, $transparency);
        } elseif ($info[2] == IMAGETYPE_PNG) {
            imagealphablending($image_resized, false);
            $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
            imagefill($image_resized, 0, 0, $color);
            imagesavealpha($image_resized, true);
        }
    }
    imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);

    # Preparing a method of providing result
    switch (strtolower($output)) {
        case 'browser':
            $mime = image_type_to_mime_type($info[2]);
            header("Content-type: $mime");
            $output = null;
            break;
        case 'file':
            $output = $newName;
            break;
        case 'return':
            return $image_resized;
            break;
        default:
            break;
    }

    # Writing image according to type to the output destination and image quality
    switch ($info[2]) {
        case IMAGETYPE_GIF:imagegif($image_resized, $output);
            break;
        case IMAGETYPE_JPEG:imagejpeg($image_resized, $output, $quality);
            break;
        case IMAGETYPE_PNG:
            $quality = 9 - (int) ((0.9 * $quality) / 10.0);
            imagepng($image_resized, $output, $quality);
            break;
        default:return false;
    }
    return true;
}

/** END IMAGE */
