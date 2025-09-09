<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fitness Tracker Landing Page</title>
  <style>
   body {
      margin: 0;
      font-family: Arial, sans-serif;
      color: white; 
      background: url("asset/cartoon-machines-gym_23-2151149012.jpg");
      background-size: cover;
      background-position: center;
      height: 100vh;
    }

    .navbar {
      display: flex;
      gap: 25px;
      padding: 15px 80px; 
      background: rgba(30, 30, 60, 0.85);
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 10;
      backdrop-filter: blur(4px);
    }

    .navbar a {
      color: #e0e0e0;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
    }

    .navbar a:hover {
      color: #a3d8ff;
    }

    .hero {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-start;
      height: 100vh;
      text-align: left;
      padding: 0 50px;
      background: rgba(0,0,0,0.6); 
    }

    .hero h1 {
      font-size: 3rem;
      margin-bottom: 10px;
      color: #ffffff;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
    }

    .hero p {
      font-size: 1.2rem;
      max-width: 600px;
      margin-bottom: 20px;
      color: #f0f0f0;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
    }

    .btn {
      background: rgba(30, 30, 60, 0.85);
      padding: 12px 25px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      color: white;
      font-size: 1.2rem;
      cursor: pointer;
    }

    .btn:hover {
      color: #a3d8ff;
    }

    .info {
      margin-top: 20px;
      font-size: 1rem;
      color: #ffd700;
    }
  </style>
</head>
<body>

<div class="navbar">
  <a href="index.php?page=landing">Home</a>
  <a href="index.php?page=about">About</a>
  <a href="index.php?page=auth&form=signup">Signup</a>
  <a href="index.php?page=auth&form=login">Login</a>
  <a href="index.php?page=contact">Contact Us</a>
</div>

  <div class="hero">
    <h1>Fitness Tracker</h1>
    <p>Track your workouts, monitor progress, and stay motivated with our all-in-one fitness tracker.</p>
    <a href="index.php?page=about"><button class="btn">Learn More</button></a>

    <div class="info">
      <?php echo "Hello, You have visited this page $visits times in this session."; ?>
    </div>
  </div>

</body>
</html>