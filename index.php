<?php
session_start();
require_once 'functions.php';

// Handle contact form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    if (verifyCSRFToken($_POST['csrf_token'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message_text = trim($_POST['message']);
        
        if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
            $message = 'Please fill in all fields.';
        } elseif (!validateEmail($email)) {
            $message = 'Please enter a valid email address.';
        } else {
            if (submitContactForm($name, $email, $subject, $message_text)) {
                $message = 'Thank you for your message! I will get back to you soon.';
            } else {
                $message = 'Sorry, there was an error sending your message. Please try again.';
            }
        }
    } else {
        $message = 'Security token invalid. Please try again.';
    }
}

// Get data from database
$personal = getPersonalInfo();
$skills = getSkillsByCategory();
$education = getEducation();
$experience = getExperience();
$projects = getProjects();
$applications = getApplicationsByCategory();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput($personal['name']) ?> - Portfolio</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="#home">Portfolio</a>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#home" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#about" class="nav-link">About</a>
                </li>
                <li class="nav-item">
                    <a href="#skills" class="nav-link">Skills</a>
                </li>
                <li class="nav-item">
                    <a href="#education" class="nav-link">Education</a>
                </li>
                <li class="nav-item">
                    <a href="#experience" class="nav-link">Experience</a>
                </li>
                <li class="nav-item">
                    <a href="#applications" class="nav-link">Applications</a>
                </li>
                <li class="nav-item">
                    <a href="#projects" class="nav-link">Projects</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">Contact</a>
                </li>
            </ul>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title"><?= sanitizeOutput($personal['JED']) ?></h1>
                <p class="hero-subtitle"><?= sanitizeOutput($personal['title']) ?></p>
                <p class="hero-description"><?= sanitizeOutput($personal['description']) ?></p>
                <div class="hero-buttons">
                    <a href="#projects" class="btn btn-primary">View My Work</a>
                    <a href="#contact" class="btn btn-secondary">Get In Touch</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="profile-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <h2 class="section-title">About Me</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>Hello! I'm a passionate web developer with a strong foundation in modern web technologies. I love creating user-friendly, responsive websites that solve real-world problems.</p>
                    <p>With several years of experience in web development, I've worked on various projects ranging from simple landing pages to complex web applications. I'm always eager to learn new technologies and stay updated with the latest industry trends.</p>
                    <p>When I'm not coding, you can find me exploring new technologies, contributing to open-source projects, or sharing knowledge with the developer community.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="skills">
        <div class="container">
            <h2 class="section-title">Skills</h2>
            <div class="skills-grid">
                <?php foreach ($skills as $category => $categorySkills): ?>
                <div class="skill-category">
                    <h3><?= sanitizeOutput($category) ?></h3>
                    <div class="skill-items">
                        <?php foreach ($categorySkills as $skill): ?>
                        <div class="skill-item">
                            <span class="skill-name"><?= sanitizeOutput($skill['name']) ?></span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: <?= $skill['proficiency'] ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Education Section -->
    <section id="education" class="education">
        <div class="container">
            <h2 class="section-title">Educational Attainment</h2>
            <div class="timeline">
                <?php foreach ($education as $index => $edu): ?>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3><?= sanitizeOutput($edu['degree']) ?></h3>
                        <p class="institution"><?= sanitizeOutput($edu['institution']) ?></p>
                        <p class="year"><?= formatYearRange($edu['start_year'], $edu['end_year']) ?></p>
                        <p class="description"><?= sanitizeOutput($edu['description']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="experience">
        <div class="container">
            <h2 class="section-title">Technical and Work Experience</h2>
            <div class="experience-grid">
                <?php foreach ($experience as $exp): ?>
                <div class="experience-card">
                    <div class="experience-header">
                        <h3><?= sanitizeOutput($exp['title']) ?></h3>
                        <p class="company"><?= sanitizeOutput($exp['company']) ?></p>
                        <p class="duration"><?= formatDate($exp['start_date']) ?> - <?= $exp['is_current'] ? 'Present' : formatDate($exp['end_date']) ?></p>
                    </div>
                    <ul class="experience-details">
                        <?php 
                        $details = explode('.', $exp['description']);
                        foreach ($details as $detail):
                            $detail = trim($detail);
                            if (!empty($detail)):
                        ?>
                        <li><?= sanitizeOutput($detail) ?></li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Applications Section -->
    <section id="applications" class="applications">
        <div class="container">
            <h2 class="section-title">Applications and Environments</h2>
            <div class="applications-grid">
                <?php foreach ($applications as $category => $categoryApps): ?>
                <div class="application-category">
                    <h3><?= sanitizeOutput($category) ?></h3>
                    <div class="application-items">
                        <?php foreach ($categoryApps as $app): ?>
                        <div class="application-item">
                            <i class="<?= sanitizeOutput($app['icon_class']) ?>"></i>
                            <span><?= sanitizeOutput($app['name']) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="projects">
        <div class="container">
            <h2 class="section-title">Projects</h2>
            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <div class="project-image">
                        <div class="project-placeholder">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3><?= sanitizeOutput($project['title']) ?></h3>
                        <p><?= sanitizeOutput($project['description']) ?></p>
                        <div class="project-tech">
                            <?php 
                            $techs = getProjectTechnologies($project['technologies']);
                            foreach ($techs as $tech):
                            ?>
                            <span class="tech-tag"><?= sanitizeOutput(trim($tech)) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="project-links">
                            <?php if (!empty($project['github_url'])): ?>
                            <a href="<?= sanitizeOutput($project['github_url']) ?>" class="project-link" target="_blank"><i class="fab fa-github"></i> Code</a>
                            <?php endif; ?>
                            <?php if (!empty($project['live_url'])): ?>
                            <a href="<?= sanitizeOutput($project['live_url']) ?>" class="project-link" target="_blank"><i class="fas fa-external-link-alt"></i> Live Demo</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2 class="section-title">Contact Information</h2>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <p><?= sanitizeOutput($personal['email']) ?></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h3>Phone</h3>
                            <p><?= sanitizeOutput($personal['phone']) ?></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Location</h3>
                            <p><?= sanitizeOutput($personal['location']) ?></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fab fa-linkedin"></i>
                        <div>
                            <h3>LinkedIn</h3>
                            <p><?= sanitizeOutput($personal['linkedin']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <h3>Send me a message</h3>
                    <?php if (!empty($message)): ?>
                    <div class="message <?= strpos($message, 'Thank you') !== false ? 'success' : 'error' ?>">
                        <?= sanitizeOutput($message) ?>
                    </div>
                    <?php endif; ?>
                    <form method="POST" action="#contact">
                        <input type="hidden" name="contact_form" value="1">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your Name" required value="<?= isset($_POST['name']) ? sanitizeOutput($_POST['name']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your Email" required value="<?= isset($_POST['email']) ? sanitizeOutput($_POST['email']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subject" required value="<?= isset($_POST['subject']) ? sanitizeOutput($_POST['subject']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Your Message" rows="5" required><?= isset($_POST['message']) ? sanitizeOutput($_POST['message']) : '' ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; <?= date('Y') ?> <?= sanitizeOutput($personal['name']) ?>. All rights reserved.</p>
                <div class="social-links">
                    <?php if (!empty($personal['github'])): ?>
                    <a href="https://<?= sanitizeOutput($personal['github']) ?>" target="_blank"><i class="fab fa-github"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($personal['linkedin'])): ?>
                    <a href="https://<?= sanitizeOutput($personal['linkedin']) ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($personal['twitter'])): ?>
                    <a href="https://<?= sanitizeOutput($personal['twitter']) ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($personal['instagram'])): ?>
                    <a href="https://<?= sanitizeOutput($personal['instagram']) ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
