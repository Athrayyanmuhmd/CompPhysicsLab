@extends('layouts.admin')

@section('title', 'Kelola Kunjungan')

@section('styles')
<style>
    .status-badge {
        transition: all 0.2s ease;
    }
    .filter-section {
        background: linear-gradient(135deg, rgba(78, 106, 231, 1) 0%, rgba(30, 58, 138, 1) 0%);
    }
    .stats-card {
        background: linear-gradient(135deg, #1D4ED8E6 0%, #2a50bab3 100%);
        transition: transform 0.2s ease;
    }
    .stats-card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Kunjungan</h1>
            <p class="text-gray-600 mt-2">Kelola pengajuan kunjungan dari calon pengunjung</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportData()"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export Data
            </button>
            <button onclick="showStatistics()"
                    class="bg-indigo-900 text-white px-4 py-2 rounded-lg hover:bg-indigo-800 transition flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Lihat Statistik
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stats-card text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium">Total Kunjungan</p>
                <p class="text-3xl font-bold">{{ $statistics['total'] }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg stats-card" style="background: linear-gradient(135deg, rgba(29, 78, 216, 0.9) 100%, rgba(42, 80, 186, 0.7) 100%);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium">Menunggu</p>
                <p class="text-3xl font-bold">{{ $statistics['pending'] }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-blue-500 text-white p-6 rounded-xl shadow-lg stats-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium">Diproses</p>
                <p class="text-3xl font-bold">{{ $statistics['processing'] }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-cog text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-green-500 text-white p-6 rounded-xl shadow-lg stats-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium">Selesai</p>
                <p class="text-3xl font-bold">{{ $statistics['completed'] }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-section text-white p-6 rounded-xl shadow-lg mb-8">
    <form method="GET" action="{{ route('admin.visits.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2">Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama, email, institusi..."
                   class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/10 backdrop-blur text-white placeholder-white/70 focus:ring-2 focus:ring-white/50">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/10 backdrop-blur text-white focus:ring-2 focus:ring-white/50">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }} class="text-black">
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                   class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/10 backdrop-blur text-white focus:ring-2 focus:ring-white/50">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                   class="w-full px-3 py-2 border border-white/30 rounded-lg bg-white/10 backdrop-blur text-white focus:ring-2 focus:ring-white/50">
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition flex items-center font-medium">
                <i class="fas fa-search mr-2"></i>
                Filter
            </button>
            <a href="{{ route('admin.visits.index') }}" class="bg-white/20 text-white px-4 py-2 rounded-lg hover:bg-white/30 transition">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Visits Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pengunjung
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kontak
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jadwal
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Peserta
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($visits as $visit)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $visit->namaPengunjung }}</div>
                            <div class="text-sm text-gray-500">{{ $visit->institution }}</div>
                            <div class="text-xs text-gray-400 mt-1">{{ $visit->tujuan }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $visit->email }}</div>
                        <div class="text-sm text-gray-500">{{ $visit->phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $visit->formattedDate }}</div>
                        <div class="text-sm text-gray-500">{{ $visit->formattedTime }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center text-sm text-gray-900">
                            <i class="fas fa-users mr-2 text-gray-400"></i>
                            {{ $visit->jumlahPengunjung }} orang
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select class="status-dropdown status-badge px-3 py-1 rounded-full text-xs font-medium {{ $visit->statusBadgeColor }} border-0 focus:ring-2 focus:ring-blue-500"
                                data-visit-id="{{ $visit->id }}" data-current-status="{{ $visit->status }}">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $visit->status == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            @if($visit->status === 'PENDING')
                                <button onclick="approveVisit('{{ $visit->id }}')"
                                        class="text-green-600 hover:text-green-800 transition"
                                        title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="rejectVisit('{{ $visit->id }}')"
                                        class="text-red-600 hover:text-red-800 transition"
                                        title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            <a href="{{ route('admin.visits.show', $visit) }}"
                               class="text-blue-600 hover:text-blue-800 transition"
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.visits.edit', $visit) }}"
                               class="text-yellow-600 hover:text-yellow-800 transition"
                               title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(in_array($visit->status, ['CANCELLED', 'COMPLETED']))
                                <button onclick="deleteVisit('{{ $visit->id }}')"
                                        class="text-red-600 hover:text-red-800 transition"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p class="text-lg font-medium">Tidak ada pengajuan kunjungan</p>
                            <p class="text-sm">Belum ada pengajuan kunjungan yang masuk dari calon pengunjung</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($visits->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $visits->links() }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Kunjungan</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus kunjungan ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 transition">
                    Hapus
                </button>
                <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let deleteVisitId = null;

    // jQuery document ready
    $(document).ready(function() {
        console.log('Admin visits page loaded');
        console.log('CSRF Token:', csrfToken);
    });

    // Status change handler - FIXED VERSION
    $(document).on('change', '.status-dropdown', function() {
        const visitId = $(this).data('visit-id');
        const newStatus = $(this).val();
        const currentStatus = $(this).data('current-status');
        const dropdown = $(this);

        console.log('Status change:', {
            visitId: visitId,
            newStatus: newStatus,
            currentStatus: currentStatus
        });

        if (newStatus === currentStatus) {
            console.log('No change in status');
            return;
        }

        // Show loading state
        dropdown.prop('disabled', true);
        showAlert('info', 'Mengubah status...');

        $.ajax({
            url: `/admin/visits/${visitId}/status`,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({
                status: newStatus
            }),
            success: function(response) {
                console.log('Status update response:', response);

                if (response.success) {
                    // Update dropdown styling
                    dropdown.removeClass().addClass('status-dropdown status-badge px-3 py-1 rounded-full text-xs font-medium border-0 focus:ring-2 focus:ring-blue-500 ' + response.badgeColor);
                    dropdown.data('current-status', newStatus);
                    dropdown.prop('disabled', false);

                    // Show success message
                    showAlert('success', response.message);

                    // Refresh page after 2 seconds to update action buttons
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    // Revert dropdown
                    dropdown.val(currentStatus);
                    dropdown.prop('disabled', false);
                    showAlert('error', response.message || 'Gagal mengubah status');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });

                // Revert dropdown
                dropdown.val(currentStatus);
                dropdown.prop('disabled', false);

                let errorMessage = 'Gagal mengubah status. Silakan coba lagi.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMessage = 'Kunjungan tidak ditemukan.';
                } else if (xhr.status === 403) {
                    errorMessage = 'Anda tidak memiliki akses untuk mengubah status.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
                }

                showAlert('error', errorMessage);
            }
        });
    });

    // Approve visit - IMPROVED VERSION
    function approveVisit(visitId) {
        if (confirm('Apakah Anda yakin ingin menyetujui kunjungan ini?')) {
            console.log('Approving visit:', visitId);
            updateVisitStatus(visitId, 'PROCESSING');
        }
    }

    // Reject visit - IMPROVED VERSION
    function rejectVisit(visitId) {
        const reason = prompt('Masukkan alasan penolakan (opsional):');
        if (confirm('Apakah Anda yakin ingin menolak kunjungan ini?')) {
            console.log('Rejecting visit:', visitId, 'Reason:', reason);
            updateVisitStatus(visitId, 'CANCELLED', reason);
        }
    }

    // Update visit status - FIXED VERSION
    function updateVisitStatus(visitId, status, reason = null) {
        const requestData = {
            status: status
        };

        if (reason) {
            requestData.reason = reason;
        }

        console.log('Updating visit status:', requestData);

        // Show loading
        showAlert('info', 'Memproses permintaan...');

        $.ajax({
            url: `/admin/visits/${visitId}/status`,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify(requestData),
            success: function(response) {
                console.log('Status update success:', response);

                if (response.success) {
                    showAlert('success', response.message);

                    // Reload page after success
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert('error', response.message || 'Gagal mengubah status');
                }
            },
            error: function(xhr, status, error) {
                console.error('Status update error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText,
                    responseJSON: xhr.responseJSON
                });

                let errorMessage = 'Gagal mengubah status. Silakan coba lagi.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showAlert('error', errorMessage);
            }
        });
    }

    // Delete visit function
    function deleteVisit(visitId) {
        deleteVisitId = visitId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Export data
    function exportData() {
        const params = new URLSearchParams(window.location.search);
        const exportUrl = `{{ route('admin.visits.index') }}/export?${params.toString()}`;
        window.open(exportUrl, '_blank');
    }

    // Show statistics
    function showStatistics() {
        showAlert('info', 'Fitur statistik detail akan segera tersedia');
    }

    // Modal handlers
    document.getElementById('confirmDelete').onclick = function() {
        if (deleteVisitId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/visits/${deleteVisitId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    };

    document.getElementById('cancelDelete').onclick = function() {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteVisitId = null;
    };

    // Close modal when clicking outside
    document.getElementById('deleteModal').onclick = function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            deleteVisitId = null;
        }
    };

    // Alert function - IMPROVED VERSION
    function showAlert(type, message) {
        // Remove existing alerts
        $('.alert-notification').remove();

        const alertTypes = {
            'success': { color: 'bg-green-500', icon: 'check' },
            'error': { color: 'bg-red-500', icon: 'exclamation-triangle' },
            'info': { color: 'bg-blue-500', icon: 'info-circle' },
            'warning': { color: 'bg-yellow-500', icon: 'exclamation-triangle' }
        };

        const alertConfig = alertTypes[type] || alertTypes['info'];

        const alert = $(`
            <div class="alert-notification fixed top-4 right-4 ${alertConfig.color} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300 max-w-sm">
                <div class="flex items-center">
                    <i class="fas fa-${alertConfig.icon} mr-2"></i>
                    <span class="text-sm font-medium">${message}</span>
                </div>
            </div>
        `);

        $('body').append(alert);

        // Animate in
        setTimeout(() => {
            alert.removeClass('translate-x-full opacity-0');
        }, 100);

        // Auto hide after 4 seconds (except for loading messages)
        if (type !== 'info' || !message.includes('Memproses')) {
            setTimeout(() => {
                alert.addClass('translate-x-full opacity-0');
                setTimeout(() => alert.remove(), 300);
            }, 4000);
        }
    }

    // Debug function - can be called from console
    window.debugAdmin = function() {
        console.log('=== ADMIN DEBUG INFO ===');
        console.log('CSRF Token:', csrfToken);
        console.log('Status dropdowns:', $('.status-dropdown').length);
        console.log('Approve buttons:', $('[onclick*="approveVisit"]').length);
        console.log('Reject buttons:', $('[onclick*="rejectVisit"]').length);
        console.log('jQuery loaded:', typeof $ !== 'undefined');
        console.log('========================');
    };

    // Test function for status update
    window.testStatusUpdate = function(visitId, status) {
        console.log('Testing status update:', visitId, status);
        updateVisitStatus(visitId, status);
    };

    console.log('Admin visits JavaScript loaded successfully');
</script>
@endsection
