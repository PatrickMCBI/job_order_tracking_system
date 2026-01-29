document.addEventListener("DOMContentLoaded", function () {
    const notifList = document.getElementById('notifList');
    const markAllBtn = document.getElementById('markAllRead');

    // Function to fetch notifications from the server
    async function fetchNotifications() {
        try {
            const res = await fetch('../jo/fetch_notifications.php');
            const data = await res.json();
            
            notifList.innerHTML = '';
            data.forEach(n => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                if(n.is_read == 0) li.classList.add('list-group-item-warning');
                li.innerHTML = `
                    ${n.message}
                    <small class="text-muted d-block">${n.created_at}</small>
                `;
                notifList.appendChild(li);
            });
        } catch(err) {
            console.error('Error fetching notifications', err);
        }
    }

    // Fetch every 5 seconds
    setInterval(fetchNotifications, 5000);
    fetchNotifications(); // initial load

    // Mark all as read
    markAllBtn.addEventListener('click', async () => {
        try {
            await fetch('../jo/mark_notifications_read.php', { method: 'POST' });
            fetchNotifications(); // refresh after marking
        } catch(err) {
            console.error('Error marking notifications as read', err);
        }
    });
});
