// resources/js/app.js
import './bootstrap';
console.log("MedCare app JS loaded");

// 🔔 Listen for AppointmentApproved event
window.Echo.channel('appointments')
    .listen('AppointmentApproved', (e) => {
        console.log("📢 Appointment approved:", e);

        // ---------- 1. Toast Notification ----------
        const toastContainer = document.querySelector('.toast-container');
        if (toastContainer) {
            const toastHTML = `
                <div class="toast align-items-center text-bg-success border-0 mb-2" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            ✅ Appointment #${e.appointment.id} was approved!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const newToast = toastContainer.lastElementChild;
            new bootstrap.Toast(newToast).show();
        }

        // ---------- 2. Bell Dropdown Notification ----------
        const notifList = document.getElementById('notifList');
        const badge = document.getElementById('notifBadge');

        if (notifList && badge) {
            // Create a new notification item
            const notifItem = document.createElement('div');
            notifItem.className = 'p-2 text-sm text-gray-700 border-b hover:bg-gray-100 cursor-pointer';
            notifItem.innerText = `✅ Appointment #${e.appointment.id} approved`;
            
            // Add to the top of the list
            notifList.prepend(notifItem);

            // Update badge count
            badge.classList.remove('hidden');
            badge.innerText = notifList.childElementCount;
        }
    });
