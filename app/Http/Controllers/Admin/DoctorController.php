<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        return view('Pages.Admin.Doctor_management', compact('doctors'));
    }

    public function create()
    {
        // ✅ Get all active staff users
        $staffMembers = User::where('role', 'staff')
                            ->where('status', 'active')
                            ->orderBy('name')
                            ->get();
        
        return view('Component.Admin.add_doctor', compact('staffMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string|max:20',
            'status' => 'nullable|in:active,inactive',
            'staff_id' => 'nullable|exists:users,id',  // ✅ Staff validation
        ]);

        try {
            $doctor = Doctor::create([
                'name' => $request->name,
                'specialization' => $request->specialization,
                'qualification' => $request->qualification,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status ?? 'active',
                'staff_id' => $request->staff_id,  // ✅ Save staff
                'slug' => Str::slug($request->name)
            ]);

            // ✅ Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Doctor Added',
                    'message' => 'Dr. ' . $doctor->name . ' (' . $doctor->specialization . ') has been added to the system',
                    'type' => 'doctor_added',
                    'data' => json_encode([
                        'icon' => 'fa-user-md',
                        'doctor_id' => $doctor->id,
                        'doctor_name' => $doctor->name,
                        'doctor_specialization' => $doctor->specialization,
                        'url' => route('admin.doctors.edit', $doctor->id)
                    ]),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Doctor added: ' . $doctor->name . ' by admin');

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor "' . $doctor->name . '" added successfully!');

        } catch (\Exception $e) {
            Log::error('Error adding doctor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        
        // ✅ Get all active staff users
        $staffMembers = User::where('role', 'staff')
                            ->where('status', 'active')
                            ->orderBy('name')
                            ->get();
        
        return view('Layout.edit-doctor', compact('doctor', 'staffMembers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $id,
            'phone' => 'required|string|max:20',
            'status' => 'nullable|in:active,inactive',
            'staff_id' => 'nullable|exists:users,id',  // ✅ Staff validation
        ]);

        try {
            $doctor = Doctor::findOrFail($id);
            
            $oldName = $doctor->name;
            $oldSpecialization = $doctor->specialization;
            
            $doctor->name = $request->name;
            $doctor->specialization = $request->specialization;
            $doctor->qualification = $request->qualification;
            $doctor->email = $request->email;
            $doctor->phone = $request->phone;
            $doctor->status = $request->status ?? $doctor->status;
            $doctor->staff_id = $request->staff_id;  // ✅ Update staff
            $doctor->slug = Str::slug($request->name);
            $doctor->save();

            // ✅ Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Doctor Updated',
                    'message' => 'Dr. ' . $doctor->name . ' (' . $doctor->specialization . ') has been updated',
                    'type' => 'doctor_updated',
                    'data' => json_encode([
                        'icon' => 'fa-user-edit',
                        'doctor_id' => $doctor->id,
                        'doctor_name' => $doctor->name,
                        'doctor_specialization' => $doctor->specialization,
                        'old_name' => $oldName,
                        'old_specialization' => $oldSpecialization,
                        'url' => route('admin.doctors.edit', $doctor->id)
                    ]),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Doctor updated: ' . $doctor->name . ' by admin');

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating doctor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            if (!in_array($status, ['active', 'inactive'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status. Only active/inactive allowed.'
                ], 400);
            }

            $doctor = Doctor::findOrFail($id);
            $oldStatus = $doctor->status;
            $doctor->status = $status;
            $doctor->save();

            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Doctor Status Updated',
                    'message' => 'Dr. ' . $doctor->name . ' status changed from ' . $oldStatus . ' to ' . $status,
                    'type' => 'doctor_status_updated',
                    'data' => json_encode([
                        'icon' => 'fa-exchange-alt',
                        'doctor_id' => $doctor->id,
                        'doctor_name' => $doctor->name,
                        'old_status' => $oldStatus,
                        'new_status' => $status,
                        'url' => route('admin.doctors.edit', $doctor->id)
                    ]),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Doctor status updated: ' . $doctor->name . ' from ' . $oldStatus . ' to ' . $status . ' by admin');

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctorName = $doctor->name;
            $doctorSpecialization = $doctor->specialization;
            $doctorId = $doctor->id;
            $doctor->delete();

            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Doctor Deleted',
                    'message' => 'Dr. ' . $doctorName . ' (' . $doctorSpecialization . ') has been removed from the system',
                    'type' => 'doctor_deleted',
                    'data' => json_encode([
                        'icon' => 'fa-user-times',
                        'doctor_name' => $doctorName,
                        'doctor_specialization' => $doctorSpecialization,
                        'doctor_id' => $doctorId
                    ]),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Doctor deleted: ' . $doctorName . ' by admin');

            return response()->json([
                'success' => true,
                'message' => 'Doctor "' . $doctorName . '" deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting doctor: ' . $e->getMessage()
            ], 500);
        }
    }
}