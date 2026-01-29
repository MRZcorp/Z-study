<x-header>Dashboard</x-header>
<x-navbar></x-navbar>
<x-sidebar>admin</x-sidebar>




    {{-- <div class="dashboard">
        
  --}}
  {{-- <style>

            /* Main Content Styles */
            .main-content {
                flex: 1;
                padding: 20px;
            }
            
            </style> --}}
        <!-- Main Content -->
        {{-- <div class="main-content"> --}}
            <!-- Header -->
            
            

            <!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- TOTAL USERS -->
    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
      <div>
        <div class="text-2xl font-bold text-slate-800">248</div>
        <div class="text-sm text-slate-500">Total Pengguna</div>
      </div>
      <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
        <span class="material-symbols-rounded text-3xl">group</span>
      </div>
    </div>
  
    <!-- ACTIVE USERS -->
    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
      <div>
        <div class="text-2xl font-bold text-slate-800">342</div>
        <div class="text-sm text-slate-500">Pengguna Aktif</div>
      </div>
      <div class="p-3 rounded-lg bg-green-100 text-green-600">
        <span class="material-symbols-rounded text-3xl">verified_user</span>
      </div>
    </div>
  
    <!-- SYSTEM HEALTH -->
    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
      <div>
        <div class="text-2xl font-bold text-slate-800">98%</div>
        <div class="text-sm text-slate-500">Kesehatan Sistem</div>
      </div>
      <div class="p-3 rounded-lg bg-pink-100 text-pink-600">
        <span class="material-symbols-rounded text-3xl">monitor_heart</span>
      </div>
    </div>
  
    <!-- ALERT -->
    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
      <div>
        <div class="text-2xl font-bold text-slate-800">5</div>
        <div class="text-sm text-slate-500">Peringatan</div>
      </div>
      <div class="p-3 rounded-lg bg-orange-100 text-orange-600">
        <span class="material-symbols-rounded text-3xl">warning</span>
      </div>
    </div>
  
    <!-- DOSEN -->
    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
      <div>
        <div class="text-2xl font-bold text-slate-800">248</div>
        <div class="text-sm text-slate-500">Total Dosen</div>
      </div>
      <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600">
        <span class="material-symbols-rounded text-3xl">school</span>
      </div>
    </div>
  
    <!-- DOSEN AKTIF -->
    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
      <div>
        <div class="text-2xl font-bold text-slate-800">342</div>
        <div class="text-sm text-slate-500">Dosen Aktif</div>
      </div>
      <div class="p-3 rounded-lg bg-green-100 text-green-600">
        <span class="material-symbols-rounded text-3xl">co_present</span>
      </div>
    </div>
  
    <!-- MAHASISWA -->
    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
      <div>
        <div class="text-2xl font-bold text-slate-800">248</div>
        <div class="text-sm text-slate-500">Total Mahasiswa</div>
      </div>
      <div class="p-3 rounded-lg bg-cyan-100 text-cyan-600">
        <span class="material-symbols-rounded text-3xl">groups</span>
      </div>
    </div>
  
    <!-- KELAS -->
    <div class="bg-white rounded-xl p-4 shadow flex items-center justify-between">
      <div>
        <div class="text-2xl font-bold text-slate-800">5</div>
        <div class="text-sm text-slate-500">Kelas Aktif</div>
      </div>
      <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
        <span class="material-symbols-rounded text-3xl">calendar_month</span>
      </div>
    </div>
    <div>
  </div>
  </div>


            <!-- Main Content Grid -->
            <div class="content-grid">
                <div class="left-column">
                    <!-- Activity Chart -->
                    <div class="chart-container">
                        <div class="section-header">
                            <h3 class="section-title">Activity Overview</h3>
                            <select style="padding: 5px; border-radius: 5px; border: 1px solid #ddd;">
                                <option>Last 7 Days</option>
                                <option>Last 30 Days</option>
                                <option>Last 90 Days</option>
                            </select>
                        </div>
                        <div class="chart-placeholder">
                            [Activity Chart Will Appear Here]
                        </div>
                    </div>

                    <!-- Recent Actions -->
                    <div class="recent-actions">
                        <div class="section-header">
                            <h3 class="section-title">Recent Actions</h3>
                            <button style="background: none; border: none; color: var(--primary); cursor: pointer;">
                                View All
                            </button>
                        </div>
                        <div class="actions-list">
                            <div class="action-item">
                                <div class="action-icon">
                                    
                                    <i><span class="material-symbols-rounded">Person_Add</span></i>
                                </div>
                                <div class="action-details">
                                    <div class="action-title">New user registered</div>
                                    <div class="action-time">2 minutes ago</div>
                                </div>
                            </div>
                            <div class="action-item">
                                <div class="action-icon">
                                   
                                    <i><span class="material-symbols-rounded">
                                        upload_file
                                        </span></i>
                                </div>
                                <div class="action-details">
                                    <div class="action-title">Document uploaded</div>
                                    <div class="action-time">15 minutes ago</div>
                                </div>
                            </div>
                            <div class="action-item">
                                <div class="action-icon">
                                    <i>
                                        <span class="material-symbols-rounded">settings</span>
                                    </i>
                                </div>
                                <div class="action-details">
                                    <div class="action-title">System settings updated</div>
                                    <div class="action-time">1 hour ago</div>
                                </div>
                            </div>
                            <div class="action-item">
                                <div class="action-icon">
                                    <i><span class="material-symbols-rounded">Shield_Lock</span></i>
                                </div>
                                <div class="action-details">
                                    <div class="action-title">Security patch applied</div>
                                    <div class="action-time">3 hours ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <!-- System Status -->
                    <div class="system-status">
                        <div class="section-header">
                            <h3 class="section-title">System Status</h3>
                            <i class="fas fa-sync-alt" style="color: var(--gray); cursor: pointer;"></i>
                        </div>
                        <div class="status-list">
                            <div class="status-item">
                                <span class="status-label">Server Uptime</span>
                                <span class="status-value good">99.9%</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">CPU Usage</span>
                                <span class="status-value">32%</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Memory Usage</span>
                                <span class="status-value">45%</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Database</span>
                                <span class="status-value good">Normal</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Last Backup</span>
                                <span class="status-value warning">12 hours ago</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <button class="action-btn">
                            <i><span class="material-symbols-rounded">Person_Add</span></i>
                            <span>Add User</span>
                        </button>
                        <button class="action-btn">
                            <i><span class="material-symbols-rounded">
                                file_export
                                </span></i>
                            <span>Export Data</span>
                        </button>
                        <button class="action-btn">
                            <i><span class="material-symbols-rounded">settings</span></i>
                            <span>Settings</span>
                        </button>
                        <button class="action-btn">
                            <i><span class="material-symbols-rounded">help</span></i>
                            
                            <span>Help</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    




  
<x-footer></x-footer>
 

