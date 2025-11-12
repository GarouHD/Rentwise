{{-- Add Tenant Modal Markup --}}
<div class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4" id="addTenantModal"
      onclick="if (event.target === this) hideAddTenant()"
>
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md sm:max-w-xl lg:max-w-2xl">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-slate-800">Add Tenant</h2>
      <button type="button" onclick="hideAddTenant()" class="text-slate-500 hover:text-red-600" id="closeModal">✕</button>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ admin_url('admin-post.php') }}" class="p-6 space-y-5">
      {{-- Hidden inputs (for later wiring) --}}
      <input type="hidden" name="action" value="rentwise_add_tenant">
      <input type="hidden" name="_wpnonce" value="{{ wp_create_nonce('rentwise_add_tenant') }}">

      {{-- Tenant Name --}}
      <div>
        <label for="tenant_name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
        <input type="text" id="tenant_name" name="tenant_name" required
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Unit --}}
      <div>
        <label for="tenant_unit" class="block text-sm font-medium text-slate-700 mb-1">Unit / Apartment</label>
        <input type="text" id="tenant_unit" name="tenant_unit"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Rent Amount --}}
      <div>
        <label for="rent_amount" class="block text-sm font-medium text-slate-700 mb-1">Monthly Rent (USD)</label>
        <input type="number" id="rent_amount" name="rent_amount" step="0.01" min="0"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Status --}}
      <div>
        <label for="tenant_status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
        <select id="tenant_status" name="tenant_status"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>

      {{-- Actions --}}
      <div class="flex justify-end space-x-3 pt-2">
        <button type="button"
                onclick="hideAddTenant()"
                class="rounded-lg px-4 py-2 text-slate-700 hover:bg-slate-100"
                id="cancelButton">
          Cancel
        </button>
        <button type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700 transition">
          Save Tenant
        </button>
      </div>
    </form>
  </div>
</div>

<script>
    function hideAddTenant() {
        const modal = document.getElementById("addTenantModal");
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>