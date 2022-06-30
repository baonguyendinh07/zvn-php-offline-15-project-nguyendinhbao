<?php
$pathSliderImg = FILES_URL . 'slider' . DS;
$data    = XML::getContentXML('sliders.xml') ?? '';

if (!empty($data)) {
    $slider     = '';
    foreach ($data as $key => $value) {
        $picture    = !empty($value) ? $pathSliderImg . $value : $pathSliderImg . 'default.jpg';
        $link         = '';
        $slider    .= '
        <div>
            <a href="' . $link . '" class="home text-center">
                <img src="' . $picture . '" alt="" class="bg-img blur-up lazyload">
            </a>
        </div>';
    }
}
