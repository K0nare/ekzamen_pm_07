<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD БД салон красоты</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background: #f0f2f5; }
        .navbar-brand { font-weight: bold; }
        .card { border-radius: 1rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05); }
        .btn-sm { border-radius: 2rem; }
        .table th { background-color: #e9ecef; }
        .action-icons a { margin: 0 4px; text-decoration: none; }
        body, .table, .card, .form-label, .navbar-text, .btn, .text-muted {
    color: #000000 !important;
}
.table th, .table td {
    color: #000000 !important;
}
.navbar-brand, .navbar-nav .nav-link {
    color: #ffffff !important; /* шапка остаётся белой на синем фоне */
}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-database me-2"></i>CRUD
        </a>
        <span class="navbar-text text-white-50">Салон красоты</span>
    </div>
</nav>
<div class="container">