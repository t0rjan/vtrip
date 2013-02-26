<?php
define('ML_IMG_SIZE_BIG', 'big');
define('ML_IMG_SIZE_PINBOARD', 'pin');
define('ML_IMG_SIZE_PIC', 'pic');
define('ML_IMG_SIZE_THUMBNAIL', 'thm');
define('ML_IMG_SIZE_SQUARE', 'sqr');
return  array(
        ML_IMG_SIZE_BIG => array(
                'type' => ML_IMG_TYPE_REGULARWIDTH,
                'width' => 1024,
                'def_path' => ''
            ),
        ML_IMG_SIZE_PINBOARD => array(
                'type' => ML_IMG_TYPE_REGULARWIDTH,
                'width' => 220,
                'def_path' => ''
            ),
        ML_IMG_SIZE_PIC => array(
                'type' => ML_IMG_TYPE_REGULARWIDTH,
                'width' => 420,
                'def_path' => ''
            ),
        ML_IMG_SIZE_THUMBNAIL => array(
                'type' => ML_IMG_TYPE_CROP,
                'width' => 76,
                'height' => 76,
                'def_path' => ''
            ),
        ML_IMG_SIZE_SQUARE => array(
                'type' => ML_IMG_TYPE_CROP,
                'width' => 50,
                'height' => 50,
                'def_path' => ''
            ),
    );