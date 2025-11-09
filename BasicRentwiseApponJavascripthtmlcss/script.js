// Sample tenant data
const tenants = {
    john: {
        name: "John Doe",
        unit: "Apt 101",
        rent: 1200,
        leaseStart: "Jan 1, 2024",
        leaseEnd: "Dec 31, 2024",
        balance: -3600,
        phone: "(555) 123-4567",
        email: "john.doe@email.com",
        payments: [
            { month: "May 2024", amount: 1200 },
            { month: "Apr 2024", amount: 1200 },
            { month: "Mar 2024", amount: 0 },
            { month: "Feb 2024", amount: 0 },
            { month: "Jan 2024", amount: 0 }
        ]
    },
    sarah: {
        name: "Sarah Miller",
        unit: "Apt 102",
        rent: 1350,
        leaseStart: "Feb 1, 2024",
        leaseEnd: "Jan 31, 2025",
        balance: 0,
        phone: "(555) 234-5678",
        email: "sarah.miller@email.com",
        payments: [
            { month: "Jun 2024", amount: 1350 },
            { month: "May 2024", amount: 1350 },
            { month: "Apr 2024", amount: 1350 }
        ]
    }
};

let currentEditingTenant = null;

function showTenantDetails(tenantId) {
    const tenant = tenants[tenantId];
    if (!tenant) return;

    document.getElementById('tenantName').textContent = tenant.name;
    document.getElementById('tenantUnit').textContent = tenant.unit;
    document.getElementById('tenantRent').textContent = `$${tenant.rent}/month`;
    document.getElementById('leaseStart').textContent = tenant.leaseStart;
    document.getElementById('leaseEnd').textContent = tenant.leaseEnd;
    document.getElementById('currentBalance').textContent = tenant.balance < 0 ? `-$${Math.abs(tenant.balance)}` : `$${tenant.balance}`;
    document.getElementById('currentBalance').className = tenant.balance < 0 ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold';

    const paymentHistory = document.getElementById('paymentHistory');
    paymentHistory.innerHTML = '';
    tenant.payments.forEach(payment => {
        const div = document.createElement('div');
        div.className = 'flex justify-between';
        div.innerHTML = `
            <span>${payment.month}</span>
            <span class="${payment.amount > 0 ? 'text-green-600' : 'text-red-600'}">${payment.amount > 0 ? '$' + payment.amount : '$0 (Missed)'}</span>
        `;
        paymentHistory.appendChild(div);
    });

    document.getElementById('tenantModal').classList.remove('hidden');
}

function closeTenantModal() {
    document.getElementById('tenantModal').classList.add('hidden');
}

function showAddTenant() {
    document.getElementById('addTenantModal').classList.remove('hidden');
}

function closeAddTenantModal() {
    document.getElementById('addTenantModal').classList.add('hidden');
}

function addNewTenant(event) {
    event.preventDefault();
    
    const name = document.getElementById('newTenantName').value;
    const unit = document.getElementById('newTenantUnit').value;
    const rent = document.getElementById('newTenantRent').value;
    
    // Create success message
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            ${name} added successfully to ${unit}!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
    
    closeAddTenantModal();
    document.getElementById('addTenantModal').querySelector('form').reset();
}

function recordPaymentForTenant() {
    // Set today's date as default
    document.getElementById('paymentDate').valueAsDate = new Date();
    
    // Set current month as default period
    const now = new Date();
    document.getElementById('paymentPeriod').value = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
    
    document.getElementById('recordPaymentModal').classList.remove('hidden');
}

function closeRecordPaymentModal() {
    document.getElementById('recordPaymentModal').classList.add('hidden');
    document.getElementById('recordPaymentModal').querySelector('form').reset();
}

