<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Freelance Platform</title>
    
    <!-- Required Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --background-color: #f8fafc;
            --card-color: #ffffff;
            --text-color: #1e293b;
            --muted-color: #64748b;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            background: #1e293b;
            height: 100vh;
            position: fixed;
            width: 280px;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease;
            padding: 2rem;
        }

        .stat-card {
            background: var(--card-color);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .total-balance-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInUp 0.5s ease;
        }

        .chart-container {
            background: var(--card-color);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            height: 400px;
        }

        .nav-link {
            color: #94a3b8;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 4px 0;
        }

        .nav-link:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .section-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            border-radius: 12px 12px 0 0;
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }
            .main-content {
                margin-left: 0;
            }
        }

        .hidden {
            display: none;
        }

        .payment-btn {
            padding: 6px 16px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .payment-btn:hover {
            transform: scale(1.05);
        }

        .user-type-chart {
            max-width: 300px;
            margin: 0 auto;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: fadeInUp 0.5s ease;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: white;
        }

        .bg-primary { background-color: var(--primary-color); }
        .bg-warning { background-color: var(--warning-color); }
        .bg-info { background-color: #0ea5e9; }
        .bg-success { background-color: var(--success-color); }
        .bg-secondary { background-color: #64748b; }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar px-3 py-4">
    <div class="brand mb-5">
        <h3 class="text-white text-center">Admin Console</h3>
    </div>
    
    <nav class="nav flex-column">
        <a class="nav-link active" href="#dashboard" onclick="showSection('dashboard')">
            <i class="fas fa-home me-2"></i> Dashboard
        </a>
        <a class="nav-link" href="#users" onclick="showSection('users')">
            <i class="fas fa-users me-2"></i> User Management
        </a>
        <a class="nav-link" href="#jobs" onclick="showSection('jobs')">
            <i class="fas fa-briefcase me-2"></i> Job Management
        </a>
        <a class="nav-link" href="#financial" onclick="showSection('financial')">
            <i class="fas fa-coins me-2"></i> Financial
        </a>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    
    <!-- Dashboard Section -->
    <div id="dashboard">
        <!-- Top Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Platform Overview</h2>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card animate-card">
                    <h5 class="text-muted mb-3">Total Users</h5>
                    <h2 class="text-primary fw-bold" id="totalUsers">0</h2>
                    <div class="d-flex justify-content-between text-muted">
                        <small>Last 7 days</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card animate-card">
                    <h5 class="text-muted mb-3">Total Platform Revenue</h5>
                    <h2 class="text-success fw-bold" id="totalRevenue">$0.00</h2>
                    <div class="d-flex justify-content-between text-muted">
                        <small>This month</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card animate-card">
                    <h5 class="text-muted mb-3">Total Freelancer Earnings</h5>
                    <h2 class="text-warning fw-bold" id="totalFreelancerEarnings">$0.00</h2>
                    <div class="d-flex justify-content-between text-muted">
                        <small>This month</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card animate-card">
                    <h5 class="text-muted mb-3">Total Jobs Completed</h5>
                    <h2 class="text-info fw-bold" id="totalJobsCompleted">0</h2>
                    <div class="d-flex justify-content-between text-muted">
                        <small>This month</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-8">
                <div class="chart-container animate-card">
                    <h5 class="mb-3">Platform Growth</h5>
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="chart-container user-type-chart animate-card">
                    <h5 class="mb-3">User Distribution</h5>
                    <canvas id="userDistribution"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management Section -->
    <div id="users" class="hidden">
        <!-- Client Management -->
        <div class="card border-0 shadow mb-4 animate-card">
            <div class="section-header">
                <h5 class="mb-0">Client Management</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Joined</th>
                                <th>Jobs Posted</th>
                                <th>Total Spent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientsTableBody">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Freelancer Management -->
        <div class="card border-0 shadow animate-card">
            <div class="section-header">
                <h5 class="mb-0">Freelancer Management</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Freelancer</th>
                                <th>Skills</th>
                                <th>Rating</th>
                                <th>Completed</th>
                                <th>Earnings</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="freelancersTableBody">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Management Section -->
    <div id="jobs" class="hidden">
        <!-- Active Jobs -->
        <div class="card border-0 shadow mb-4 animate-card">
            <div class="section-header">
                <h5 class="mb-0">Active Jobs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Client</th>
                                <th>Budget</th>
                                <th>Proposals</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="activeJobsBody">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Job History -->
        <div class="card border-0 shadow animate-card">
            <div class="section-header">
                <h5 class="mb-0">Job History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Status</th>
                                <th>Client</th>
                                <th>Freelancer</th>
                                <th>Amount</th>
                                <th>Commission</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody id="jobHistoryBody">
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Section -->
    <div id="financial" class="hidden">
        <div class="row mb-4">
            <div class="col-12">
                <div class="total-balance-card">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-3">Platform Balance</h4>
                            <h1 class="display-4 fw-bold" id="platformBalance">$0.00</h1>
                            <p class="mb-0">Total payments received</p>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-3">Platform Revenue</h4>
                            <h1 class="display-4 fw-bold" id="platformRevenue">$0.00</h1>
                            <p class="mb-0">20% commission from all payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="financialTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="client-tab" data-bs-toggle="tab" data-bs-target="#client-payments" type="button" role="tab">Client Payments</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="freelancer-tab" data-bs-toggle="tab" data-bs-target="#freelancer-payouts" type="button" role="tab">Freelancer Payouts</button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="financialTabsContent">
            <!-- Client Payments Tab -->
            <div class="tab-pane fade show active" id="client-payments" role="tabpanel">
                <div class="card border-0 shadow mb-4 animate-card">
                    <div class="card-header bg-white border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">Client Payments</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <select class="form-select form-select-sm" id="clientPaymentFilter">
                                            <option value="all">All Payments</option>
                                            <option value="this_month">This Month</option>
                                            <option value="last_month">Last Month</option>
                                            <option value="this_year">This Year</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control form-control-sm" id="clientSearch" placeholder="Search clients...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Client</th>
                                        <th>Job Title</th>
                                        <th>Amount</th>
                                        <th>Platform Fee (20%)</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="clientPaymentsBody">
                                    <!-- Client payments data will be populated here -->
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Client payments pagination">
                            <ul class="pagination justify-content-center mt-3" id="clientPaymentsPagination">
                                <!-- Pagination will be added here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Freelancer Payouts Tab -->
            <div class="tab-pane fade" id="freelancer-payouts" role="tabpanel">
                <div class="card border-0 shadow animate-card">
                    <div class="card-header bg-white border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">Freelancer Payouts</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    <div class="me-2">
                                        <select class="form-select form-select-sm" id="freelancerPayoutFilter">
                                            <option value="pending">Pending Approval</option>
                                            <option value="approved">Approved Work</option>
                                            <option value="paid">Paid</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control form-control-sm" id="freelancerSearch" placeholder="Search freelancers...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Submission ID</th>
                                        <th>Freelancer</th>
                                        <th>Job Title</th>
                                        <th>Client</th>
                                        <th>Amount</th>
                                        <th>Platform Fee (15%)</th>
                                        <th>Payout Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="freelancerPayoutsBody">
                                    <!-- Freelancer payouts data will be populated here -->
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Freelancer payouts pagination">
                            <ul class="pagination justify-content-center mt-3" id="freelancerPayoutsPagination">
                                <!-- Pagination will be added here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch dashboard data
    fetch('admin_handle.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error(data.error);
                return;
            }

            // Update Total Users
            document.getElementById('totalUsers').textContent = data.totalUsers;
            
            // Update Total Platform Revenue
            document.getElementById('totalRevenue').textContent = '$' + data.totalPlatformRevenue.toFixed(2);
            
            // Update Total Freelancer Earnings
            document.getElementById('totalFreelancerEarnings').textContent = '$' + data.totalFreelancerEarnings.toFixed(2);
            
            // Update Total Jobs Completed
            document.getElementById('totalJobsCompleted').textContent = data.totalJobsCompleted;

            // Initialize Platform Growth Chart
            const growthChart = new Chart(document.getElementById('growthChart'), {
                type: 'line',
                data: {
                    labels: data.growthChartLabels,
                    datasets: [{
                        label: 'New Users',
                        data: data.growthChartData,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 5,
                        pointBackgroundColor: '#6366f1',
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e2e8f0'
                            },
                            title: {
                                display: true,
                                text: 'New Users'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Weeks'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 12
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            });

            // Initialize User Distribution Chart with dynamic data
            const userDistribution = new Chart(document.getElementById('userDistribution'), {
                type: 'doughnut',
                data: {
                    labels: ['Freelancers', 'Clients'],
                    datasets: [{
                        data: [data.freelancers, data.clients],
                        backgroundColor: ['#6366f1', '#10b981'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 12
                        }
                    },
                    cutout: '70%'
                }
            });
        })
        .catch(error => console.error('Error:', error));

    // Fetch and populate client data
    function fetchClients() {
        fetch('admin_handle.php?action=getClients')
            .then(response => response.json())
            .then(data => {
                if (!data.success) return;

                const tbody = document.getElementById('clientsTableBody');
                tbody.innerHTML = '';

                data.clients.forEach(client => {
                    tbody.innerHTML += `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="avatar.png" class="rounded-circle me-2" width="35" height="35">
                                    ${client.users_name}
                                </div>
                            </td>
                            <td>${client.joined_date}</td>
                            <td>${client.jobs_posted}</td>
                            <td>$${client.total_spent}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-comment-alt"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            });
    }

    // Fetch and populate freelancer data
    function fetchFreelancers() {
        fetch('admin_handle.php?action=getFreelancers')
            .then(response => response.json())
            .then(data => {
                if (!data.success) return;

                const tbody = document.getElementById('freelancersTableBody');
                tbody.innerHTML = '';

                data.freelancers.forEach(freelancer => {
                    tbody.innerHTML += `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="avatar.png" class="rounded-circle me-2" width="35" height="35">
                                    ${freelancer.name}
                                </div>
                            </td>
                            <td>${freelancer.skills}</td>
                            <td><span class="text-warning"><i class="fas fa-star"></i> ${freelancer.rating}</span></td>
                            <td>${freelancer.jobs_completed}</td>
                            <td>$${freelancer.earnings}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-comment-alt"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            });
    }

    // Fetch and populate active jobs data
    function fetchActiveJobs() {
        fetch('admin_handle.php?action=getActiveJobs')
            .then(response => response.json())
            .then(data => {
                if (!data.success) return;

                const tbody = document.getElementById('activeJobsBody');
                tbody.innerHTML = '';

                data.activeJobs.forEach(job => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${job.job_title}</td>
                            <td>${job.client_name || 'N/A'}</td>
                            <td>$${job.budget}</td>
                            <td>${job.proposals}</td>
                            <td>${new Date(job.deadline).toLocaleDateString()}</td>
                            <td><span class="status-badge ${getStatusClass(job.status)}">${job.status}</span></td>
                        </tr>
                    `;
                });
            });
    }

    // Fetch and populate job history data
    function fetchJobHistory() {
        fetch('admin_handle.php?action=getJobHistory')
            .then(response => response.json())
            .then(data => {
                if (!data.success) return;

                const tbody = document.getElementById('jobHistoryBody');
                tbody.innerHTML = '';

                data.jobHistory.forEach(job => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${job.job_title}</td>
                            <td><span class="status-badge ${getStatusClass(job.status)}">${job.status}</span></td>
                            <td>${job.client_name}</td>
                            <td>${job.freelancer_name}</td>
                            <td>$${job.amount}</td>
                            <td>$${job.commission}</td>
                            <td>${new Date(job.completed_at).toLocaleDateString()}</td>
                        </tr>
                    `;
                });
            });
    }

    // Helper function to get status badge class
    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case 'open': return 'bg-primary';
            case 'bidding': return 'bg-warning';
            case 'in progress': return 'bg-info';
            case 'submitted': return 'bg-success';
            default: return 'bg-secondary';
        }
    }

    function showSection(sectionId) {
        // Remove 'active' class from all nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });

        // Add 'active' class to the clicked nav link
        const activeLink = document.querySelector(`.nav-link[href="#${sectionId}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }

        // Hide all sections and show only the selected one
        ['dashboard', 'users', 'jobs', 'financial'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                if (id === sectionId) {
                    element.classList.remove('hidden');
                    element.classList.add('animate__animated', 'animate__fadeIn');

                    // Load data when section becomes visible
                    if (id === 'users') {
                        fetchClients();
                        fetchFreelancers();
                    } else if (id === 'jobs') {
                        fetchActiveJobs();
                        fetchJobHistory();
                    } else if (id === 'financial') {
                        fetchFinancialData();
                    }
                } else {
                    element.classList.add('hidden');
                }
            }
        });
    }

    // Handle URL hash changes
    function handleHashChange() {
        const sectionId = window.location.hash.substring(1); // Remove the '#' from the hash
        if (sectionId) {
            showSection(sectionId);
        }
    }

    // Listen for hash changes
    window.addEventListener('hashchange', handleHashChange);

    // Initialize default view based on URL hash
    const defaultSection = window.location.hash ? window.location.hash.substring(1) : 'dashboard';
    showSection(defaultSection);

    // Add click event listeners to sidebar links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default anchor behavior
            const sectionId = this.getAttribute('href').substring(1); // Get section ID from href
            window.location.hash = sectionId; // Update URL hash
            showSection(sectionId); // Manually trigger section change
        });
    });
});

// Fetch financial data
function fetchFinancialData() {
    fetch('admin_handle.php?action=getFinancialData')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error(data.error);
                return;
            }

            // Update platform balance and revenue
            document.getElementById('platformBalance').textContent = '$' + data.total_payments.toFixed(2);
            document.getElementById('platformRevenue').textContent = '$' + data.platform_revenue.toFixed(2);

            // Populate client payments table
            const clientPaymentsBody = document.getElementById('clientPaymentsBody');
            clientPaymentsBody.innerHTML = '';
            
            data.client_payments.forEach(payment => {
                const platformFee = payment.amount * 0.2;
                clientPaymentsBody.innerHTML += `
                    <tr>
                        <td>#${payment.id}</td>
                        <td>${payment.client_name}</td>
                        <td>${payment.job_title}</td>
                        <td>$${payment.amount.toFixed(2)}</td>
                        <td>$${platformFee.toFixed(2)}</td>
                        <td>${new Date(payment.payment_date).toLocaleDateString()}</td>
                        <td><span class="status-badge bg-success">Paid</span></td>
                    </tr>
                `;
            });

            // Populate freelancer payouts table
            const freelancerPayoutsBody = document.getElementById('freelancerPayoutsBody');
            freelancerPayoutsBody.innerHTML = '';
            
            data.freelancer_payouts.forEach(payout => {
                const platformFee = payout.amount * 0.15;
                const payoutAmount = payout.amount - platformFee;
                const statusBadge = payout.status === 'Approved' ? 'bg-warning' : 
                                   payout.status === 'Paid' ? 'bg-success' : 'bg-secondary';
                
                freelancerPayoutsBody.innerHTML += `
                    <tr>
                        <td>#${payout.submission_id}</td>
                        <td>${payout.freelancer_name}</td>
                        <td>${payout.job_title}</td>
                        <td>${payout.client_name}</td>
                        <td>$${payout.amount.toFixed(2)}</td>
                        <td>$${platformFee.toFixed(2)}</td>
                        <td>$${payoutAmount.toFixed(2)}</td>
                        <td><span class="status-badge ${statusBadge}">${payout.status}</span></td>
                        <td>
                            ${payout.status === 'Approved' ? `
                            <button class="btn btn-sm btn-success payment-btn" onclick="processPayout(${payout.submission_id}, ${payoutAmount})">
                                <i class="fas fa-money-bill-wave me-2"></i>Pay Now
                            </button>` : ''}
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => console.error('Error:', error));
}

// Process payout to freelancer
function processPayout(submissionId, amount) {
    if (!confirm(`Are you sure you want to process this payout of $${amount.toFixed(2)}?`)) {
        return;
    }

    fetch('admin_handle.php?action=processPayout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            submission_id: submissionId,
            amount: amount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payout processed successfully!');
            fetchFinancialData(); // Refresh the data
        } else {
            alert('Error processing payout: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the payout.');
    });
}
</script>
</body>
</html>