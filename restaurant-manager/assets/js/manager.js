const themeToggleBtn = document.getElementById('themeToggle');
if (themeToggleBtn) {
    const icon = themeToggleBtn.querySelector('i');
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    if (currentTheme === 'light') {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    }

    themeToggleBtn.addEventListener('click', () => {
        let theme = document.documentElement.getAttribute('data-theme');
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('manager_theme', 'light');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('manager_theme', 'dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    });
}

function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}

const activeOrdersContainer = document.getElementById('active-orders-container');
const connectionStatus = document.getElementById('connection-status');

if (activeOrdersContainer) {
    const columns = [
        { id: 'pending',    title: 'New Orders (Pending)',  color: 'pending'    },
        { id: 'accepted',   title: 'Accepted',              color: 'accepted'   },
        { id: 'preparing',  title: 'Preparing',             color: 'preparing'  },
        { id: 'ready',      title: 'Ready for Pickup',      color: 'ready'      },
        { id: 'picked_up',  title: 'Picked Up',             color: 'picked_up'  },
        { id: 'on_the_way', title: 'On the Way',            color: 'picked_up'  }
    ];

    function renderKanban(orders) {
        let html = '';
        columns.forEach(col => {
            const colOrders = orders.filter(o => o.status === col.id);
            html += `
                <div class="kanban-column">
                    <div class="kanban-column-header">
                        <span>${col.title}</span>
                        <span class="badge badge-${col.color}">${colOrders.length}</span>
                    </div>
            `;
            colOrders.forEach(order => {
                let itemsList = order.items.map(i => `${i.quantity}x ${i.name}`).join(', ');
                html += `
                    <div class="order-card">
                        <div class="order-meta">
                            <strong>#${String(order.id).padStart(5, '0')}</strong>
                            <span>$${parseFloat(order.total_amount).toFixed(2)}</span>
                        </div>
                        <div style="margin-bottom: 8px;"><strong>${order.customer_name}</strong></div>
                        <div class="order-items text-muted">${itemsList}</div>
                        <div class="order-actions">
                            <select onchange="updateOrderStatus(${order.id}, this.value)">
                                ${generateStatusOptions(order.status)}
                            </select>
                        </div>
                    </div>
                `;
            });
            html += `</div>`;
        });
        activeOrdersContainer.innerHTML = html;
    }

    function generateStatusOptions(currentStatus) {
        const statuses = ['pending', 'accepted', 'preparing', 'ready', 'picked_up', 'on_the_way', 'delivered', 'cancelled'];
        return statuses.map(s => {
            const label = s.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            const selected = s === currentStatus ? 'selected' : '';
            return `<option value="${s}" ${selected}>${label}</option>`;
        }).join('');
    }

    async function fetchOrders() {
        try {
            const response = await fetch('?route=api/manager/active-orders');
            if (!response.ok) throw new Error('Network error');
            const data = await response.json();
            if (data.success) {
                renderKanban(data.orders);
                connectionStatus.textContent = 'Live';
                connectionStatus.className = 'badge badge-delivered';
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            console.error('Error fetching orders:', error);
            connectionStatus.textContent = 'Disconnected';
            connectionStatus.className = 'badge badge-cancelled';
        }
    }

    window.updateOrderStatus = async function(orderId, newStatus) {
        try {
            const response = await fetch('?route=api/manager/update-order-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status: newStatus })
            });
            const data = await response.json();
            if (data.success) {
                fetchOrders();
            } else {
                alert('Error updating status: ' + data.error);
            }
        } catch (error) {
            console.error('Error updating status:', error);
            alert('Failed to update order status.');
        }
    }

    fetchOrders();
    setInterval(fetchOrders, 5000);
}
