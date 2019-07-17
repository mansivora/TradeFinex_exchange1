<?php

return [

    'characters' => '2346789abcdefghjmnpqrtuxyz',

    'default'   => [
        'length'    => 5,
        'width'     => 201,
        'height'    => 65,
        'quality'   => 0,
        'sensitive' => true,
        'lines'     => -1,
        'bgImage'   => true,
        'bgColor'   => '#ffffff',
        'fontColors' => ['#000000'],
        'contrast'  => 0,
        'angle' => 15,
        'blur' => 0,
    ],

    'flat'   => [
        'length'    => 6,
        'width'     => 160,
        'height'    => 46,
        'quality'   => 90,
        'lines'     => 6,
        'bgImage'   => false,
        'bgColor'   => '#ecf2f4',
        'fontColors'=> ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#8e44ad', '#795548'],
        'contrast'  => -5,
    ],

    'mini'   => [
        'length'    => 3,
        'width'     => 60,
        'height'    => 32,
    ],

    'inverse'   => [
        'length'    => 5,
        'width'     => 120,
        'height'    => 36,
        'quality'   => 90,
        'sensitive' => true,
        'angle'     => 12,
        'sharpen'   => 10,
        'blur'      => 2,
        'invert'    => true,
        'contrast'  => -5,
    ]

];
