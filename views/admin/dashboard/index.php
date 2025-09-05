<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= htmlspecialchars($stats['total_users'] ?? 0) ?></h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="/admin/users" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= htmlspecialchars($stats['active_sessions'] ?? 0) ?></h3>
                <p>Active Sessions</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= htmlspecialchars($stats['system_uptime'] ?? '0%') ?></h3>
                <p>System Uptime</p>
            </div>
            <div class="icon">
                <i class="fas fa-server"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>65</h3>
                <p>Unique Visitors</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Activity</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe</td>
                                <td>Logged in</td>
                                <td>2 minutes ago</td>
                            </tr>
                            <tr>
                                <td>Jane Smith</td>
                                <td>Updated profile</td>
                                <td>5 minutes ago</td>
                            </tr>
                            <tr>
                                <td>Bob Johnson</td>
                                <td>Created new post</td>
                                <td>10 minutes ago</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="btn-group-vertical w-100" role="group">
                    <a href="/admin/users" class="btn btn-primary mb-2">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                    <a href="/admin/settings" class="btn btn-secondary mb-2">
                        <i class="fas fa-cog"></i> System Settings
                    </a>
                    <a href="/admin/api/stats" class="btn btn-info mb-2">
                        <i class="fas fa-chart-bar"></i> View API Stats
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
