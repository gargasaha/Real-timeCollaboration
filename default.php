<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DevColab | Real-Time Developer Collaboration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: #0a192f;
        }
        header {
            height: 100vh;
            color: white;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }
        .video-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            z-index: -2;
            filter: brightness(0.3) blur(1px);
        }
        .overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: linear-gradient(120deg, rgba(10,25,47,0.8) 60%, rgba(13,110,253,0.3) 100%);
            z-index: -1;
        }
        .header-content {
            z-index: 2;
            padding: 2rem 2rem 3rem 2rem;
            background: rgba(10,25,47,0.7);
            border-radius: 1rem;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
            backdrop-filter: blur(4px);
        }
        .header-content h1 {
            font-size: 4rem;
            font-weight: 700;
            animation: slideDown 1s ease-in-out;
            letter-spacing: 2px;
            background: linear-gradient(90deg, #0d6efd 40%, #00e0ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header-content p {
            font-size: 1.35rem;
            animation: fadeIn 2s ease-in-out;
            color: #e0e0e0;
        }
        .btn-primary {
            background: linear-gradient(90deg, #0d6efd 60%, #00e0ff 100%);
            border: none;
            font-weight: 600;
            letter-spacing: 1px;
            box-shadow: 0 4px 16px rgba(13,110,253,0.2);
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #00e0ff 60%, #0d6efd 100%);
            color: #fff;
        }
        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        section {
            padding: 100px 20px;
        }
        .features i {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 10px;
            transition: color 0.3s;
        }
        .feature-card {
            transition: transform 0.4s, box-shadow 0.4s, background 0.3s;
            background: #112240;
            color: #fff;
            border: none;
        }
        .feature-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 10px 30px rgba(13,110,253,0.15);
            background: #0d6efd;
            color: #fff;
        }
        .feature-card:hover i {
            color: #fff;
        }
        .bg-light {
            background: linear-gradient(90deg, #e3f2fd 60%, #f8fafc 100%);
        }
        footer {
            background-color: #0a192f;
            color: #b0c4de;
            text-align: center;
            padding: 20px;
            letter-spacing: 1px;
        }
        #contact input, #contact textarea {
            background: #f8fafc;
            border: 1px solid #d1e3f8;
            border-radius: 8px;
        }
        #contact input:focus, #contact textarea:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
        }
        @media (max-width: 768px) {
            .header-content h1 { font-size: 2.2rem; }
            section { padding: 60px 10px; }
        }
    </style>
</head>
<body>
    <header>
        <video autoplay muted loop class="video-bg" poster="https://images.unsplash.com/photo-1519389950473-47ba0277781c">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-programming-team-having-a-meeting-5045-large.mp4" type="video/mp4">
            <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c" alt="Programming background">
        </video>
        <div class="overlay"></div>
        <div class="header-content">
            <h1>Welcome to DevColab</h1>
            <p>Real-time collaboration for developers made easy, fast, and fun.</p>
            <a href="#features" class="btn btn-primary mt-3">Explore Features</a>
        </div>
    </header>

    <section id="features" class="text-center">
        <div class="container">
            <h2 class="mb-5" data-aos="fade-up" style="color:#0d6efd;">Awesome Features</h2>
            <div class="row features">
                <div class="col-md-4 mb-4" data-aos="fade-right">
                    <div class="feature-card p-4 shadow-sm rounded">
                        <i class="fas fa-users"></i>
                        <h4>Team Collaboration</h4>
                        <p>Invite team members and collaborate in real-time with live code updates.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up">
                    <div class="feature-card p-4 shadow-sm rounded">
                        <i class="fas fa-code"></i>
                        <h4>Code Synchronization</h4>
                        <p>Instant code synchronization between collaborators across the world.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-left">
                    <div class="feature-card p-4 shadow-sm rounded">
                        <i class="fas fa-terminal"></i>
                        <h4>Built-in Compiler</h4>
                        <p>Compile and test code right from your browser with language support.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light text-center" data-aos="zoom-in">
        <div class="container">
            <h2 class="mb-4" style="color:#0d6efd;">Join the Revolution</h2>
            <p style="color:#222;">DevColab is designed for remote teams, coding bootcamps, and real-time pair programming. Built by developers, for developers.</p>
            <a href="/Real-timeCollaboration/dashboard.php" class="btn btn-success mt-3">Get Started for Free</a>
        </div>
    </section>

    <section id="contact" class="text-center">
        <div class="container" data-aos="fade-up">
            <h2 class="mb-4" style="color:#0d6efd;">Contact Us</h2>
            <p>Have questions or feedback? We'd love to hear from you.</p>
            <form class="row g-3 justify-content-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="col-md-4">
                    <input type="email" class="form-control" placeholder="Your Email" required>
                </div>
                <div class="col-md-8">
                    <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 DevColab. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>
