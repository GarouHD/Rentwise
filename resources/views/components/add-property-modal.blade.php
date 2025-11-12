{{-- Add Property Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="addPropertyModal"
      onclick="if (event.target === this) hideAddProperty()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm lg:max-w-md transform transition-all duration-300 scale-100">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-slate-800">Add Property</h2>
      <button type="button" onclick="hideAddProperty()" class="text-slate-500 hover:text-slate-700">✕</button>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ admin_url('admin-post.php') }}" class="p-6 space-y-5">
      {{-- Hidden inputs --}}
      <input type="hidden" name="action" value="rentwise_add_property">
      <input type="hidden" name="_wpnonce" value="{{ wp_create_nonce('rentwise_add_property') }}">

      {{-- Property Name --}}
      <div>
        <label for="property_name" class="block text-sm font-medium text-slate-700 mb-1">Property Name</label>
        <input type="text" id="property_name" name="property_name" required
               placeholder="e.g., Sunset Apartments"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Address --}}
      <div>
        <label for="property_address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
        <input type="text" id="property_address" name="property_address" required
               placeholder="123 Main St, City, State"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Number of Units --}}
      <div>
        <label for="property_units" class="block text-sm font-medium text-slate-700 mb-1">Number of Units</label>
        <input type="number" id="property_units" name="property_units" min="1" step="1"
               placeholder="e.g., 10"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Property Type --}}
      <div>
        <label for="property_type" class="block text-sm font-medium text-slate-700 mb-1">Property Type</label>
        <select id="property_type" name="property_type"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
          <option value="apartment">Apartment Building</option>
          <option value="house">Single Family Home</option>
          <option value="condo">Condo</option>
          <option value="townhouse">Townhouse</option>
          <option value="commercial">Commercial</option>
          <option value="other">Other</option>
        </select>
      </div>

      {{-- Actions --}}
      <div class="flex justify-end space-x-3 pt-2">
        <button type="button"
                onclick="hideAddProperty()"
                class="rounded-lg px-4 py-2 text-slate-700 hover:bg-slate-100 bg-white border border-slate-300 font-medium transition">
          Cancel
        </button>
        <button type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700 transition shadow-md">
          Save Property
        </button>
      </div>
    </form>
  </div>
</div>

<script>
    function hideAddProperty() {
        const modal = document.getElementById("addPropertyModal");
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
    #addPropertyModal {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    #addPropertyModal.flex {
        opacity: 1;
    }
</style>

