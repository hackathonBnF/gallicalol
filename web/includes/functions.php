<?php

function base64_to_bin($base64) {
    list($type, $data) = explode(';', $base64);
    list(, $data)      = explode(',', $data);

    return base64_decode($data);
}

// @link http://stackoverflow.com/questions/191845/how-to-store-images-in-your-filesystem
function int_to_filepath($id) {
    $dir = strrev((string) $id);

    if (strlen($dir) % 2 !== 0) {
        $dir = str_pad($dir, (strlen($dir) + 1), "0", STR_PAD_LEFT);
    }

    $parts = str_split($dir, 2);

    return implode('/', $parts);
}