function recordPayment(event) {
    event.preventDefault();
    
    const tenant = document.getElementById('paymentTenant').value;
    const amount = document.getElementById('paymentAmount').value;
    const period = document.getElementById('paymentPeriod').value;
    const method = document.getElementById('paymentMethod').value;
    
    // Update tenant balance (simplified)
    if (tenants[tenant]) {
        tenants[tenant].balance += parseFloat(amount);
        tenants[tenant].payments.unshift({
            month: new Date(period + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' }),
            amount: parseFloat(amount)
        });
    }
    
    // Show success message
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Payment of $${amount} recorded successfully!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
    
    closeRecordPaymentModal();
    
    // Refresh tenant details if modal is open
    if (!document.getElementById('tenantModal').classList.contains('hidden')) {
        showTenantDetails(tenant);
    }
}

function editTenant() {
    const tenantName = document.getElementById('tenantName').textContent;
    
    // Find current tenant data
    for (let [key, tenant] of Object.entries(tenants)) {
        if (tenant.name === tenantName) {
            currentEditingTenant = key;
            
            // Pre-fill form with current data
            document.getElementById('editTenantName').value = tenant.name;
            document.getElementById('editTenantUnit').value = tenant.unit;
            document.getElementById('editTenantRent').value = tenant.rent;
            document.getElementById('editTenantPhone').value = tenant.phone || '';
            document.getElementById('editTenantEmail').value = tenant.email || '';
            
            break;
        }
    }
    
    document.getElementById('editTenantModal').classList.remove('hidden');
}

function closeEditTenantModal() {
    document.getElementById('editTenantModal').classList.add('hidden');
    currentEditingTenant = null;
}

function saveEditTenant(event) {
    event.preventDefault();
    
    if (!currentEditingTenant) return;
    
    const name = document.getElementById('editTenantName').value;
    const unit = document.getElementById('editTenantUnit').value;
    const rent = document.getElementById('editTenantRent').value;
    const phone = document.getElementById('editTenantPhone').value;
    const email = document.getElementById('editTenantEmail').value;
    
    // Update tenant data
    tenants[currentEditingTenant].name = name;
    tenants[currentEditingTenant].unit = unit;
    tenants[currentEditingTenant].rent = parseInt(rent);
    tenants[currentEditingTenant].phone = phone;
    tenants[currentEditingTenant].email = email;
    
    // Show success message
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            ${name}'s information updated successfully!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
    
    closeEditTenantModal();
    
    // Refresh tenant details
    showTenantDetails(currentEditingTenant);
}

function deleteTenant() {
    if (!currentEditingTenant) return;
    
    const tenantName = tenants[currentEditingTenant].name;
    
    // Create confirmation dialog
    const confirmDiv = document.createElement('div');
    confirmDiv.className = 'fixed inset-0 bg-black bg-opacity-50 z-60';
    confirmDiv.innerHTML = `
        <div class="flex items-center justify-center min-h-full p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6">
                <div class="text-center">
                    <div class="bg-red-100 p-3 rounded-full w-12 h-12 mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Delete Tenant</h3>
                    <p class="text-gray-600 mb-6">Are you sure you want to delete ${tenantName}? This action cannot be undone.</p>
                    <div class="flex space-x-3">
                        <button onclick="confirmDelete()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">
                            Delete
                        </button>
                        <button onclick="cancelDelete()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(confirmDiv);
    
    window.confirmDelete = function() {
        delete tenants[currentEditingTenant];
        
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        successDiv.textContent = `${tenantName} deleted successfully!`;
        document.body.appendChild(successDiv);
        
        setTimeout(() => {
            successDiv.remove();
        }, 3000);
        
        confirmDiv.remove();
        closeEditTenantModal();
        closeTenantModal();
    };
    
    window.cancelDelete = function() {
        confirmDiv.remove();
    };
}

function renewContract() {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-purple-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
            </svg>
            Contract renewal process started!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
}

function sendNotification() {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-orange-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
            </svg>
            Notification sent to tenant!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
}

function showRecordPayment() {
    // Set today's date as default
    document.getElementById('paymentDate').valueAsDate = new Date();
    
    // Set current month as default period
    const now = new Date();
    document.getElementById('paymentPeriod').value = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
    
    document.getElementById('recordPaymentModal').classList.remove('hidden');
}

function showPropertyInventory() {
    document.getElementById('inventoryModal').classList.remove('hidden');
}

function closeInventoryModal() {
    document.getElementById('inventoryModal').classList.add('hidden');
}

function addInventoryItem() {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            Add inventory item form would open here!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
}

function showMoveInOut() {
    document.getElementById('checklistModal').classList.remove('hidden');
}

function closeChecklistModal() {
    document.getElementById('checklistModal').classList.add('hidden');
}

function saveChecklist() {
    const deposit = document.getElementById('securityDeposit').value;
    const notes = document.getElementById('damageNotes').value;
    
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Checklist saved successfully!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
    
    closeChecklistModal();
}

function printChecklist() {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"></path>
            </svg>
            Checklist ready for printing!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
}

function showMediaDocs() {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-orange-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
            </svg>
            Document management system would open here!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
}

function showGridView() {
    // Already in grid view, could add list view toggle here
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.textContent = 'Grid view active!';
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

function showProperties() {
    document.getElementById('propertiesModal').classList.remove('hidden');
}

function closePropertiesModal() {
    document.getElementById('propertiesModal').classList.add('hidden');
}

function addNewProperty() {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            Add new property form would open here!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
}

function generateReport() {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
            </svg>
            Portfolio report generated successfully!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 4000);
}
