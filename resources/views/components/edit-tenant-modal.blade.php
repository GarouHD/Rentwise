{{-- Edit Tenant Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="editTenantModal"
      onclick="if (event.target === this) hideEditTenant()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm lg:max-w-md transform transition-all duration-300 scale-100">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-slate-800">Edit Tenant</h2>
      <button type="button" onclick="hideEditTenant()" class="text-slate-500 hover:text-slate-700">✕</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      {{-- Form --}}
      <form method="POST" action="{{ admin_url('admin-post.php') }}" class="p-6 space-y-5" id="editTenantForm">
        {{-- Hidden inputs --}}
        <input type="hidden" name="action" value="rentwise_update_tenant">
        <input type="hidden" name="_wpnonce" value="{{ wp_create_nonce('rentwise_update_tenant') }}">
        <input type="hidden" name="tenant_id" id="edit_tenant_id">

        {{-- Tenant Name --}}
        <div>
          <label for="edit_tenant_name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
          <input type="text" id="edit_tenant_name" name="tenant_name" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
        </div>

        {{-- Unit --}}
        <div>
          <label for="edit_tenant_unit" class="block text-sm font-medium text-slate-700 mb-1">Unit / Apartment</label>
          <input type="text" id="edit_tenant_unit" name="tenant_unit"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
        </div>

        {{-- Rent Amount --}}
        <div>
          <label for="edit_rent_amount" class="block text-sm font-medium text-slate-700 mb-1">Monthly Rent (USD)</label>
          <input type="number" id="edit_rent_amount" name="rent_amount" step="0.01" min="0"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
        </div>

        {{-- Status --}}
        <div>
          <label for="edit_tenant_status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
          <select id="edit_tenant_status" name="tenant_status"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        {{-- Actions --}}
        <div class="flex justify-between pt-2">
          <button type="button"
                  onclick="confirmDeleteTenant()"
                  class="rounded-lg px-4 py-2 bg-red-600 text-white hover:bg-red-700 transition font-medium shadow-md">
            Delete Tenant
          </button>
          <div class="flex space-x-3">
            <button type="button"
                    onclick="hideEditTenant()"
                    class="rounded-lg px-4 py-2 text-slate-700 hover:bg-slate-100 bg-white border border-slate-300 font-medium transition">
              Cancel
            </button>
            <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700 transition shadow-md">
              Update Tenant
            </button>
          </div>
        </div>
      </form>

      <!-- RIGHT COLUMN: Payments -->
      <div class="border-l border-slate-200 h-full flex flex-col">

          <!-- Title -->
          <div class="px-4 py-3">
              <h3 class="text-sm font-semibold text-slate-700">Recent Payments</h3>
          </div>

          <!-- Payments List -->
          <div id="edit_payments" class="p-4 text-sm flex-1 overflow-y-auto">
              <div class="text-slate-500">No payments yet.</div>
          </div>

          <!-- Balance -->
          <div class="px-4 pb-2">
              <div id="edit_balance_row" class="text-sm text-slate-700">
                  Balance:
                  <span id="edit_balance_value" class="font-medium text-slate-700">—</span>
              </div>
          </div>

          <!-- Action Button -->
          <div class="px-4 pb-4">
              <button type="button"
                      onclick="showRecordPaymentModal()"
                      class="rounded-lg px-4 py-2 bg-green-600 text-white hover:bg-green-700 transition font-medium shadow-md w-full">
                  Record Payment
              </button>
          </div>

      </div>



      </div>
    </div>
    
</div>

<script>
function showTenantDetails(tenantId) {
    // Fetch tenant data via AJAX
    fetch('{{ admin_url('admin-ajax.php') }}?action=rentwise_get_tenant&tenant_id=' + tenantId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tenant = data.data;
                
                // Populate form fields
                document.getElementById('edit_tenant_id').value = tenantId;
                document.getElementById('edit_tenant_name').value = tenant.name || '';
                document.getElementById('edit_tenant_unit').value = tenant.unit || '';
                document.getElementById('edit_rent_amount').value = tenant.rent_amount || '';
                document.getElementById('edit_tenant_status').value = tenant.status || 'active';
                
                // Show modal with fade-in
                const modal = document.getElementById('editTenantModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);

                // Load payments
                loadPayments(tenantId);

            } else {
                alert('Failed to load tenant data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load tenant dataHERE');
        });

}

function hideEditTenant() {
    const modal = document.getElementById('editTenantModal');
    if (modal) {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300); // Match the transition duration
    }
}

function confirmDeleteTenant() {
    if (confirm('Are you sure you want to delete this tenant? This action cannot be undone.')) {
        const tenantId = document.getElementById('edit_tenant_id').value;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ admin_url('admin-post.php') }}';
        
        // Add hidden fields
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'rentwise_delete_tenant';
        form.appendChild(actionInput);
        
        const nonceInput = document.createElement('input');
        nonceInput.type = 'hidden';
        nonceInput.name = '_wpnonce';
        nonceInput.value = '{{ wp_create_nonce('rentwise_delete_tenant') }}';
        form.appendChild(nonceInput);
        
        const tenantIdInput = document.createElement('input');
        tenantIdInput.type = 'hidden';
        tenantIdInput.name = 'tenant_id';
        tenantIdInput.value = tenantId;
        form.appendChild(tenantIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function loadPayments() {

  // Get tenantID from hidden input field
  tenantID = document.getElementById('edit_tenant_id').value;

  // Ajax request to load payments 
  const container = document.getElementById('edit_payments');
  const balanceEl = document.getElementById('edit_balance_value');

  if (!container) return;

  container.innerHTML = '<div class="text-slate-500">Loading payments…</div>';
  if (balanceEl) {
      balanceEl.textContent = '—';
      balanceEl.className = 'font-medium text-slate-700';
  }

  const form = new URLSearchParams();
  form.set('action', 'rentwise_get_payments');
  form.set('tenant_id', tenantID);

  fetch('{{ admin_url('admin-ajax.php') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: form.toString()
  })
  .then(res => res.json())
  .then(json => {

      if (!json || !json.success) {
          container.innerHTML = '<div class="text-rose-600 text-sm">Could not load payments.</div>';
          return;
      }

      container.innerHTML = json.data.html || '<div class="text-slate-500">No payments yet.</div>';

      if (typeof json.data.balance !== 'undefined' && balanceEl) {
          const val = Number(json.data.balance);
          const pretty = (val < 0 ? '-' : '') + '$' + Math.abs(val).toFixed(2);
          balanceEl.textContent = pretty;
          balanceEl.className = 'font-medium ' + (val < 0 ? 'text-rose-600' : 'text-emerald-600');
      }
  })
  .catch(err => {
      console.error(err);
      container.innerHTML = '<div class="text-rose-600 text-sm">Could not load payments.</div>';
  });
}

</script>
<style>
    #editTenantModal {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    #editTenantModal.flex {
        opacity: 1;
    }
</style>

