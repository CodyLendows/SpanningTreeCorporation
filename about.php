<?php
$testimonials_file = 'js/testimonials.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents($testimonials_file), true);
    
    $new_testimonial = [
        'name' => ($_POST['name']),
        'company' => htmlspecialchars($_POST['company']),
        'text' => htmlspecialchars($_POST['testimonial'])
    ];
    
    $data['testimonials'][] = $new_testimonial;
    
    file_put_contents($testimonials_file, json_encode($data, JSON_PRETTY_PRINT));
    
    header('Location: about.php?submitted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About - Spanning Tree Corporation</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet" />
</head>
<body>
  <header>
    <div class="container header-container">
      <img src="images/logo.png" alt="Spanning Tree Corporation Logo" class="logo" />
      <nav>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="resources.html">Resources</a></li>
          <li><a href="solutions.html">Solutions</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="content">
      <div class="container">
        <h1>About Spanning Tree Corporation</h1>
        <p>
          We are pioneers in the field of network design and implementation. Our team of experts has been providing solutions to complex networking problems for over 20 years. We have worked with clients in a variety of industries, including healthcare, finance, and education.
        </p>
      </div>
    </section>
    <section class="meet-the-team">
      <div class="team-container">
        <h2>Meet the Team</h2>
        <div class="team-members">
          <div class="team-member">
            <img src="images/team-member-1.jpg" alt="Richard Fujisaki" />
            <h3>Richard Fujisaki</h3>
            <p>Lead Software Engineer</p>
          </div>
          <div class="team-member">
            <img src="images/team-member-2.jpg" alt="Howard Allen" />
            <h3>Howard Allen</h3>
            <p>Security Analyst</p>
          </div>
          <div class="team-member">
            <img src="images/team-member-3.jpg" alt="Kaito Williams" />
            <h3>Kaito Williams</h3>
            <p>Assistant Software Engineer</p>
          </div>
        </div>
      </div>
    </section>
    
    <section class="testimonials">
      <div class="container">
        <h2>What Our Clients Say</h2>
        <div class="testimonials-slider" id="testimonials-container">
          <!-- Testimonials will be loaded here -->
        </div>
        
        <div class="add-testimonial">
          <h3>Share Your Experience</h3>
          <form id="testimonial-form" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="text" name="company" placeholder="Company Name" required>
            <textarea name="testimonial" placeholder="Your testimonial..." required></textarea>
            <button type="submit" class="btn">Submit Testimonial</button>
          </form>
          <?php if (isset($_GET['submitted'])): ?>
            <p class="success-message">Thank you for your testimonial!</p>
          <?php endif; ?>
        </div>
      </div>
    </section>

  </main>

  <footer>
    <div class="container">
      <p>&copy; 2025 Spanning Tree Corporation. All rights reserved.</p>
    </div>
  </footer>

  <script src="js/main.js"></script>
</body>
</html>
