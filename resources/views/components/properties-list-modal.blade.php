{{-- Properties List Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="propertiesListModal"
      onclick="if (event.target === this) hidePropertiesList()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all duration-300 scale-100 max-h-[90vh] flex flex-col">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-slate-800">My Properties</h2>
      <div class="flex items-center gap-3">
        <button type="button" onclick="showAddProperty()" 
                class="rounded-lg bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700 transition shadow-md">
          + Add Property
        </button>
        <button type="button" onclick="hidePropertiesList()" class="text-slate-500 hover:text-slate-700">✕</button>
      </div>
    </div>

    {{-- Properties List --}}
    <div class="p-6 overflow-y-auto">
      @php
        // Get all properties for the current landlord
        $properties = rentwise_get_all_properties();
      @endphp

      @if (!empty($properties))
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
          @foreach ($properties as $property)
            @php
              $name = get_field('name', $property->ID) ?: $property->post_title;
              $address = get_field('address', $property->ID) ?: '';
              $units = get_field('units', $property->ID) ?: 0;
              $type = get_field('type', $property->ID) ?: 'apartment';
              
              // Property type labels
              $typeLabels = [
                'apartment' => 'Apartment Building',
                'house' => 'Single Family Home',
                'condo' => 'Condo',
                'townhouse' => 'Townhouse',
                'commercial' => 'Commercial',
                'other' => 'Other'
              ];
              $typeLabel = $typeLabels[$type] ?? 'Property';
            @endphp

            <div onclick="showPropertyDetails({{ $property->ID }})"
                 class="bg-gradient-to-br from-sky-50 to-blue-50 rounded-xl p-5 border border-sky-200 hover:shadow-lg transition-all cursor-pointer group">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h3 class="font-semibold text-slate-900 text-lg mb-1 group-hover:text-indigo-600 transition">
                    {{ $name }}
                  </h3>
                  <p class="text-sm text-slate-600 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $address }}
                  </p>
                  <div class="flex gap-3 text-xs text-slate-500">
                    <span class="bg-white px-2 py-1 rounded-md">{{ $typeLabel }}</span>
                    @if($units > 0)
                      <span class="bg-white px-2 py-1 rounded-md">{{ $units }} units</span>
                    @endif
                  </div>
                </div>
                <div class="ml-3">
                  <svg class="w-10 h-10 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-12">
          <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
          <p class="text-slate-500 mb-4">No properties yet</p>
          <button type="button" onclick="hidePropertiesList(); showAddProperty();"
                  class="rounded-lg bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700 transition shadow-md">
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

