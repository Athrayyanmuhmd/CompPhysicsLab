@extends('layouts.admin')

@section('title', 'Kelola Jadwal Available')

@section('styles')
<style>
    .day-card {
        transition: all 0.2s ease;
        cursor: pointer;
        border: 1px solid #e5e7eb;
    }

    .day-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }

    .day-available {
        background: #f0fdf4;
        border-color: #22c55e;
    }

    .day-limited {
        background: #fffbeb;
        border-color: #f59e0b;
    }

    .day-full {
        background: #fef2f2;
        border-color: #ef4444;
    }

    .day-closed {
        background: #f9fafb;
        border-color: #9ca3af;
        opacity: 0.6;
    }

    .month-nav {
        background: white;
        border: 1px solid #e5e7eb;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Jadwal Available</h1>
            <p class="text-gray-600">Atur ketersediaan slot waktu laboratorium</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="openBatchModal()"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm">
                <i class="fas fa-edit mr-2"></i>Batch Update
            </button>
            <button onclick="openCopyModal()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                <i class="fas fa-copy mr-2"></i>Salin Jadwal
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-calendar text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Total Slot</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Tersedia</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['available'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Diblokir</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['unavailable'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-chart-pie text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Ketersediaan</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['availability_percentage'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Navigation -->
    <div class="month-nav p-4 rounded-lg">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">{{ $selectedDate->format('F Y') }}</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.schedule.index') }}?month={{ $selectedDate->copy()->subMonthNoOverflow()->format('Y-m') }}"
                   class="px-3 py-1 text-gray-600 hover:text-gray-900 border rounded">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('admin.schedule.index') }}?month={{ now()->format('Y-m') }}"
                   class="px-3 py-1 text-gray-600 hover:text-gray-900 border rounded text-sm">
                    Bulan Ini
                </a>
                <a href="{{ route('admin.schedule.index') }}?month={{ $selectedDate->copy()->addMonthNoOverflow()->format('Y-m') }}"
                   class="px-3 py-1 text-gray-600 hover:text-gray-900 border rounded">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-lg border p-6">
        <div class="grid grid-cols-7 gap-3">
            <!-- Header Days -->
            <div class="text-center text-sm font-medium text-gray-500 py-2">Sen</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Sel</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Rab</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Kam</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Jum</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Sab</div>
            <div class="text-center text-sm font-medium text-gray-500 py-2">Min</div>

            <!-- Empty offset cells for first day alignment -->
            @for($i = 0; $i < $firstDayOffset; $i++)
                <div></div>
            @endfor

            <!-- Calendar Days -->
            @foreach($monthData as $day => $data)
                <div class="day-card p-3 rounded-lg text-center
                    {{ $data['status'] === 'available' ? 'day-available' : '' }}
                    {{ $data['status'] === 'limited' ? 'day-limited' : '' }}
                    {{ $data['status'] === 'full' ? 'day-full' : '' }}
                    {{ $data['status'] === 'closed' ? 'day-closed' : '' }}"
                    onclick="openDayModal('{{ $data['date']->format('Y-m-d') }}')">

                    <div class="text-lg font-semibold
                        {{ $data['status'] === 'available' ? 'text-green-700' : '' }}
                        {{ $data['status'] === 'limited' ? 'text-yellow-700' : '' }}
                        {{ $data['status'] === 'full' ? 'text-red-700' : '' }}
                        {{ $data['status'] === 'closed' ? 'text-gray-500' : '' }}">
                        {{ $day }}
                    </div>

                    @if($data['status'] !== 'closed')
                        <div class="text-xs text-gray-600 mt-1">
                            {{ $data['available_slots'] }}/{{ $data['total_slots'] }}
                        </div>
                        @if($data['visits_count'] > 0)
                            <div class="text-xs text-gray-500">
                                {{ $data['visits_count'] }} visit
                            </div>
                        @endif
                    @else
                        <div class="text-xs text-gray-500">Tutup</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg border p-4">
        <div class="flex flex-wrap gap-4 text-sm">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                <span>Tersedia (4+ slot)</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-yellow-500 rounded mr-2"></div>
                <span>Terbatas (1-3 slot)</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>
                <span>Penuh (0 slot)</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-gray-400 rounded mr-2"></div>
                <span>Tutup</span>
            </div>
        </div>
    </div>
</div>

<!-- Day Detail Modal -->
<div id="dayModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold" id="dayModalTitle">Detail Jadwal</h3>
                <button onclick="closeDayModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div id="dayModalContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Batch Update Modal -->
<div id="batchModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg w-full max-w-md mx-4">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Batch Update</h3>
                <button onclick="closeBatchModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="batchForm" class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" id="batchDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Slot Waktu</label>
                    <div class="grid grid-cols-2 gap-2" id="batchSlots">
                        <label class="flex items-center text-sm">
                            <input type="checkbox" value="08:00" class="mr-2"> 08:00-09:00
                        </label>
                        <label class="flex items-center text-sm">
                            <input type="checkbox" value="09:00" class="mr-2"> 09:00-10:00
                        </label>
                        <label class="flex items-center text-sm">
                            <input type="checkbox" value="10:00" class="mr-2"> 10:00-11:00
                        </label>
                        <label class="flex items-center text-sm">
                            <input type="checkbox" value="11:00" class="mr-2"> 11:00-12:00
                        </label>
                        <label class="flex items-center text-sm">
                            <input type="checkbox" value="13:00" class="mr-2"> 13:00-14:00
                        </label>
                        <label class="flex items-center text-sm">
                            <input type="checkbox" value="14:00" class="mr-2"> 14:00-15:00
                        </label>
                        <label class="flex items-center text-sm">
                            <input type="checkbox" value="15:00" class="mr-2"> 15:00-16:00
                        </label>
                        <label class="flex items-center text-sm">
                            <input type="checkbox" value="16:00" class="mr-2"> 16:00-17:00
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                    <select id="batchAction" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="enable">Aktifkan Slot</option>
                        <option value="disable">Nonaktifkan Slot</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeBatchModal()" class="px-4 py-2 text-gray-600 border rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Copy Schedule Modal -->
<div id="copyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg w-full max-w-md mx-4">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Salin Jadwal</h3>
                <button onclick="closeCopyModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="copyForm" class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sumber</label>
                    <input type="date" id="sourceDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Tujuan</label>
                    <textarea id="targetDates" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="2024-01-15&#10;2024-01-16&#10;2024-01-17" required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Satu tanggal per baris (YYYY-MM-DD)</p>
                </div>

                <div>
                    <label class="flex items-center text-sm">
                        <input type="checkbox" id="overwrite" class="mr-2">
                        Timpa pengaturan yang sudah ada
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeCopyModal()" class="px-4 py-2 text-gray-600 border rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Salin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let currentSlotTime = null;

    // Global functions that will be available for buttons in loaded content
    window.blockSlot = function(timeSlot) {
        console.log('Block slot called for:', timeSlot);

        const btn = event.target.closest('button');
        if (btn) {
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...';

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 1000);
        }

        currentSlotTime = timeSlot;
        const timeDisplay = timeSlot + ':00 - ' + (parseInt(timeSlot.split(':')[0]) + 1).toString().padStart(2, '0') + ':00';

        // Check if elements exist before setting values
        const blockSlotTimeEl = document.getElementById('blockSlotTime');
        const blockSlotModalEl = document.getElementById('blockSlotModal');

        if (blockSlotTimeEl) blockSlotTimeEl.value = timeDisplay;
        if (blockSlotModalEl) blockSlotModalEl.classList.remove('hidden');
    };

    window.unblockSlot = function(timeSlot) {
        console.log('Unblock slot called for:', timeSlot);

        if (confirm('Apakah Anda yakin ingin mengaktifkan slot ' + timeSlot + '?')) {
            const btn = event.target.closest('button');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengaktifkan...';
            }

            updateSlotStatus(timeSlot, true, null, null);
        }
    };

    window.enableAllSlots = function() {
        if (confirm('Apakah Anda yakin ingin mengaktifkan semua slot yang diblokir?')) {
            // Get current date from modal or use selected date
            const selectedDate = getCurrentSelectedDate();

            fetch(`{{ route('admin.schedule.show') }}?date=${selectedDate}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Parse the HTML to find blocked slots
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    const blockedButtons = tempDiv.querySelectorAll('.unblock-btn');

                    if (blockedButtons.length === 0) {
                        showAlert('info', 'Tidak ada slot yang diblokir untuk diaktifkan.');
                        return;
                    }

                    blockedButtons.forEach(btn => {
                        const timeSlot = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
                        updateSlotStatus(timeSlot, true, null, null);
                    });

                    setTimeout(() => {
                        loadDayDetails(selectedDate);
                    }, 1000);
                }
            });
        }
    };

    window.disableAllSlots = function() {
        const reason = prompt('Masukkan alasan untuk memblokir semua slot:');
        if (reason && confirm('Apakah Anda yakin ingin memblokir semua slot yang tersedia?')) {
            const selectedDate = getCurrentSelectedDate();

            fetch(`{{ route('admin.schedule.show') }}?date=${selectedDate}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    const availableButtons = tempDiv.querySelectorAll('.block-btn');

                    if (availableButtons.length === 0) {
                        showAlert('info', 'Tidak ada slot tersedia untuk diblokir.');
                        return;
                    }

                    availableButtons.forEach(btn => {
                        const timeSlot = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
                        updateSlotStatus(timeSlot, false, reason, 'Batch disable all slots');
                    });

                    setTimeout(() => {
                        loadDayDetails(selectedDate);
                    }, 1000);
                }
            });
        }
    };

    window.viewVisit = function(timeSlot) {
        console.log('View visit called for:', timeSlot);
        // This will be handled by the specific visit data in the loaded content
        showAlert('info', 'Membuka detail kunjungan...');
    };

    window.closeBlockModal = function() {
        const modal = document.getElementById('blockSlotModal');
        const form = document.getElementById('blockSlotForm');

        if (modal) modal.classList.add('hidden');
        if (form) form.reset();
        currentSlotTime = null;
    };

    function getCurrentSelectedDate() {
        // Try to get date from modal title or use current date
        const modalTitle = document.getElementById('dayModalTitle');
        if (modalTitle) {
            const titleText = modalTitle.textContent;
            const dateMatch = titleText.match(/\d{4}-\d{2}-\d{2}/);
            if (dateMatch) return dateMatch[0];
        }

        // Fallback to today's date
        return new Date().toISOString().split('T')[0];
    }

    function updateSlotStatus(timeSlot, isAvailable, reason, notes) {
        console.log('Updating slot:', { timeSlot, isAvailable, reason, notes });

        const selectedDate = getCurrentSelectedDate();

        fetch('{{ route("admin.schedule.update-slot") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                date: selectedDate,
                time_slot: timeSlot,
                is_available: isAvailable,
                reason: reason,
                notes: notes
            }),
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                showAlert('success', data.message || 'Slot berhasil diperbarui!');
                setTimeout(() => {
                    loadDayDetails(selectedDate);
                }, 1000);
            } else {
                showAlert('error', data.message || 'Gagal memperbarui slot.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memperbarui slot: ' + error.message);
        });
    }

    function openDayModal(date) {
        document.getElementById('dayModalTitle').textContent = `Jadwal ${new Date(date).toLocaleDateString('id-ID', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}`;
        loadDayDetails(date);
        document.getElementById('dayModal').classList.remove('hidden');
    }

    function loadDayDetails(date) {
        document.getElementById('dayModalContent').innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Memuat...</p>
            </div>
        `;

        fetch(`{{ route('admin.schedule.show') }}?date=${date}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('dayModalContent').innerHTML = data.html;

                // Re-attach event listeners for the new content
                attachModalEventListeners();
            } else {
                document.getElementById('dayModalContent').innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <p>Gagal memuat detail jadwal</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('dayModalContent').innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <p>Gagal memuat detail jadwal</p>
                </div>
            `;
        });
    }

    function attachModalEventListeners() {
        // Attach event listener for block slot form if it exists
        const blockForm = document.getElementById('blockSlotForm');
        if (blockForm) {
            // Remove existing listeners to prevent duplicates
            const newForm = blockForm.cloneNode(true);
            blockForm.parentNode.replaceChild(newForm, blockForm);

            // Add new listener
            newForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const reason = document.getElementById('blockReason').value;
                const notes = document.getElementById('blockNotes').value;

                if (currentSlotTime && reason) {
                    updateSlotStatus(currentSlotTime, false, reason, notes);
                    closeBlockModal();
                } else {
                    showAlert('error', 'Silakan isi alasan untuk memblokir slot.');
                }
            });
        }

        // Attach modal close listeners
        const blockModal = document.getElementById('blockSlotModal');
        if (blockModal) {
            blockModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBlockModal();
                }
            });
        }
    }

    function closeDayModal() {
        document.getElementById('dayModal').classList.add('hidden');
    }

    function openBatchModal() {
        document.getElementById('batchDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('batchModal').classList.remove('hidden');
    }

    function closeBatchModal() {
        document.getElementById('batchModal').classList.add('hidden');
        document.getElementById('batchForm').reset();
    }

    function openCopyModal() {
        document.getElementById('copyModal').classList.remove('hidden');
    }

    function closeCopyModal() {
        document.getElementById('copyModal').classList.add('hidden');
        document.getElementById('copyForm').reset();
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'bg-green-500' :
                          type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const iconClass = type === 'success' ? 'fa-check' :
                         type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle';

        const alert = document.createElement('div');
        alert.className = `fixed top-4 right-4 ${alertClass} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300`;
        alert.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${iconClass} mr-2"></i>
                ${message}
            </div>
        `;

        document.body.appendChild(alert);

        setTimeout(() => {
            alert.classList.remove('translate-x-full', 'opacity-0');
        }, 100);

        setTimeout(() => {
            alert.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    }

    // Form handlers
    document.getElementById('batchForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const date = document.getElementById('batchDate').value;
        const selectedSlots = Array.from(document.querySelectorAll('#batchSlots input:checked')).map(cb => cb.value);
        const action = document.getElementById('batchAction').value;

        if (!date || selectedSlots.length === 0) {
            alert('Silakan pilih tanggal dan minimal satu slot.');
            return;
        }

        fetch('{{ route("admin.schedule.batch-update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                date: date,
                slots: selectedSlots,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeBatchModal();
                window.location.reload();
            } else {
                alert(data.message || 'Gagal melakukan batch update.');
            }
        })
        .catch(error => {
            alert('Gagal melakukan batch update.');
        });
    });

    document.getElementById('copyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const sourceDate = document.getElementById('sourceDate').value;
        const targetDatesText = document.getElementById('targetDates').value;
        const overwrite = document.getElementById('overwrite').checked;

        if (!sourceDate || !targetDatesText) {
            alert('Silakan isi tanggal sumber dan tujuan.');
            return;
        }

        const targetDates = targetDatesText.split('\n').map(date => date.trim()).filter(date => date);

        fetch('{{ route("admin.schedule.copy") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                source_date: sourceDate,
                target_dates: targetDates,
                overwrite: overwrite
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeCopyModal();
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menyalin jadwal.');
            }
        })
        .catch(error => {
            alert('Gagal menyalin jadwal.');
        });
    });

    // Close modals on outside click
    ['dayModal', 'batchModal', 'copyModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });

    // Keyboard handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['dayModal', 'batchModal', 'copyModal'].forEach(modalId => {
                document.getElementById(modalId).classList.add('hidden');
            });
        }
    });

    console.log('Schedule script loaded successfully');
</script>
@endsection
