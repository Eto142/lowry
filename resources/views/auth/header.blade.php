<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ziiriel-arthouse</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Slab:wght@100..900&family=Sahitya:wght@400;700&display=swap"
    rel="stylesheet">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
    }

    body {
      font-family: "Poppins", sans-serif;
      font-weight: 400;
      font-style: normal;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Mobile Navigation */
    .mobile-nav {
      width: 100%;
    }

    .mobile-nav-top {
      display: flex;
      background-color: #333;
      color: white;
      width: 100%;
    }

    .mobile-nav-top .nav-item {
      flex: 1;
      text-align: center;
      padding: 10px;
      color: white;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
      border-right: 1px solid rgba(255, 255, 255, 0.2);
    }

    .mobile-nav-top .nav-item:last-child {
      border-right: none;
    }

    .mobile-nav-logo {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px 0;
      background-color: #f0f0f0;
    }

    /* Desktop Navigation */
    .navbar {
      position: relative;
    }

    .navbar-brand {
      z-index: 1030;
    }

    .navbar-brand img {
      max-height: 50px;
    }

    /* Modal Overlay */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.1);
      z-index: 1040;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 20px;
      overflow-y: auto;
    }

    .card {
      border-radius: 0;
      border: none;
      background-color: white;
      max-width: 100%;
      margin-top: 50px;
    }

    .form-control {
      border-radius: 0;
      padding: 0.6rem 0.75rem;
    }

    .btn {
      border-radius: 0;
      padding: 0.5rem 1.5rem;
    }

    .btn-dark {
      background-color: #000;
    }

    .btn-outline-dark:hover {
      background-color: #000;
      color: #fff;
    }

    .rounded-bit {
      border-radius: 15px;
    }

    .name-input {
      width: 50%;
    }

    .name-input:focus {
      box-shadow: none;
      border-color: red;
    }


    .border-red {
      border-color: red;
    }

    .input:focus {
      box-shadow: none !important;
      outline: none !important;
    }

    .input-group {
      width: 75%;
    }

    .input-group input {
      width: 100%;
    }

    .input-group input:focus {
      box-shadow: none;
      border-color: red;
    }

    .no-border-right {
      border-right: none;
    }

    .input-group-text {
      border-radius: 0;
      background-color: #fff;
    }

    .flex-grow-1 {
      flex-grow: 1;
    }

    footer {
      background-color: #f0f0f0;
      font-size: 0.9rem;
      position: relative;
      z-index: 1030;
      width: 100%;
    }

    footer a {
      color: #000;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 767.98px) {
      .col-md-8.border-end {
        border-right: none !important;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 2rem;
        margin-bottom: 2rem;
      }

      .col-md-4 {
        padding-top: 1rem;
      }

      .modal-overlay {
        align-items: flex-start;
      }

      .card {
        margin-top: 30px;
      }
    }

    /* Animation for form appearance */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .fade-in {
      animation: fadeIn 0.5s ease-in-out forwards;
    }
  </style>
</head>

<body>
  <div class="page-wrapper">
    <!-- Mobile Navigation Bar -->
    <div class="mobile-nav d-lg-none">
      <div class="mobile-nav-top">
        <a href="#" class="nav-item">
          <i class="bi bi-person-fill"></i> Sign In
        </a>
        <a href="#" class="nav-item">
          <i class="bi bi-cart"></i> My basket
        </a>
        <a href="#" class="nav-item">
          <i class="bi bi-search"></i> Search
        </a>
      </div>
      <div class="mobile-nav-logo">
        <a href="#" class="navbar-brand">
          <svg xmlns="http://www.w3.org/2000/svg" width="200" height="80" viewBox="0 0 200 80">
            <text x="10" y="50" font-family="Arial, sans-serif" font-size="40" font-weight="bold" fill="#333"><img
                src="{{asset('images/logo.png')}}" alt="ziiriel-arthouse" width="150px"></text>
          </svg>
        </a>
      </div>
    </div>

    <!-- Desktop Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom d-none d-lg-flex">
      <div class="container">
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link fw-bolder" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bolder" href="#">What's On</a>
            </li>
          </ul>
        </div>

        <!-- Logo centered -->
        <a class="navbar-brand mx-auto position-absolute top-50 start-50 translate-middle" href="{{ url('/') }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="200" height="80" viewBox="0 0 200 80">
            <text x="10" y="50" font-family="Arial, sans-serif" font-size="40" font-weight="bold" fill="#333"><img
                src="{{asset('images/logo.png')}}" alt="ziiriel-arthouse" width="150px"></text>
          </svg>
        </a>

        <div class="d-flex align-items-center ms-auto">
          <button class="btn btn-link text-dark me-2">
            <i class="bi bi-search"></i>
          </button>
          <a href="{{ url('/login') }}" class="btn btn-link text-dark text-decoration-none fw-bolder">
            <i class="bi bi-person-fill me-1"></i> Sign In
          </a>
          <a href="#" class="btn btn-link text-dark ms-2">
            <i class="bi bi-cart"></i>
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
      <div class="row mb-5">
        <div class="col-12">
          <p class="lead">
            In order to proceed, your personal details are required. Please
            <a href="{{ url('/login') }}" class="text-primary text-decoration-none">log-in</a> or
            <a href="{{ url('/register') }}" id="register-link" class="text-primary text-decoration-none">register</a>
            to continue
          </p>
        </div>
      </div>
    </div>