{{-- Monthly Revenue Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="monthlyRevenueModal"
      onclick="if (event.target === this) hideMonthlyRevenue()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all duration-300 scale-100 max-h-[90vh] flex flex-col">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <div>
        <h2 class="text-lg font-semibold text-slate-800">Monthly Revenue Breakdown</h2>
        <p class="text-sm text-slate-500 mt-1">Total revenue from all active tenants</p>
      </div>
      <button type="button" onclick="hideMonthlyRevenue()" class="text-slate-500 hover:text-slate-700">✕</button>
    </div>

    {{-- Revenue Breakdown --}}
    <div class="p-6 overflow-y-auto">
      @php
        // Get all tenants for the current landlord
        $tenants = rentwise_get_all_tenants();
        $total_revenue = 0;
        $tenant_revenues = [];
        
        // Calculate revenue per tenant
        foreach ($tenants as $tenant) {
          $rent = (float) get_field('rent_amount', $tenant->ID);
          if ($rent > 0) {
            $tenant_revenues[] = [
              'tenant' => $tenant,
              'rent' => $rent,
            ];
            $total_revenue += $rent;
          }
        }
        
        // Sort by rent amount (highest first)
        usort($tenant_revenues, function($a, $b) {
          return $b['rent'] <=> $a['rent'];
        });
      @endphp

      {{-- Total Revenue Summary --}}
      <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-xl p-6 border border-amber-200 mb-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-amber-700 font-medium mb-1">Total Monthly Revenue</p>
            <p class="text-3xl font-bold text-amber-900">${{ number_format($total_revenue, 2) }}</p>
            <p class="text-sm text-amber-600 mt-1">From {{ count($tenant_revenues) }} {{ count($tenant_revenues) === 1 ? 'tenant' : 'tenants' }}</p>
          </div>
          <div class="bg-amber-100 rounded-full p-4">
            <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      {{-- Revenue by Tenant --}}
      @if (!empty($tenant_revenues))
        <h3 class="text-sm font-semibold text-slate-700 mb-4 uppercase tracking-wide">Revenue by Tenant</h3>
        <div class="space-y-3">
          @foreach ($tenant_revenues as $item)
            @php
              $tenant = $item['tenant'];
              $rent = $item['rent'];
              $name = get_field('name', $tenant->ID) ?: $tenant->post_title;
              $property = get_field('property', $tenant->ID);
              $property_name = $property ? get_field('name', $property) : 'No property assigned';
              
              // Calculate percentage of total
              $percentage = $total_revenue > 0 ? ($rent / $total_revenue) * 100 : 0;
            @endphp

            <div class="bg-white rounded-lg p-4 border border-slate-200 hover:border-amber-300 hover:shadow-md transition-all cursor-pointer"
                 onclick="showEditTenant({{ $tenant->ID }})">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-2">
                    <div class="bg-amber-100 rounded-full p-2">
                      <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                    </div>
                    <div>
                      <h4 class="font-semibold text-slate-900">{{ $name }}</h4>
                      <p class="text-sm text-slate-500">{{ $property_name }}</p>
                    </div>
                  </div>
                  
                  {{-- Progress Bar --}}
                  <div class="relative w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-amber-400 to-amber-600 rounded-full transition-all duration-500"
                         style="width: {{ $percentage }}%"></div>
                  </div>
                  <p class="text-xs text-slate-500 mt-1">{{ number_format($percentage, 1) }}% of total revenue</p>
                </div>
                
                <div class="ml-6 text-right">
                  <p class="text-2xl font-bold text-slate-900">${{ number_format($rent, 0) }}</p>
                  <p class="text-xs text-slate-500">per month</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-12">
          <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p class="text-slate-500 mb-4">No revenue data yet</p>
          <button type="button" onclick="hideMonthlyRevenue(); showAddTenant();"
                  class="rounded-lg bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700 transition shadow-md">
            Add Your First Tenant
          </button>
        </div>
      @endif
    </div>
  </div>
</div>

<script>
function showMonthlyRevenue() {
    const modal = document.getElementById('monthlyRevenueModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}

function hideMonthlyRevenue() {
    const modal = document.getElementById('monthlyRevenueModal');
    if (modal) {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
}
</script>
<style>
    #monthlyRevenueModal {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    #monthlyRevenueModal.flex {
        opacity: 1;
    }
</style>

