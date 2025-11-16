<?php
require_once '../config/check_login.php';

$message = null;
if (isset($_SESSION['contact_message'])) {
    $message = $_SESSION['contact_message'];
    unset($_SESSION['contact_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="utils/main.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <title>Contact Us</title>
</head>
<body>


<aside class="sidebar">
  <ul>
    <li>
      <a class="home" href="/clients/index.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
          <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
          </svg>
        </div>
        Home
      </a>
    </li>

    <li>
      <a href="/clients/services.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
            <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/>
          </svg>
        </div>
        Services
      </a>
    </li>
    <li>
      <a href="/clients/ordered.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-check" viewBox="0 0 16 16">
            <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
          </svg>
        </div>
        Ordered
      </a>
    </li>
    <li>
      <a href="/clients/profile.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
          </svg>
        </div>
        Profile
      </a>
    </li>
    <li>
      <a class="about" href="/clients/about.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
          </svg>
        </div>
        About
      </a>
    </li>
    <li>
        <a class="contact  active" href="/clients/contact.php">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                    <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                </svg>
            </div>
            Contact
        </a>
    </li>
  </ul>
</aside>

<!-- Main Content -->
<div id="sidebar-container"></div>


<div class="main-content">
    <style>
        .contact-wrapper { max-width: 1100px; margin: 2rem auto; background-color: #fff; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); display: grid; grid-template-columns: 1fr; }
        @media (min-width: 1024px) { .contact-wrapper { grid-template-columns: 1fr 1fr; } }
        
        .contact-info { padding: 2.5rem; background-color: #f9fafb; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; }
        .contact-info h1 { font-size: 2rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem; }
        .contact-info p { font-size: 1rem; color: #4b5563; margin-bottom: 2rem; }
        .info-item { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        .info-item .icon { color: #3b82f6; }
        .info-item span { font-size: 0.95rem; color: #374151; }
        #contact-map { height: 250px; width: 100%; border-radius: 0.5rem; margin-top: 2rem; border: 1px solid #e5e7eb; }

        .contact-form { padding: 2.5rem; }
        .contact-form h2 { font-size: 1.75rem; font-weight: 600; color: #111827; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; transition: all 0.2s; }
        .form-group input:focus, .form-group textarea:focus { border-color: #3b82f6; box-shadow: 0 0 0 1px #3b82f6; outline: none; }
        .btn-submit { display: inline-block; background-color: #3b82f6; color: white; padding: 0.75rem 2rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; transition: background-color 0.2s; width: 100%; }
        .btn-submit:hover { background-color: #2563eb; }
        .message { padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.5rem; font-weight: 500; }
        .message.success { background-color: #d1fae5; color: #065f46; }
        .message.error { background-color: #fee2e2; color: #991b1b; }
    </style>

    <div class="contact-wrapper">
        <div class="contact-info">
            <h1>Get in Touch</h1>
            <p>We're here to help and answer any question you might have. We look forward to hearing from you.</p>
            
            <div class="info-item">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/></svg></div>
                <span>#123, Street 456, Phnom Penh, Cambodia</span>
            </div>
            <div class="info-item">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm.239-.03-1.588-1.588L0 11.803V4.697zM16 4.697v7.104l-5.803-3.558L16 4.697z"/></svg></div>
                <span>contact@electronicservice.com</span>
            </div>
            <div class="info-item">
                <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M1.885.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/></svg></div>
                <span>(+855) 12 345 678</span>
            </div>

            <div id="contact-map"></div>
        </div>

        <div class="contact-form">
            <h2>Send us a Message</h2>

            <?php if ($message): ?>
                <div class="message <?= htmlspecialchars($message['type']) ?>">
                    <?= htmlspecialchars($message['text']) ?>
                </div>
            <?php endif; ?>

            <form action="submit_contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        </div>
    </div>
</div>

  <script src="utils/main.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Default location: Phnom Penh
        const lat = 11.5564;
        const lng = 104.9282;

        const map = L.map('contact-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup('Our Office Location')
            .openPopup();
    });
  </script>
</body>
</html>