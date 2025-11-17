{{-- Active Tenants List Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="activeTenantsListModal"
      onclick="if (event.target === this) hideActiveTenantsList()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all duration-300 scale-100 max-h-[90vh] flex flex-col">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-200 px-4 sm:px-6 py-3 sm:py-4 gap-2 sm:gap-3">
      <h2 class="text-base sm:text-lg font-semibold text-slate-800">Active Tenants</h2>
      <div class="flex items-center gap-2 sm:gap-3">
        <button type="button" onclick="showAddTenant()" 
                class="rounded-lg bg-indigo-600 px-3 sm:px-4 py-2 text-white text-xs sm:text-sm font-medium hover:bg-indigo-700 transition shadow-md whitespace-nowrap">
          + Add Tenant
        </button>
        <button type="button" onclick="hideActiveTenantsList()" class="text-slate-500 hover:text-slate-700 text-xl">✕</button>
      </div>
    </div>

    {{-- Tenants List --}}
    <div class="p-4 sm:p-6 overflow-y-auto">
      @php
        // Get all tenants for the current landlord
        $tenants = rentwise_get_all_tenants();
      @endphp

      @if (!empty($tenants))
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
          @foreach ($tenants as $tenant)
            @php
              $name = get_field('name', $tenant->ID) ?: $tenant->post_title;
              $email = get_field('email', $tenant->ID) ?: '';
              $phone = get_field('phone', $tenant->ID) ?: '';
              $rent = get_field('rent', $tenant->ID) ?: 0;
              $property = get_field('property', $tenant->ID);
              $property_name = $property ? get_field('name', $property) : '';
              
              // Format rent
              $formatted_rent = '$' . number_format($rent, 0);
            @endphp

            <div onclick="showEditTenant({{ $tenant->ID }})"
                 class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-5 border border-emerald-200 hover:shadow-lg transition-all cursor-pointer group">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h3 class="font-semibold text-slate-900 text-lg mb-1 group-hover:text-indigo-600 transition">
                    {{ $name }}
                  </h3>
                  
                  @if($email)
                    <p class="text-sm text-slate-600 mb-1">
                      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                      </svg>
                      {{ $email }}
                    </p>
                  @endif

                  @if($phone)
                    <p class="text-sm text-slate-600 mb-2">
                      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                      {{ $phone }}
                    </p>
                  @endif

                  <div class="flex gap-3 text-xs text-slate-500 mt-2">
                    @if($rent > 0)
                      <span class="bg-white px-2 py-1 rounded-md font-medium">{{ $formatted_rent }}/mo</span>
                    @endif
                    @if($property_name)
                      <span class="bg-white px-2 py-1 rounded-md">{{ $property_name }}</span>
                    @endif
                  </div>
                </div>
                <div class="ml-3">
                  <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-12">
          <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
          <p class="text-slate-500 mb-4">No active tenants yet</p>
          <button type="button" onclick="hideActiveTenantsList(); showAddTenant();"
                  class="rounded-lg bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700 transition shadow-md">
            Add Your First Tenant
          </button>
        </div>
      @endif
    </div>
  </div>
</div>

<script>
function showActiveTenantsList() {
    const modal = document.getElementById('activeTenantsListModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}

function hideActiveTenantsList() {
    const modal = document.getElementById('activeTenantsListModal');
    if (modal) {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
}

function showAddTenant() {
    hideActiveTenantsList();
    const modal = document.getElementById('addTenantModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}
</script>
<style>
    #activeTenantsListModal {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    #activeTenantsListModal.flex {
        opacity: 1;
    }
</style>

