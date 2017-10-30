<?php
function site_url()
{
    return rtrim(getenv('SITE_URL'), '/') . '/';
}

function site_path()
{
    static $_path;

    if (!$_path) {
        $_path = rtrim(parse_url(getenv('SITE_URL'), PHP_URL_PATH), '/');
    }

    return $_path;
}

function img_url()
{
    return rtrim(getenv('SITE_URL'), '/') . '/';
}

function img_path()
{
    return rtrim(getenv('IMG_PATH'), '/') . '/';
}

function dispatch()
{
    $path = $_SERVER['REQUEST_URI'];

    if (getenv('SITE_URL') !== null) {
        $path = preg_replace('@^' . preg_quote(site_path()) . '@', '', $path);
    }

    $parts = preg_split('/\?/', $path, -1, PREG_SPLIT_NO_EMPTY);

    $uri = trim($parts[0], '/');

    if ($uri == 'index.php' || $uri == '') {
        $uri = 'site';
    }

    return $uri;
}

function getUrlFile()
{
    $uri    = dispatch();
    $getUri = explode("/", $uri);

    if ($getUri[0] == 'api') {
        $file = 'routes/' . (isset($getUri[1]) ? $getUri[1] : 'sites') . '.php';

        if (file_exists($file)) {
            return $file;
        }
    } else {

        $file = 'routes/' . $getUri[0] . '.php';

        if (file_exists($file)) {
            return $file;
        }
    }

    return 'routes/sites.php';
}

/** SLIM RESPONSE FUNCTION */

function successResponse($response, $message)
{
    return $response->withJson([
        'status_code' => 200,
        'data'        => $message,
    ], 200);
}

function unprocessResponse($response, $message)
{
    return $response->withJson([
        'status_code' => 422,
        'errors'      => $message,
    ], 422);
}

function unauthorizedResponse($response, $message)
{
    return $response->withJson([
        'status_code' => 403,
        'errors'      => $message,
    ], 403);
}

/** END RESPONSE */

function normalizeChars()
{
    return array(
        'ï¿½' => 'S', 'ï¿½' => 's', 'ï¿½' => 'Dj', 'ï¿½' => 'Z', 'ï¿½' => 'z', 'ï¿½'  => 'A', 'ï¿½' => 'A', 'ï¿½' => 'A', 'ï¿½' => 'A', 'ï¿½' => 'A',
        'ï¿½' => 'A', 'ï¿½' => 'A', 'ï¿½' => 'C', 'ï¿½'  => 'E', 'ï¿½' => 'E', 'ï¿½'  => 'E', 'ï¿½' => 'E', 'ï¿½' => 'I', 'ï¿½' => 'I', 'ï¿½' => 'I',
        'ï¿½' => 'I', 'ï¿½' => 'N', 'ï¿½' => 'O', 'ï¿½'  => 'O', 'ï¿½' => 'O', 'ï¿½'  => 'O', 'ï¿½' => 'O', 'ï¿½' => 'O', 'ï¿½' => 'U', 'ï¿½' => 'U',
        'ï¿½' => 'U', 'ï¿½' => 'U', 'ï¿½' => 'Y', 'ï¿½'  => 'B', 'ï¿½' => 'Ss', 'ï¿½' => 'a', 'ï¿½' => 'a', 'ï¿½' => 'a', 'ï¿½' => 'a', 'ï¿½' => 'a',
        'ï¿½' => 'a', 'ï¿½' => 'a', 'ï¿½' => 'c', 'ï¿½'  => 'e', 'ï¿½' => 'e', 'ï¿½'  => 'e', 'ï¿½' => 'e', 'ï¿½' => 'i', 'ï¿½' => 'i', 'ï¿½' => 'i',
        'ï¿½' => 'i', 'ï¿½' => 'o', 'ï¿½' => 'n', 'ï¿½'  => 'o', 'ï¿½' => 'o', 'ï¿½'  => 'o', 'ï¿½' => 'o', 'ï¿½' => 'o', 'ï¿½' => 'o', 'ï¿½' => 'u',
        'ï¿½' => 'u', 'ï¿½' => 'u', 'ï¿½' => 'u', 'ï¿½'  => 'y', 'ï¿½' => 'y', 'ï¿½'  => 'b', 'ï¿½' => 'y', 'ï¿½' => 'f',
    );
}

