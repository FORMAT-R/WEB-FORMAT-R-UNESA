@php
    $tema = $dept['tema_visual'] ?? 'klasik';
@endphp

@if ($tema == 'klasik')
    @include('departemen.themes.klasik')
@elseif ($tema == 'digital')
    @include('departemen.themes.digital')
@elseif ($tema == 'akademik')
    @include('departemen.themes.akademik')
@elseif ($tema == 'bisnis')
    @include('departemen.themes.bisnis')
@elseif ($tema == 'arch')
    @include('departemen.themes.arch')
@elseif ($tema == 'urban')
    @include('departemen.themes.urban')
@elseif ($tema == 'idcard')
    @include('departemen.themes.idcard')
@else
    @include('departemen.themes.klasik')
@endif