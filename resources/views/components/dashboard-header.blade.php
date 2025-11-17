<header class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6 md:mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Rentwise</h1>
            <p class="text-sm sm:text-base text-gray-600">Property Management Dashboard</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <div class="bg-green-100 px-3 py-2 rounded-lg flex-shrink-0">
                <span class="text-green-800 font-semibold text-sm sm:text-base">Total Balance: $0</span>
            </div>
            <button onclick="showExpensesList()" class="bg-red-600 hover:bg-red-700 text-white px-3 sm:px-4 py-2 rounded-lg transition text-sm sm:text-base flex-shrink-0">
                💸 Expenses
            </button>
            <button onclick="showAddTenant()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg transition text-sm sm:text-base flex-shrink-0">
                + Add Tenant
            </button>
        </div>
    </div>
</header>

<script>
    function showAddTenant() {
        const modal = document.getElementById("addTenantModal");
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Trigger animation after a brief delay
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 10);
        }
    }
</script>