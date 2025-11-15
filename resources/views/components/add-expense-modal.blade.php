{{-- Add Expense Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300" id="addExpenseModal"
      onclick="if (event.target === this) hideAddExpense()"
>
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm lg:max-w-md transform transition-all duration-300 scale-100">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-slate-800">Add Expense</h2>
      <button type="button" onclick="hideAddExpense()" class="text-slate-500 hover:text-red-600">✕</button>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ admin_url('admin-post.php') }}" class="p-6 space-y-5">
      {{-- Hidden inputs --}}
      <input type="hidden" name="action" value="rentwise_add_expense">
      <input type="hidden" name="_wpnonce" value="{{ wp_create_nonce('rentwise_add_expense') }}">

      {{-- Description --}}
      <div>
        <label for="expense_description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
        <input type="text" id="expense_description" name="expense_description" required
               placeholder="e.g. Plumbing repair, Property taxes"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Amount --}}
      <div>
        <label for="expense_amount" class="block text-sm font-medium text-slate-700 mb-1">Amount (USD)</label>
        <input type="number" id="expense_amount" name="expense_amount" step="0.01" min="0" required
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Category --}}
      <div>
        <label for="expense_category" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
        <select id="expense_category" name="expense_category"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
          @foreach (rentwise_get_expense_categories() as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
          @endforeach
        </select>
      </div>

      {{-- Property (Optional) --}}
      <div>
        <label for="expense_property" class="block text-sm font-medium text-slate-700 mb-1">Property (Optional)</label>
        <select id="expense_property" name="expense_property"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
          <option value="">-- All Properties --</option>
          @php
            $properties = rentwise_get_all_properties();
          @endphp
          @foreach ($properties as $property)
            <option value="{{ $property->ID }}">{{ get_field('name', $property->ID) ?: $property->post_title }}</option>
          @endforeach
        </select>
      </div>

      {{-- Date --}}
      <div>
        <label for="expense_date" class="block text-sm font-medium text-slate-700 mb-1">Date</label>
        <input type="date" id="expense_date" name="expense_date" value="{{ date('Y-m-d') }}"
               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
      </div>

      {{-- Actions --}}
      <div class="flex justify-end space-x-3 pt-2">
        <button type="button"
                onclick="hideAddExpense()"
                class="rounded-lg px-4 py-2 text-slate-700 hover:bg-slate-100 bg-white border border-slate-300 font-medium transition">
          Cancel
        </button>
        <button type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2 text-white font-medium hover:bg-indigo-700 transition shadow-md">
          Save Expense
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function hideAddExpense() {
    const modal = document.getElementById("addExpenseModal");
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
#addExpenseModal {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
#addExpenseModal.flex {
    opacity: 1;
}
</style>

