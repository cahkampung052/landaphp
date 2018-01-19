<?php
/** GLOBAL FUNCTION */
function base64ToFile($base64, $path, $custom_name = null)
{
    if (isset($base64['base64'])) {
        $extension = substr($base64['filename'], strrpos($base64['filename'], ",") + 1);

        if (!empty($custom_name)) {
            $nama = $custom_name;
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
    $angka = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp  = "";
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

/** END FUNCTION */
