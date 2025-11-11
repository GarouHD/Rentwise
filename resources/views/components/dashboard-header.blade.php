<header class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Rentwise</h1>
            <p class="text-gray-600">Property Management Dashboard</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="bg-green-100 px-4 py-2 rounded-lg">
                <span class="text-green-800 font-semibold">Total Balance: $12,450</span>
            </div>
            <button onclick="showAddTenant()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
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
        }
    }
</script>