function urlParsing($string)
{
    $arrDash = array("--", "---", "----", "-----");
    $string  = strtolower(trim($string));
    $string  = strtr($string, normalizeChars());
    $string  = preg_replace('/[^a-zA-Z0-9 -.]/', '', $string);
    $string  = str_replace(" ", "-", $string);
    $string  = str_replace("&", "", $string);
    $string  = str_replace(array("'", "\"", "&quot;"), "", $string);
    $string  = str_replace($arrDash, "-", $string);
    return str_replace($arrDash, "-", $string);
}

function base64ToFile($base64, $path, $custom_name = null)
{
    if (isset($base64['base64'])) {
        $extension = substr($base64['filename'], strrpos($base64['filename'], ",") + 1);

        if (!empty($custom_name)) {
            $nama = $custom_name . '.' . $extension;
        } else {
            $nama = $base64['filename'];
        }

        $file = base64_decode($base64['base64']);
        file_put_contents($path . '/' . $nama, $file);

        return [
            'fileName' => $nama,
            'filePath' => $path . '/' . $nama,
        ];
    } else {
        return [
            'fileName' => '',
            'filePath' => '',
        ];
    }
}

function namaBulan($index)
{
    $index = (int) $index;
    $bulan = array(1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    );

    $bln = isset($bulan[$index]) ? $bulan[$index] : '';

    return $bln;
}

function terbilang($x)
{
    $x     = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
        "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x < 12) {
        $temp = " " . $angka[$x];
    } else if ($x < 20) {
        $temp = terbilang($x - 10) . " belas";
    } else if ($x < 100) {
        $temp = terbilang($x / 10) . " puluh" . terbilang($x % 10);
    } else if ($x < 200) {
        $temp = " seratus" . terbilang($x - 100);
    } else if ($x < 1000) {
        $temp = terbilang($x / 100) . " ratus" . terbilang($x % 100);
    } else if ($x < 2000) {
        $temp = " seribu" . terbilang($x - 1000);
    } else if ($x < 1000000) {
        $temp = terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
    } else if ($x < 1000000000) {
        $temp = terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
    } else if ($x < 1000000000000) {
        $temp = terbilang($x / 1000000000) . " milyar" . terbilang(fmod($x, 1000000000));
    } else if ($x < 1000000000000000) {
        $temp = terbilang($x / 1000000000000) . " trilyun" . terbilang(fmod($x, 1000000000000));
    }
    return $temp;
}

function rp($price = 0, $prefix = true, $decimal = 0)
{
    if ($price === '-' || empty($price)) {
        return '';
    } else {
        if ($prefix === "-") {
            return $price;
        } else {
            $rp = ($prefix) ? 'Rp. ' : '';

            if ($price < 0) {
                $price  = (float) $price * -1;
                $result = '(' . $rp . number_format($price, $decimal, ",", ".") . ')';
            } else {
                $price  = (float) $price;
                $result = $rp . number_format($price, $decimal, ",", ".");
            }
            return $result;
        }
    }
}

function partial($view, $locals = null)
{

    if (is_array($locals) && count($locals)) {
        extract($locals, EXTR_SKIP);
    }

    $path = basename($view);
    $view = preg_replace('/' . $path . '$/', "_{$path}", $view);
    $view = "views/{$view}.php";

    if (file_exists($view)) {
        ob_start();
        require $view;
        return ob_get_clean();
    } else {
        error(500, "partial [{$view}] not found");
    }

    return '';
}

function content($value = null)
{
    return stash('$content$', $value);
}

function render($view, $locals = null, $layout = null)
{

    if (is_array($locals) && count($locals)) {
        extract($locals, EXTR_SKIP);
    }

    ob_start();
    include "views/{$view}.php";
    content(trim(ob_get_clean()));

    if ($layout !== false) {

        if ($layout == null) {
            $layout = ($layout == null) ? 'mainSingle' : $layout;
        }

        $layout = "views/layout/{$layout}.php";

        header('Content-type: text/html; charset=utf-8');

        ob_start();
        require $layout;
        echo trim(ob_get_clean());
    } else {
        echo content();
    }
}

function stash($name, $value = null) {

    static $_stash = array();

    if ($value === null)
        return isset($_stash[$name]) ? $_stash[$name] : null;

    $_stash[$name] = $value;

    return $value;
} 