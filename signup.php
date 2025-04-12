<?php
// signup.php
include("db_connect.php"); // DB connection
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phoneNumber = $_POST['country_code'] . $_POST['custom2'];
    $memberID = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']); // More secure
    $signupDate = date('Y-m-d');
    $groupID = 3;
    $isBanned = 0;
    $isApproved = 1;
    $custom1 = $conn->real_escape_string($_POST['custom1']);
    $custom2 = $conn->real_escape_string($phoneNumber);
    $custom3 = '';
    $custom4 = '';
    $comments = '';
    $pass_reset_key = '';
    $pass_reset_expiry = NULL;

    $check = $conn->query("SELECT * FROM membership_users WHERE memberID='$memberID'");
    if ($check->num_rows > 0) {
        $msg = "Username already taken.";
    } else {
        $sql = "INSERT INTO membership_users 
        (memberID, passMD5, email, signupDate, groupID, isBanned, isApproved,
         custom1, custom2, custom3, custom4, comments, pass_reset_key, pass_reset_expiry) 
        VALUES 
        ('$memberID', '$password', '$email', '$signupDate', $groupID, $isBanned, $isApproved,
         '$custom1', '$custom2', '$custom3', '$custom4', '$comments', '$pass_reset_key', NULL)";
        
        if ($conn->query($sql)) {
            header("Location: index.php?signIn=1&registered=1&username=" . urlencode($memberID));
            exit;
        } else {
            $msg = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: 'Inter', sans-serif;
    background-color: #fff8ef;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    position: relative;
    overflow: hidden;
  }

  canvas {
    position: fixed;
    top: 0;
    left: 0;
    z-index: -1;
  }

  .form-container {
    background: rgba(255, 255, 255, 0.6); /* 60% transparent */
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    padding: 2.5rem;
    width: 100%;
    max-width: 450px;
    border-radius: 18px;
    box-shadow: 0 15px 35px rgba(255, 179, 67, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
  }

  .form-container h2 {
    text-align: center;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    color: #FFB343;
  }

  .form-group {
    margin-bottom: 1.2rem;
  }

  .form-group label {
    font-size: 0.95rem;
    font-weight: 600;
    color: #333;
    display: block;
    margin-bottom: 6px;
  }

  .form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 1rem;
    transition: border 0.3s ease;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(3px);
  }

  .form-control:focus {
    border-color: #FFB343;
    outline: none;
  }

  .input-group {
    display: flex;
  }

  .input-group select {
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-right: none;
    border-radius: 10px 0 0 10px;
    background: rgba(255, 255, 255, 0.6);
  }

  .input-group input {
    flex: 1;
    border-radius: 0 10px 10px 0;
  }

  .submit-btn {
    background-color: #FFB343;
    color: white;
    padding: 0.9rem;
    width: 100%;
    font-size: 1rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  .submit-btn:hover {
    background-color: #e39e29;
  }

  @media (max-width: 500px) {
    .form-container {
      padding: 2rem 1.5rem;
    }
  }
</style>

</head>
<body>
  <canvas id="spiderCanvas"></canvas>
  <div class="form-container">
    <h2>Create Account</h2>
    
    <?php if (!empty($msg)) echo "<p style='color:red; text-align:center;'>$msg</p>"; ?>

    <form method="post" action="">
      <div class="form-group">
        <label for="fullName">Full Name</label>
        <input type="text" class="form-control" id="fullName" name="custom1" placeholder="Your full name" required oninput="generateUsername()" />
      </div>

      <div class="form-group">
        <label for="custom2">Phone Number</label>
        <div class="input-group">
          <select name="country_code" id="country_code">
            <option value="+91" selected>+91 (IN)</option>
            <option value="+1">+1 (US)</option>
            <option value="+44">+44 (UK)</option>
            <option value="+61">+61 (AU)</option>
          </select>
          <input class="form-control" name="custom2" id="custom2" type="tel" placeholder="9876543210" pattern="[0-9]{10,15}" required />
        </div>
      </div>

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" readonly required />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required />
      </div>

      <button type="submit" class="submit-btn">Sign Up</button>
    </form>
  </div>

  <script>
    function generateUsername() {
      const fullName = document.getElementById("fullName").value.trim().toLowerCase();
      const usernameField = document.getElementById("username");

      if (fullName.length === 0) {
        usernameField.value = "";
        return;
      }

      const cleanName = fullName.replace(/[^a-z]/g, "").substring(0, 8);
      const rand = Math.floor(Math.random() * 900 + 100);
      usernameField.value = cleanName + rand;
    }

    // Canvas animation
    const canvas = document.getElementById('spiderCanvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    let mouse = { x: null, y: null, radius: 150 };

    window.addEventListener('mousemove', (e) => {
      mouse.x = e.x;
      mouse.y = e.y;
    });

    window.addEventListener('touchmove', (e) => {
      if (e.touches.length > 0) {
        mouse.x = e.touches[0].clientX;
        mouse.y = e.touches[0].clientY;
      }
    }, { passive: true });

    window.addEventListener('touchend', () => {
      mouse.x = null;
      mouse.y = null;
    });

    class Particle {
      constructor(x, y, dx, dy, radius) {
        this.x = x;
        this.y = y;
        this.dx = dx;
        this.dy = dy;
        this.radius = radius;
      }
      draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = '#FFB343';
        ctx.fill();
      }
      update(particles) {
        if (this.x + this.radius > canvas.width || this.x - this.radius < 0) this.dx = -this.dx;
        if (this.y + this.radius > canvas.height || this.y - this.radius < 0) this.dy = -this.dy;
        this.x += this.dx;
        this.y += this.dy;
        this.draw();
        for (let p of particles) {
          const dx = this.x - p.x;
          const dy = this.y - p.y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < 100) {
            ctx.beginPath();
            ctx.strokeStyle = 'rgba(255, 179, 67, 0.15)';
            ctx.moveTo(this.x, this.y);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
          }
          if (mouse.x && mouse.y) {
            const mdx = this.x - mouse.x;
            const mdy = this.y - mouse.y;
            const mdist = Math.sqrt(mdx * mdx + mdy * mdy);
            if (mdist < mouse.radius) {
              ctx.beginPath();
              ctx.strokeStyle = 'rgba(255, 179, 67, 0.3)';
              ctx.moveTo(this.x, this.y);
              ctx.lineTo(mouse.x, mouse.y);
              ctx.stroke();
            }
          }
        }
      }
    }

    let particles = [];
    for (let i = 0; i < 150; i++) {
      let radius = 2;
      let x = Math.random() * canvas.width;
      let y = Math.random() * canvas.height;
      let dx = (Math.random() - 0.5) * 1;
      let dy = (Math.random() - 0.5) * 1;
      particles.push(new Particle(x, y, dx, dy, radius));
    }

    function animate() {
      requestAnimationFrame(animate);
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      particles.forEach(p => p.update(particles));
    }

    animate();

    window.addEventListener('resize', () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    });
  </script>
</body>
</html>
