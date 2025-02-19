<!DOCTYPE html>
<html>
<head>
<title>Business Registration and Renewal System</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:700|Inter:400">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="main.css"> <!-- Link to main.css -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive viewport -->
</head>
<body>

<div class="header" id="navbar">
  <div class="header-left" id="navbar-left">
    <button class="header-toggle" id="toggle-button">☰</button>
    <div class="header-search" id="navbar-search">
      <input type="text" class="form-control" placeholder="Type something...">
    </div>
  </div>

  <div class="header-right" id="navbar-right">
    <button><i class="fas fa-bell"></i></button>
    <button><i class="fas fa-search"></i></button>
    <img src="placeholder-image.png" alt="User Avatar">
  </div>
</div>

<div class="sidebar" id="sidebar">
  <div class="sidebar-brand" id="sidebar-brand">
    <h2>LGU3 System</h2>
  </div>
  <ul class="sidebar-menu" id="sidebar-menu">
    <li class="active"><a href="#">Dashboard</a></li>
    <li><a href="#">Registration</a></li>
    <li><a href="#">Renewal</a></li>
    <li><a href="#">Users</a></li>
    <li><a href="#">Admin</a></li>
    <li><a href="#">Settings</a></li>
  </ul>
</div>

<div class="main-content">
  <h1>Main Content Area</h1>
  <p>This is where your main content goes.</p>
</div>

<script>
  const toggleButton = document.getElementById('toggle-button');
  const sidebar = document.querySelector('.sidebar');

  toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
  });
</script>

<!-- Font Awesome Script (CDN) -->
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>

</body>
</html>