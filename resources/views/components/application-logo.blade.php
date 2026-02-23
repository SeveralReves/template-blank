@php
    $logo = asset('/images/anchor.svg');
    $width = isset($width) && !empty($width) ? $width : '' ;
    $height = isset($height) && !empty($height) ? $height : '' ;
@endphp 

{{-- <img src="{{ $logo }}" alt="logo img" width="{{ $width }}" height="{{ $height }}"> --}}
<div class="logo">
    <div class="logo__icon">
        <img src="{{ $logo }}" alt="logo img" width="{{ $width }}" height="{{ $height }}">
    </div>
    <h1 class="logo__title">PortOps Manager</h1>
</div>