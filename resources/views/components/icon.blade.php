@props(['name', 'class' => 'h-6 w-6'])
@php
  $name = preg_replace('/[^a-z0-9\-_.]/i', '', $name); // basic safety
@endphp

@if (View::exists("svg.$name"))
  @include("svg.$name", ['class' => $class])
@else
  <span class="inline-block {{ $class }}" aria-hidden="true"></span>
@endif
