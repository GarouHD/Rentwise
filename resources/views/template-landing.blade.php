{{--
  Template Name: Landing Page
--}}

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
  {{-- Navigation Bar --}}
  <nav class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="bg-blue-600 rounded-lg p-1.5">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <span class="text-xl font-bold text-gray-900">Rentwise</span>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ wp_login_url() }}" class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2 transition-colors">
            Log In
          </a>
          <a href="{{ wp_registration_url() }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition-colors shadow-sm">
            Sign Up
          </a>
        </div>
      </div>
    </div>
  </nav>

  {{-- Hero Section --}}
  <section class="max-w-7xl mx-auto px-6 py-16 md:py-24">
    <div class="text-center max-w-3xl mx-auto">
      <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-5 leading-tight">
        Manage Your Properties
        <span class="text-blue-600 block mt-2">Smarter, Not Harder</span>
      </h1>
      <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
        Rentwise is the all-in-one property management platform designed for landlords. 
        Track tenants, manage payments, and stay organized—all from one simple dashboard.
      </p>
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ wp_registration_url() }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-7 py-3 rounded-lg text-base transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
          Get Started Free
        </a>
        <a href="{{ wp_login_url() }}" class="bg-white hover:bg-gray-50 text-gray-900 font-semibold px-7 py-3 rounded-lg text-base border-2 border-gray-200 transition-all shadow-sm hover:shadow-md">
          Log In
        </a>
      </div>
    </div>
  </section>

  {{-- Features Section --}}
  <section class="max-w-7xl mx-auto px-6 py-12 md:py-16">
    <div class="text-center mb-10 md:mb-12">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
        Everything You Need to Manage Properties
      </h2>
      <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
        Powerful features designed specifically for landlords and property managers
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {{-- Feature 1: Tenant Management --}}
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all group">
        <div class="bg-blue-50 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Tenant Directory</h3>
        <p class="text-sm text-gray-600 leading-relaxed">
          Keep track of all your tenants in one place. View contact information, rental history, and payment status at a glance.
        </p>
      </div>

      {{-- Feature 2: Payment Tracking --}}
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-green-200 transition-all group">
        <div class="bg-green-50 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors">
          <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Payment Tracking</h3>
        <p class="text-sm text-gray-600 leading-relaxed">
          Monitor rent payments, track overdue accounts, and get notified when payments are due or received.
        </p>
      </div>

      {{-- Feature 3: Property Management --}}
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-200 transition-all group">
        <div class="bg-purple-50 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-4 group-hover:bg-purple-100 transition-colors">
          <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Property Inventory</h3>
        <p class="text-sm text-gray-600 leading-relaxed">
          Organize your properties, track units, and manage maintenance requests all from your dashboard.
        </p>
      </div>

      {{-- Feature 4: Notifications --}}
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-yellow-200 transition-all group">
        <div class="bg-yellow-50 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-4 group-hover:bg-yellow-100 transition-colors">
          <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Smart Notifications</h3>
        <p class="text-sm text-gray-600 leading-relaxed">
          Stay on top of important events with real-time notifications for overdue payments, upcoming due dates, and more.
        </p>
      </div>

      {{-- Feature 5: Easy to Use --}}
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-orange-200 transition-all group">
        <div class="bg-orange-50 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-4 group-hover:bg-orange-100 transition-colors">
          <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Simple & Intuitive</h3>
        <p class="text-sm text-gray-600 leading-relaxed">
          No complicated setup required. Get started in minutes and manage your properties with ease.
        </p>
      </div>

      {{-- Feature 6: Secure --}}
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-red-200 transition-all group">
        <div class="bg-red-50 rounded-lg p-3 w-12 h-12 flex items-center justify-center mb-4 group-hover:bg-red-100 transition-colors">
          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Secure & Private</h3>
        <p class="text-sm text-gray-600 leading-relaxed">
          Your data is protected with enterprise-grade security. Your information stays private and secure.
        </p>
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="max-w-7xl mx-auto px-6 py-12 md:py-16">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-8 md:p-12 text-center text-white shadow-xl">
      <h2 class="text-2xl md:text-3xl font-bold mb-3">
        Ready to Simplify Your Property Management?
      </h2>
      <p class="text-base md:text-lg mb-8 text-blue-100 max-w-2xl mx-auto">
        Join thousands of landlords who trust Rentwise to manage their properties efficiently.
      </p>
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ wp_registration_url() }}" class="bg-white hover:bg-gray-50 text-blue-600 font-semibold px-7 py-3 rounded-lg text-base transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
          Create Free Account
        </a>
        <a href="{{ wp_login_url() }}" class="bg-blue-800/50 hover:bg-blue-800 text-white font-semibold px-7 py-3 rounded-lg text-base border-2 border-white/30 transition-all hover:border-white/50">
          Already Have an Account?
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
