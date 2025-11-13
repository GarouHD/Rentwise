{{-- Property Portfolio Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="propertiesListModal"
      onclick="if (event.target === this) hidePropertiesList()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl transform transition-all duration-300 scale-100 max-h-[90vh] flex flex-col">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h2 class="text-2xl font-bold text-slate-900">Property Portfolio</h2>
      <button type="button" onclick="hidePropertiesList()" class="text-slate-500 hover:text-slate-700 text-2xl">✕</button>
    </div>

    {{-- Properties Grid --}}
    <div class="p-6 overflow-y-auto">
      @php
        // Get all properties for the current landlord
        $properties = rentwise_get_all_properties();
        
        // Define property colors
        $colors = [
          ['bg' => 'bg-blue-500', 'light' => 'bg-blue-50', 'border' => 'border-blue-200'],
          ['bg' => 'bg-green-500', 'light' => 'bg-green-50', 'border' => 'border-green-200'],
          ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50', 'border' => 'border-purple-200'],
          ['bg' => 'bg-orange-500', 'light' => 'bg-orange-50', 'border' => 'border-orange-200'],
          ['bg' => 'bg-pink-500', 'light' => 'bg-pink-50', 'border' => 'border-pink-200'],
          ['bg' => 'bg-cyan-500', 'light' => 'bg-cyan-50', 'border' => 'border-cyan-200'],
        ];
        
        // Portfolio totals
        $total_units = 0;
        $total_occupied = 0;
        $total_revenue = 0;
        $total_value = 0;
      @endphp

      @if (!empty($properties))
        {{-- Property Cards Grid --}}
        <div class="grid gap-6 grid-cols-1 lg:grid-cols-3 mb-8">
          @foreach ($properties as $index => $property)
            @php
              $color = $colors[$index % count($colors)];
              $name = get_field('name', $property->ID) ?: $property->post_title;
              $address = get_field('address', $property->ID) ?: '';
              $units = (int) get_field('units', $property->ID) ?: 0;
              $property_value = (float) get_field('property_value', $property->ID) ?: 0;
              
              // Get tenants for this property
              $property_tenants = rentwise_get_all_tenants([
                'meta_query' => [
                  [
                    'key'   => 'landlord',
                    'value' => wp_get_current_user()->ID,
                    'compare' => '=',
                  ],
                  [
                    'key'   => 'property',
                    'value' => $property->ID,
                    'compare' => '=',
                  ],
                ]
              ]);
              
              $occupied = count($property_tenants);
              $property_revenue = 0;
              
              // Calculate property revenue
              foreach ($property_tenants as $tenant) {
                $property_revenue += (float) get_field('rent_amount', $tenant->ID);
              }
              
              // Add to totals
              $total_units += $units;
              $total_occupied += $occupied;
              $total_revenue += $property_revenue;
              $total_value += $property_value;
            @endphp

            <div onclick="showPropertyDetails({{ $property->ID }})" 
                 class="rounded-2xl p-6 {{ $color['light'] }} border-2 {{ $color['border'] }} hover:shadow-xl transition-all cursor-pointer">
              {{-- Property Header --}}
              <div class="flex items-start gap-4 mb-6">
                <div class="{{ $color['bg'] }} rounded-full p-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="font-bold text-xl text-slate-900 mb-1">{{ $name }}</h3>
                  <p class="text-sm text-slate-600">{{ $address }}</p>
                </div>
              </div>

              {{-- Property Stats --}}
              <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center">
                  <span class="text-slate-600 font-medium">Total Units:</span>
                  <span class="font-bold text-slate-900 text-lg">{{ $units }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-600 font-medium">Occupied:</span>
                  <span class="font-bold text-green-600 text-lg">{{ $occupied }}/{{ $units }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-600 font-medium">Monthly Revenue:</span>
                  <span class="font-bold text-slate-900 text-lg">${{ number_format($property_revenue, 0) }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-600 font-medium">Property Value:</span>
                  <span class="font-bold text-slate-900 text-lg">${{ number_format($property_value, 0) }}</span>
                </div>
              </div>

              {{-- Current Tenants --}}
              @if (!empty($property_tenants))
                <div class="border-t-2 {{ $color['border'] }} pt-4">
                  <h4 class="font-bold text-slate-900 mb-3">Current Tenants:</h4>
                  <div class="space-y-2">
                    @foreach ($property_tenants as $tenant)
                      @php
                        $tenant_name = get_field('name', $tenant->ID) ?: $tenant->post_title;
                        $unit_number = get_field('unit_number', $tenant->ID) ?: 'Unit';
                        $rent = (float) get_field('rent_amount', $tenant->ID);
                      @endphp
                      <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-700">{{ $unit_number }} - {{ $tenant_name }}</span>
                        <span class="font-semibold text-green-600">${{ number_format($rent, 0) }}</span>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>
          @endforeach
        </div>

        {{-- Portfolio Summary --}}
        <div class="border-t-2 border-slate-200 pt-8">
          <h3 class="text-2xl font-bold text-slate-900 mb-6">Portfolio Summary</h3>
          
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Total Units --}}
            <div class="text-center">
              <div class="text-5xl font-bold text-blue-600 mb-2">{{ $total_units }}</div>
              <div class="text-slate-600 font-medium">Total Units</div>
            </div>

            {{-- Occupancy Rate --}}
            <div class="text-center">
              @php
                $occupancy_rate = $total_units > 0 ? round(($total_occupied / $total_units) * 100) : 0;
              @endphp
              <div class="text-5xl font-bold text-green-600 mb-2">{{ $occupancy_rate }}%</div>
              <div class="text-slate-600 font-medium">Occupancy Rate</div>
            </div>

            {{-- Monthly Revenue --}}
            <div class="text-center">
              <div class="text-5xl font-bold text-purple-600 mb-2">${{{ number_format($total_revenue, 0) }}</div>
              <div class="text-slate-600 font-medium">Monthly Revenue</div>
            </div>

            {{-- Total Value --}}
            <div class="text-center">
              @php
                $value_display = $total_value >= 1000000 
                  ? '$' . number_format($total_value / 1000000, 2) . 'M'
                  : '$' . number_format($total_value, 0);
              @endphp
              <div class="text-5xl font-bold text-orange-600 mb-2">{{ $value_display }}</div>
              <div class="text-slate-600 font-medium">Total Value</div>
            </div>
          </div>

          {{-- Action Buttons --}}
          <div class="flex gap-4">
            <button type="button" onclick="event.stopPropagation(); showAddProperty();" 
                    class="rounded-lg bg-blue-600 px-6 py-3 text-white font-semibold hover:bg-blue-700 transition shadow-md">
              Add New Property
            </button>
            <button type="button" onclick="event.stopPropagation(); alert('Generate Report feature coming soon!');" 
                    class="rounded-lg bg-green-600 px-6 py-3 text-white font-semibold hover:bg-green-700 transition shadow-md">
              Generate Report
            </button>
          </div>
        </div>

      @else
        {{-- Empty State --}}
        <div class="text-center py-16">
          <svg class="w-20 h-20 text-slate-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
          <p class="text-xl text-slate-500 mb-6">No properties in your portfolio yet</p>
          <button type="button" onclick="hidePropertiesList(); showAddProperty();"
                  class="rounded-lg bg-blue-600 px-8 py-4 text-white text-lg font-semibold hover:bg-blue-700 transition shadow-md">
            Add Your First Property
          </button>
        </div>
      @endif
    </div>
  </div>
</div>

<script>
function showPropertiesList() {
    const modal = document.getElementById('propertiesListModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}

function hidePropertiesList() {
    const modal = document.getElementById('propertiesListModal');
    if (modal) {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
}

function showAddProperty() {
    hidePropertiesList();
    const modal = document.getElementById('addPropertyModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}

function showPropertyDetails(propertyId) {
    hidePropertiesList();
    const modal = document.getElementById('editPropertyModal');
    if (modal) {
        // Load property data
        fetch(`${window.location.origin}/wp-admin/admin-ajax.php?action=get_property&property_id=${propertyId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('edit_property_id').value = propertyId;
                    document.getElementById('edit_property_name').value = data.data.name || '';
                    document.getElementById('edit_property_address').value = data.data.address || '';
                    document.getElementById('edit_property_units').value = data.data.units || '';
                    document.getElementById('edit_property_type').value = data.data.type || 'apartment';
                    document.getElementById('edit_property_value').value = data.data.property_value || '';
                }
            });
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}
</script>
<style>
    #propertiesListModal {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    #propertiesListModal.flex {
        opacity: 1;
    }
</style>

