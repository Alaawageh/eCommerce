<?php

function lang( $phrase ) {
    static $lang = array(
       'MASSAGE'    => 'Welcome',
       'ADMIN_HOME' => 'Home',
       'CATEGORIES' => 'Categories',
       'ITEMS'      => 'items',
       'MEMBERS'    => 'Members',
       'COMMENTS'   => 'Comments'
    );
    return $lang[$phrase];
}
?>