{{-- Edit Property Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="editPropertyModal"
      onclick="if (event.target === this) hideEditProperty()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm lg:max-w-md transform transition-all duration-300 scale-100">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-slate-800">Edit Property</h2>
      <button type="button" onclick="hideEditProperty()" class="text-slate-500 hover:text-slate-700">✕</button>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ admin_url('admin-post.php') }}" class="p-6 space-y-5" id="editPropertyForm">
      {{-- Hidden inputs --}}
      <input type="hidden" name="action" value="rentwise_update_property">
      <input type="hidden" name="_wpnonce" value="{{ wp_create_nonce('rentwise_update_property') }}">
      <input type="hidden" name="property_id" id="edit_property_id">

      {{-- Property Name --}}
      <div>
        <label for="edit_property_name" class="block text-sm font-medium text-slate-700 mb-1">Property Name</label>
        <input type="text" id="edit_property_name" name="property_name" required
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Address --}}
      <div>
        <label for="edit_property_address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
        <input type="text" id="edit_property_address" name="property_address" required
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Number of Units --}}
      <div>
        <label for="edit_property_units" class="block text-sm font-medium text-slate-700 mb-1">Number of Units</label>
        <input type="number" id="edit_property_units" name="property_units" min="1" step="1"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Property Type --}}
      <div>
        <label for="edit_property_type" class="block text-sm font-medium text-slate-700 mb-1">Property Type</label>
        <select id="edit_property_type" name="property_type"
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
      <div class="flex justify-between pt-2">
        <button type="button"
                onclick="confirmDeleteProperty()"
                class="rounded-lg px-4 py-2 bg-red-600 text-white hover:bg-red-700 transition font-medium shadow-md">
          Delete Property
        </button>
        <div class="flex space-x-3">
          <button type="button"
                  onclick="hideEditProperty()"
                  class="rounded-lg px-4 py-2 text-slate-700 hover:bg-slate-100 bg-white border border-slate-300 font-medium transition">
            Cancel
          </button>
          <button type="submit"
                  class="rounded-lg bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700 transition shadow-md">
            Update Property
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function showPropertyDetails(propertyId) {
    // Fetch property data via AJAX
    fetch('{{ admin_url('admin-ajax.php') }}?action=rentwise_get_property&property_id=' + propertyId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const property = data.data;
                
                // Populate form fields
                document.getElementById('edit_property_id').value = propertyId;
                document.getElementById('edit_property_name').value = property.name || '';
                document.getElementById('edit_property_address').value = property.address || '';
                document.getElementById('edit_property_units').value = property.units || '';
                document.getElementById('edit_property_type').value = property.type || 'apartment';
                
                // Show modal with fade-in
                const modal = document.getElementById('editPropertyModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);
            } else {
                alert('Failed to load property data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load property data');
        });
}

function hideEditProperty() {
    const modal = document.getElementById('editPropertyModal');
    if (modal) {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
}

function confirmDeleteProperty() {
    if (confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
        const propertyId = document.getElementById('edit_property_id').value;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ admin_url('admin-post.php') }}';
        
        // Add hidden fields
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'rentwise_delete_property';
        form.appendChild(actionInput);
        
        const nonceInput = document.createElement('input');
        nonceInput.type = 'hidden';
        nonceInput.name = '_wpnonce';
        nonceInput.value = '{{ wp_create_nonce('rentwise_delete_property') }}';
        form.appendChild(nonceInput);
        
        const propertyIdInput = document.createElement('input');
        propertyIdInput.type = 'hidden';
        propertyIdInput.name = 'property_id';
        propertyIdInput.value = propertyId;
        form.appendChild(propertyIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<style>
    #editPropertyModal {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    #editPropertyModal.flex {
        opacity: 1;
    }
</style>

