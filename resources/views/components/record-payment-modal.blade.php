{{-- Record Payment Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300"
     id="recordPaymentModal"
     onclick="if (event.target === this) hideRecordPayment()">

  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-100">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-slate-800">Record Payment</h2>
      <button type="button"
              onclick="hideRecordPayment()"
              class="text-slate-500 hover:text-slate-700">
        ✕
      </button>
    </div>

    {{-- Payment Form --}}
    <form method="POST" action="{{ admin_url('admin-post.php') }}" class="p-6 space-y-5" id="recordPaymentForm">
      {{-- Hidden inputs --}}
      <input type="hidden" name="action" value="rentwise_record_payment">
      <input type="hidden" name="_wpnonce" value="{{ wp_create_nonce('rentwise_record_payment') }}">
      <input type="hidden" name="tenant_id" id="payment_tenant_id">

      {{-- Amount --}}
      <div>
        <label for="payment_amount" class="block text-sm font-medium text-slate-700 mb-1">Amount (USD)</label>
        <input type="number"
               id="payment_amount"
               name="amount"
               step="0.01"
               min="0"
               required
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800
                      focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Paid On --}}
      <div>
        <label for="payment_paid_on" class="block text-sm font-medium text-slate-700 mb-1">Paid On</label>
        <input type="date"
               id="payment_paid_on"
               name="paid_on"
               required
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800
                      focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Status --}}
      <div>
        <label for="payment_status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
        <select id="payment_status"
                name="status"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
          <option value="paid">Paid</option>
          <option value="overdue">Overdue</option>
          <option value="due">Due soon</option>
        </select>
      </div>

      {{-- Actions --}}
      <div class="flex justify-end pt-2 space-x-3">
        <button type="button"
                onclick="hideRecordPayment()"
                class="rounded-lg px-4 py-2 text-slate-700 hover:bg-slate-100 bg-white border border-slate-300 font-medium transition">
          Cancel
        </button>

        <button type="submit"
                class="rounded-lg bg-green-600 px-5 py-2 text-white font-medium hover:bg-green-700 transition shadow-md">
          Save Payment
        </button>
      </div>
    </form>

  </div>
</div>

<script>
function showRecordPaymentModal() {

    // close the edit tenant modal
    hideEditTenant()

    // get tenant ID from the edit modal hidden input
    const tenantId = document.getElementById('edit_tenant_id').value;

    if (!tenantId) {
        alert("Error, failed to load tenant");
        return;
    }

    // set the tenant ID into the payment modal
    document.getElementById('payment_tenant_id').value = tenantId;

    console.log(tenantId)

    // show modal
    const modal = document.getElementById('recordPaymentModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.style.opacity = '1';
}

function hideRecordPayment() {
    const modal = document.getElementById('recordPaymentModal');
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}
</script>
