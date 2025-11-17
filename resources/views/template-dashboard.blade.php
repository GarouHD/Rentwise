{{-- 
  Template Name: Dashboard
  Template Post Type: page
--}}

@extends('layouts.app')

@section('content')
  @php
    // Gate: only landlords (and optionally admins) can view
    $u = wp_get_current_user();
    $is_landlord = is_user_logged_in() && in_array('landlord', (array) $u->roles, true);
    $is_admin    = current_user_can('administrator');
    if (!$is_landlord && !$is_admin) {
      wp_safe_redirect(home_url('/login'));
      exit;
    }
  @endphp

  <section class="min-h-full px-4 sm:px-6 md:px-12 lg:px-20 py-4 sm:py-6 md:py-8 space-y-4 sm:space-y-6 md:space-y-8 bg-gradient-to-br from-blue-50 to-indigo-100">

    @include('components.dashboard-header')
    @include('components.add-tenant-modal')
    @include('components.edit-tenant-modal')
    @include('components.active-tenants-list-modal')
    @include('components.monthly-revenue-modal')
    @include('components.overdue-payments-modal')
    @include('components.add-property-modal')
    @include('components.edit-property-modal')
    @include('components.properties-list-modal')
    @include('components.record-payment-modal')
    @include('components.add-expense-modal')
    @include('components.edit-expense-modal')
    @include('components.expenses-list-modal')

    {{-- KPI row (Blade components) --}}
    <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mb-4 sm:mb-6 md:mb-8">
        <x-kpi-card
        title="Active Tenants"
        icon="users"
        color="bg-emerald-100"
        textColor="text-emerald-700"
        metric="active_tenants"
        :clickable="true"
        onClick="showActiveTenantsList()"
        />
        <x-kpi-card
        title="Monthly Revenue"
        icon="dollar"
        color="bg-amber-100"
        textColor="text-amber-700"
        metric="monthly_revenue"
        :clickable="true"
        onClick="showMonthlyRevenue()"
        />
        <x-kpi-card
        title="Overdue Payments"
        icon="alert"
        color="bg-rose-100"
        textColor="text-rose-700"
        metric="overdue_payments"
        :clickable="true"
        onClick="showOverduePayments()"
        />
        <x-kpi-card
        title="Properties"
        icon="building"
        color="bg-sky-100"
        textColor="text-sky-700"
        metric="properties"
        :clickable="true"
        onClick="showPropertiesList()"
        />
    </div>

    {{-- Editor-managed content (ACF blocks, e.g., Tenant Directory) --}}
    @while(have_posts()) @php(the_post()) 
        <div class="prose max-w-none">
            @php(the_content()) 
        </div>
    @endwhile

    {{-- Activity Feed --}}
    @include('components.activity-feed')

  </section>

@endsection