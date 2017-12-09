<?php

function normalize($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES);
}
