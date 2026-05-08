<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: #f8f9fc;
        }
        
        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand h4 {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
        }
        
        .sidebar-brand small {
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            border-left: 4px solid #fff;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }
        
        /* Dashboard Cards */
        .dashboard-card {
            border-radius: 0.75rem;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 0.15rem 1.75rem rgba(58, 59, 69, 0.15);
            transition: transform 0.2s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-3px);
        }
        
        .bg-primary-gradient {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }
        
        .bg-success-gradient {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        }
        
        .bg-warning-gradient {
            background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
        }
        
        .bg-info-gradient {
            background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        }
        
        .bg-danger-gradient {
            background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        }
        
        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        /* Table Styles */
        .table {
            font-size: 0.9rem;
        }
        
        .table th {
            background: #f8f9fc;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem rgba(58, 59, 69, 0.08);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }
        
        /* Button Styles */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.75rem;
        }
        
        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: #4e73df;
        }
        
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 2px solid #e3e6f0;
            padding: 0.625rem 0.875rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        /* Progress Bar */
        .progress {
            height: 1.25rem;
            border-radius: 0.5rem;
            background: #eaecf4;
        }
        
        /* Page Header */
        .page-header {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        
        /* AHP Detail Card */
        .ahp-detail {
            background: #f8f9fc;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        
        .ahp-criteria {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #e3e6f0;
        }
        
        .ahp-criteria:last-child {
            border-bottom: none;
        }
        
        /* Footer */
        footer {
            margin-top: 3rem;
            padding: 2rem 0;
            text-align: center;
            color: #858796;
            border-top: 1px solid #e3e6f0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Text utilities */
        .text-white-75 {
            color: rgba(255,255,255,0.75);
        }
        
        .text-dark-75 {
            color: rgba(0,0,0,0.75);
        }
        
        /* Kategori badges */
        .badge.bg-success { background-color: #28a745 !important; }
        .badge.bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .badge.bg-info { background-color: #17a2b8 !important; }
        .badge.bg-danger { background-color: #dc3545 !important; }
    </style>
</head>
<body>
