<x-header></x-header>
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
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-value">248</div>
                            <div class="stat-label">Total Pengguna</div>
                        </div>
                        <div class="p-3 rounded-lg bg-blue-200 text-blue-600">
                            <i ><span class="material-symbols-rounded">Groups</span></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-value">342</div>
                            <div class="stat-label">Pengguna Aktif</div>
                        </div>
                        <div class="p-3 rounded-lg bg-green-200 text-green-600">
                            <i><span class="material-symbols-rounded">Cell_Tower</span></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-value">98%</div>
                            <div class="stat-label">Kesehatan System</div>
                        </div>
                         <div class="p-3 rounded-lg bg-pink-200 text-pink-600 ">

                            <i><span class="material-symbols-rounded">Cardiology</span></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-value">5</div>
                            <div class="stat-label">Peringatan Penanganan</div>
                        </div>
                        <div class="p-3 rounded-lg bg-orange-200 text-orange-600">
                            <i ><span class="material-symbols-rounded">Warning</span></i>
                        </div>
                    </div>
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
 