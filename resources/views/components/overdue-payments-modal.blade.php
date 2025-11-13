{{-- Overdue Payments Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="overduePaymentsModal"
      onclick="if (event.target === this) hideOverduePayments()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all duration-300 scale-100 max-h-[90vh] flex flex-col">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <div>
        <h2 class="text-lg font-semibold text-slate-800">Overdue Payments</h2>
        <p class="text-sm text-slate-500 mt-1">Tenants with outstanding payments</p>
      </div>
      <button type="button" onclick="hideOverduePayments()" class="text-slate-500 hover:text-slate-700">✕</button>
    </div>

    {{-- Overdue Payments List --}}
    <div class="p-6 overflow-y-auto">
      @php
        // Get all tenants for the current landlord
        $tenants = rentwise_get_all_tenants();
        $overdue_tenants = [];
        $total_overdue = 0;
        
        // Find tenants with overdue payments
        foreach ($tenants as $tenant) {
          $payment_status = get_field('payment_status', $tenant->ID);
          $rent = (float) get_field('rent_amount', $tenant->ID);
          $last_payment = get_field('last_payment_date', $tenant->ID);
          $days_overdue = get_field('days_overdue', $tenant->ID);
          
          // Check if payment is overdue (you can adjust this logic based on your fields)
          if ($payment_status === 'overdue' || $days_overdue > 0) {
            $overdue_tenants[] = [
              'tenant' => $tenant,
              'rent' => $rent,
              'days_overdue' => $days_overdue ?: 0,
              'last_payment' => $last_payment,
            ];
            $total_overdue += $rent;
          }
        }
        
        // Sort by days overdue (most overdue first)
        usort($overdue_tenants, function($a, $b) {
          return $b['days_overdue'] <=> $a['days_overdue'];
        });
      @endphp

      {{-- Total Overdue Summary --}}
      @if (!empty($overdue_tenants))
        <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-xl p-6 border border-rose-200 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-rose-700 font-medium mb-1">Total Overdue Amount</p>
              <p class="text-3xl font-bold text-rose-900">${{ number_format($total_overdue, 2) }}</p>
              <p class="text-sm text-rose-600 mt-1">From {{ count($overdue_tenants) }} {{ count($overdue_tenants) === 1 ? 'tenant' : 'tenants' }}</p>
            </div>
            <div class="bg-rose-100 rounded-full p-4">
              <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
              </svg>
            </div>
          </div>
        </div>

        <h3 class="text-sm font-semibold text-slate-700 mb-4 uppercase tracking-wide">Overdue Tenants</h3>
        <div class="space-y-3">
          @foreach ($overdue_tenants as $item)
            @php
              $tenant = $item['tenant'];
              $rent = $item['rent'];
              $days_overdue = $item['days_overdue'];
              $name = get_field('name', $tenant->ID) ?: $tenant->post_title;
              $email = get_field('email', $tenant->ID) ?: '';
              $phone = get_field('phone', $tenant->ID) ?: '';
              $property = get_field('property', $tenant->ID);
              $property_name = $property ? get_field('name', $property) : 'No property assigned';
              
              // Determine urgency level
              $urgency_class = 'bg-yellow-50 border-yellow-300';
              $urgency_badge_class = 'bg-yellow-100 text-yellow-800';
              $urgency_text = 'Recently Overdue';
              
              if ($days_overdue > 30) {
                $urgency_class = 'bg-rose-50 border-rose-300';
                $urgency_badge_class = 'bg-rose-100 text-rose-800';
                $urgency_text = 'Critically Overdue';
              } elseif ($days_overdue > 14) {
                $urgency_class = 'bg-orange-50 border-orange-300';
                $urgency_badge_class = 'bg-orange-100 text-orange-800';
                $urgency_text = 'Seriously Overdue';
              }
            @endphp

            <div class="bg-white rounded-lg p-4 border-2 {{ $urgency_class }} hover:shadow-lg transition-all cursor-pointer"
                 onclick="showEditTenant({{ $tenant->ID }})">
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-2">
                    <div class="bg-rose-100 rounded-full p-2">
                      <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                    </div>
                    <div class="flex-1">
                      <div class="flex items-center gap-2">
                        <h4 class="font-semibold text-slate-900">{{ $name }}</h4>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $urgency_badge_class }} font-medium">
                          {{ $days_overdue }} days overdue
                        </span>
                      </div>
                      <p class="text-sm text-slate-500">{{ $property_name }}</p>
                    </div>
                  </div>
                  
                  @if($email || $phone)
                    <div class="flex gap-4 text-xs text-slate-600 ml-11">
                      @if($email)
                        <span class="flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                          </svg>
                          {{ $email }}
                        </span>
                      @endif
                      @if($phone)
                        <span class="flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                          </svg>
                          {{ $phone }}
                        </span>
                      @endif
                    </div>
                  @endif
                </div>
                
                <div class="ml-4 text-right">
                  <p class="text-2xl font-bold text-rose-900">${{ number_format($rent, 0) }}</p>
                  <p class="text-xs text-slate-500">owed</p>
                </div>
              </div>

              <div class="flex gap-2 ml-11">
                <button onclick="event.stopPropagation(); alert('Send reminder to {{ $name }}');" 
                        class="text-xs bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition">
                  📧 Send Reminder
                </button>
                <button onclick="event.stopPropagation(); showEditTenant({{ $tenant->ID }});" 
                        class="text-xs bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg hover:bg-slate-300 transition">
                  View Details
                </button>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-12">
          <div class="bg-emerald-100 rounded-full p-4 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <p class="text-lg font-semibold text-slate-900 mb-2">All Caught Up! 🎉</p>
          <p class="text-slate-500">No overdue payments at the moment. Great job!</p>
        </div>
      @endif
    </div>
  </div>
</div>

<script>
function showOverduePayments() {
    const modal = document.getElementById('overduePaymentsModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}

function hideOverduePayments() {
    const modal = document.getElementById('overduePaymentsModal');
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
    #overduePaymentsModal {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    #overduePaymentsModal.flex {
        opacity: 1;
    }
</style>

