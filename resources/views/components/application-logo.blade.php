@php
    $logo = asset('/images/svr.png');
    $width = isset($width) && !empty($width) ? $width : '' ;
    $height = isset($height) && !empty($height) ? $height : '' ;
@endphp 

<img src="{{ $logo }}" alt="logo img" width="{{ $width }}" height="{{ $height }}">
