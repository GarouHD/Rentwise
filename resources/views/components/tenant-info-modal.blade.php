<div class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/40 p-4" id="tenantInfoModal"
     onclick=""
     aria-hidden="true">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md sm:max-w-xl lg:max-w-2xl" role="dialog" aria-modal="true" aria-labelledby="ti-name">

    <!-- Header -->
    <div class="flex items-center justify-between p-5">
      <div>
        <h2 id="ti-name" class="text-xl font-semibold">John Doe</h2>
      </div>
      <button type="button" class="rounded p-2 hover:bg-red-600" aria-label="Close modal" onclick="hideAddTenant()">
        ✕
      </button>
    </div>

    <!-- Content -->
    <div class="p-5">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Tenant Information -->
        <section class="rounded-xl bg-slate-50 p-4">
          <h3 class="text-sm font-semibold text-slate-700">Tenant Information</h3>
          <dl class="mt-3 space-y-2 text-sm text-slate-700">
            <div class="flex justify-between gap-4">
              <dt class="text-slate-500">Unit:</dt>
              <dd id="ti-unit" class="font-medium">Apt 101</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-slate-500">Rent:</dt>
              <dd id="ti-rent" class="font-medium">$1,200/month</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-slate-500">Lease Start:</dt>
              <dd id="ti-lease-start" class="font-medium">Jan 1, 2024</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-slate-500">Lease End:</dt>
              <dd id="ti-lease-end" class="font-medium">Dec 31, 2024</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-slate-500">Current Balance:</dt>
              <dd id="ti-balance" class="font-medium text-rose-600">-$3,600</dd>
            </div>
          </dl>
        </section>

        <!-- Recent Payments (skeleton/default) -->
        <section class="rounded-xl bg-slate-50 p-4">
          <h3 class="text-sm font-semibold text-slate-700">Recent Payments</h3>
          <div id="ti-payments" class="mt-3 text-sm">
            <div class="text-slate-500">No payments yet.</div>
          </div>
        </section>

      </div>

      <!-- Actions -->
      <div class="mt-6 flex flex-wrap gap-3">
        <button id="ti-record-payment" type="button"
                class="rounded-full px-4 py-2 text-white bg-emerald-600 hover:bg-emerald-700">
          Record Payment
        </button>

        <button id="ti-edit-tenant" type="button"
                class="rounded-full px-4 py-2 text-white bg-blue-600 hover:bg-blue-700">
          Edit Tenant
        </button>
        
      </div>
    </div>
  </div>
</div>
