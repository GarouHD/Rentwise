@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  @while(have_posts()) @php(the_post())
    @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
  @endwhile

  <div class="text-red-400 font-bold text-5xl">Tailwind is working if this is red and bold</div>
  @include('blocks.tenant-grid.view')
  @include('components.Dashboard-header')
  @include('components.add-tenant-modal')
  @include('components.tenant-info-modal')
  
  {!! get_the_posts_navigation() !!}
@endsection

