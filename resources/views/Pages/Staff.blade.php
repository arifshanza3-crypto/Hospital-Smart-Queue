@extends('Layout.staff_app')
@section('title', 'Staff Portal - Smart Queue Management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/Staff.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <section class="hero-header">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <span class="badge-top">Staff Portal</span>
                    <h1>Smart Queue Management</h1>
                    <p>Real-time oversight of physical walk-ins and digital bookings. Optimize patient flow with a single click.</p>
                </div>
                <div class="hero-actions">
                    <button class="btn btn-primary" onclick="openModal('patientModal')">+ Add Physical Patient</button>
                    <button class="btn btn-secondary" onclick="openModal('timeModal')">⏱ Set Global Time</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Total in Queue</span>
                    <h2 id="stat-total">{{ $totalQueue ?? 0 }}</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Now Serving</span>
                    <h2 id="stat-serving">{{ $nowServingToken ?? '--' }}</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Pending Wait</span>
                    <h2 id="stat-avg-time">{{ $avgWaitTime ?? 0 }}m</h2>
                </div>
            </div>
        </div>
    </section>

    <main class="container">
        <div class="data-card">
            <div class="table-scroll-hint">
                <i class="fas fa-arrows-left-right"></i> Swipe to see more
            </div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Token #</th>
                            <th>Patient Info</th>
                            <th>Doctor</th>
                            <th>Type</th>
                            <th>Est. Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="queue-body">
                        @if(isset($tokens) && $tokens->count() > 0)
                            @foreach($tokens as $token)
                            <tr id="token-row-{{ $token->id }}">
                                <td><strong>{{ $token->token_number }}</strong></td>
                                <td>
                                    <div>{{ $token->patient_name ?? 'N/A' }}</div>
                                    <small class="patient-phone">{{ $token->phone ?? '' }}</small>
                                </td>
                                <td>
                                    @if($token->doctor)
                                        <div style="font-weight: 600; color: #0a2a3a;">Dr. {{ $token->doctor->name }}</div>
                                        <small style="color: #64748b;">{{ $token->doctor->specialization }}</small>
                                    @else
                                        <span style="color: #94a3b8;">N/A</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($token->type ?? 'online') }}</td>
                                <td>{{ $token->estimated_time ?? 0 }} min</td>
                                <td class="status-td">
                                    <span class="status-badge {{ $token->status }}">
                                        {{ ucfirst($token->status) }}
                                    </span>
                                </td>
                                <td class="action-td">
                                    @if($token->status == 'waiting')
                                        <button onclick="updateTokenStatus({{ $token->id }}, 'calling')" class="btn-sm btn-call">
                                            <i class="fas fa-phone"></i> Call
                                        </button>
                                        <button onclick="updateTokenStatus({{ $token->id }}, 'cancelled')" class="btn-sm btn-cancel">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>

                                    @elseif($token->status == 'calling')
                                        <button onclick="updateTokenStatus({{ $token->id }}, 'serving')" class="btn-sm btn-start">
                                            <i class="fas fa-play"></i> Start
                                        </button>
                                        <button onclick="updateTokenStatus({{ $token->id }}, 'cancelled')" class="btn-sm btn-cancel">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>

                                    @elseif($token->status == 'serving')
                                        <button onclick="updateTokenStatus({{ $token->id }}, 'completed')" class="btn-sm btn-complete">
                                            <i class="fas fa-check"></i> Complete
                                        </button>

                                    @elseif($token->status == 'completed')
                                        <span class="badge-served">
                                            <i class="fas fa-check-circle"></i> Served
                                        </span>

                                    @elseif($token->status == 'cancelled')
                                        <span class="badge-cancelled">
                                            <i class="fas fa-times-circle"></i> Cancelled
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr id="empty-row">
                                <td colspan="7" class="empty-cell">
                                    <i class="fas fa-inbox"></i>
                                    No patients in queue
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Patient Modal - UPDATED with Doctor Selection -->
    <div id="patientModal" class="modal">
        <div class="modal-content">
            <h3>Add New Patient</h3>
            
            <!-- Full Name -->
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="p_name" placeholder="Enter name..." required>
            </div>
            <!-- Mobile Number -->
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="tel" id="p_mobile" placeholder="03XX-XXXXXXX" required maxlength="11">
                <small id="mobileError" class="error-msg">Please enter a valid 11-digit number starting with 03</small>
            </div>

            <!-- ✅ NEW: Select Doctor Dropdown -->
            <div class="form-group">
                <label>Select Doctor</label>
                <select id="p_doctor" required>
                    <option value="">-- Select Doctor --</option>
                    @forelse($doctors ?? [] as $doctor)
                        <option value="{{ $doctor->id }}">
                            Dr. {{ $doctor->name }} - {{ $doctor->specialization }}
                        </option>
                    @empty
                        <option value="" disabled>No doctors available</option>
                    @endforelse
                </select>
                <small id="doctorError" class="error-msg">Please select a doctor</small>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-text" onclick="closeModal('patientModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitPatient()">Add to Queue</button>
            </div>
        </div>
    </div>

    <!-- Time Modal -->
    <div id="timeModal" class="modal">
        <div class="modal-content">
            <h3>Set Global Est. Time</h3>
            <div class="form-group">
                <label>Minutes per patient</label>
                <input type="number" id="global_min" value="15">
            </div>
            <div class="modal-footer">
                <button class="btn btn-text" onclick="closeModal('timeModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitGlobalTime()">Update All</button>
            </div>
        </div>
    </div>

    <!-- External Script File -->
    <script src="{{ asset('js/Staff.js') }}"></script>

    {{-- ✅ Inline Script for Submit Patient with Doctor --}}
    <script>
    // Override submitPatient to include doctor_id
    if (typeof window.submitPatient === 'undefined' || true) {
        window.submitPatient = function() {
            const name = document.getElementById('p_name')?.value?.trim();
            const doctorId = document.getElementById('p_doctor')?.value?.trim();
            const mobile = document.getElementById('p_mobile')?.value?.trim();
            const doctorError = document.getElementById('doctorError');
            const mobileError = document.getElementById('mobileError');

            // Hide previous errors
            doctorError.style.display = 'none';
            mobileError.style.display = 'none';

            // Validate Name
            if (!name) {
                alert('Please enter patient name');
                return;
            }

            // Validate Doctor
            if (!doctorId) {
                doctorError.style.display = 'block';
                document.getElementById('p_doctor').style.border = '1px solid #dc3545';
                return;
            }

            // Validate Mobile
            if (!mobile) {
                alert('Please enter mobile number');
                return;
            }

            const isValidMobile = /^(03)\d{9}$/.test(mobile);
            if (!isValidMobile) {
                mobileError.style.display = 'block';
                document.getElementById('p_mobile').style.border = '1px solid #dc3545';
                return;
            }

            // Submit via AJAX
            fetch('/staff/add-patient', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: name,
                    doctor_id: doctorId,
                    mobile_number: mobile
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal('patientModal');
                    // Reset form
                    document.getElementById('p_name').value = '';
                    document.getElementById('p_doctor').value = '';
                    document.getElementById('p_mobile').value = '';
                    // Reload page
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Error adding patient');
            });
        };

        // Mobile validation
        document.addEventListener('DOMContentLoaded', function() {
            const mobileInput = document.getElementById('p_mobile');
            const mobileError = document.getElementById('mobileError');

            if (mobileInput) {
                mobileInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11);
                    }
                    const isValid = /^(03)\d{9}$/.test(this.value);
                    if (this.value.length > 0 && !isValid) {
                        mobileError.style.display = 'block';
                        this.style.border = '1px solid #dc3545';
                    } else {
                        mobileError.style.display = 'none';
                        this.style.border = '1px solid #e2e8f0';
                    }
                });
            }

            // Doctor validation
            const doctorSelect = document.getElementById('p_doctor');
            if (doctorSelect) {
                doctorSelect.addEventListener('change', function() {
                    if (this.value) {
                        document.getElementById('doctorError').style.display = 'none';
                        this.style.border = '1px solid #e2e8f0';
                    }
                });
            }
        });
    }
    </script>
@endsection