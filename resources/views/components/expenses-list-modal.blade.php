{{-- Expenses List Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" id="expensesListModal"
     onclick="if (event.target === this) hideExpensesList()">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col">
    
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-4 sm:px-6 py-3 sm:py-4 bg-gradient-to-r from-red-50 to-orange-50 gap-2">
      <div>
        <h2 class="text-lg sm:text-2xl font-bold text-slate-800">💸 Expense Tracking</h2>
        <p class="text-xs sm:text-sm text-slate-600 mt-1">Manage all your property expenses</p>
      </div>
      <button type="button" onclick="hideExpensesList()" class="text-slate-500 hover:text-slate-700 text-xl sm:text-2xl flex-shrink-0">✕</button>
    </div>

    {{-- Content --}}
    <div class="flex-1 overflow-y-auto p-4 sm:p-6">
      @php
        $expenses = rentwise_get_all_expenses();
        $total_expenses = 0;
        $categories_totals = [];
      @endphp

      @if (!empty($expenses))
        {{-- Summary Cards --}}
        <div class="grid gap-4 md:grid-cols-3 mb-6">
          {{-- Total Expenses --}}
          <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border-2 border-red-200">
            <div class="text-sm text-red-700 font-medium mb-1">Total Expenses</div>
            @php
              foreach ($expenses as $expense) {
                $total_expenses += (float) get_field('amount', $expense->ID);
              }
            @endphp
            <div class="text-3xl font-bold text-red-700">${{ number_format($total_expenses, 2) }}</div>
          </div>

          {{-- This Month --}}
          <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border-2 border-orange-200">
            <div class="text-sm text-orange-700 font-medium mb-1">This Month</div>
            @php
              $this_month = 0;
              $current_month = date('Y-m');
              foreach ($expenses as $expense) {
                $expense_date = get_field('date', $expense->ID) ?: $expense->post_date;
                if (strpos($expense_date, $current_month) === 0) {
                  $this_month += (float) get_field('amount', $expense->ID);
                }
              }
            @endphp
            <div class="text-3xl font-bold text-orange-700">${{ number_format($this_month, 2) }}</div>
          </div>

          {{-- Expense Count --}}
          <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border-2 border-purple-200">
            <div class="text-sm text-purple-700 font-medium mb-1">Total Records</div>
            <div class="text-3xl font-bold text-purple-700">{{ count($expenses) }}</div>
          </div>
        </div>

        {{-- Expenses Table --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
          <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
              <tr>
                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700">Date</th>
                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700">Description</th>
                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700">Category</th>
                <th class="text-left px-4 py-3 text-sm font-semibold text-slate-700">Property</th>
                <th class="text-right px-4 py-3 text-sm font-semibold text-slate-700">Amount</th>
                <th class="text-center px-4 py-3 text-sm font-semibold text-slate-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach ($expenses as $expense)
                @php
                  $description = get_field('description', $expense->ID) ?: $expense->post_title;
                  $amount = (float) get_field('amount', $expense->ID);
                  $category = get_field('category', $expense->ID) ?: 'other';
                  $property_id = get_field('property_id', $expense->ID);
                  $property_name = $property_id ? (get_field('name', $property_id) ?: 'N/A') : 'All Properties';
                  $date = get_field('date', $expense->ID) ?: date('Y-m-d', strtotime($expense->post_date));
                  $categories = rentwise_get_expense_categories();
                  $category_label = $categories[$category] ?? 'Other';
                @endphp
                <tr class="hover:bg-slate-50 transition">
                  <td class="px-4 py-3 text-sm text-slate-600">{{ date('M j, Y', strtotime($date)) }}</td>
                  <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ $description }}</td>
                  <td class="px-4 py-3 text-xs">
                    <span class="inline-block px-2 py-1 rounded-full bg-slate-100 text-slate-700">
                      {{ $category_label }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-sm text-slate-600">{{ $property_name }}</td>
                  <td class="px-4 py-3 text-sm font-semibold text-right text-red-600">${{ number_format($amount, 2) }}</td>
                  <td class="px-4 py-3 text-center">
                    <button onclick="showExpenseDetails({{ $expense->ID }})" 
                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                      Edit
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      @else
        {{-- Empty State --}}
        <div class="text-center py-12">
          <div class="text-6xl mb-4">📊</div>
          <h3 class="text-xl font-semibold text-slate-800 mb-2">No Expenses Yet</h3>
          <p class="text-slate-600 mb-6">Start tracking your property expenses to see the complete financial picture.</p>
          <button onclick="hideExpensesList(); showAddExpense();" 
                  class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
            Add First Expense
          </button>
        </div>
      @endif
    </div>

    {{-- Footer --}}
    <div class="border-t border-slate-200 px-6 py-4 bg-slate-50 flex justify-between items-center">
      <button onclick="hideExpensesList(); showAddExpense();" 
              class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-md">
        + Add Expense
      </button>
      <button onclick="hideExpensesList()" 
              class="px-4 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition font-medium">
        Close
      </button>
    </div>

  </div>
</div>

<script>
function showExpensesList() {
    const modal = document.getElementById('expensesListModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function hideExpensesList() {
    const modal = document.getElementById('expensesListModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function showExpenseDetails(expenseId) {
    // Fetch expense data and show edit modal
    fetch('{{ admin_url('admin-ajax.php') }}?action=rentwise_get_expense&expense_id=' + expenseId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const expense = data.data;
                
                // Populate edit modal fields (we'll create this next)
                document.getElementById('edit_expense_id').value = expenseId;
                document.getElementById('edit_expense_description').value = expense.description || '';
                document.getElementById('edit_expense_amount').value = expense.amount || '';
                document.getElementById('edit_expense_category').value = expense.category || 'other';
                document.getElementById('edit_expense_property').value = expense.property_id || '';
                document.getElementById('edit_expense_date').value = expense.date || '';
                
                // Hide list modal, show edit modal
                hideExpensesList();
                showEditExpense();
            } else {
                alert('Failed to load expense data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load expense data');
        });
}

function showAddExpense() {
    const modal = document.getElementById('addExpenseModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}

function showEditExpense() {
    const modal = document.getElementById('editExpenseModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
}
</script>

