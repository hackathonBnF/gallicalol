<?php

function base64_to_bin($base64) {
    list($type, $data) = explode(';', $base64);
    list(, $data)      = explode(',', $data);

    return base64_decode($data);
}
