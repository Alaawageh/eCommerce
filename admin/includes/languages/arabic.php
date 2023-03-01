<?php

function lang( $phrase ) {
    static $lang = array(
       'MASSAGE' => 'مرحبا',
       'ADMIN' => 'مدير',
    );
    return $lang[$phrase];
}
?>