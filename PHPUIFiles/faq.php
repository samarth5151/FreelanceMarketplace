<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - Freelance Marketplace</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #262b40;
            --accent-green: rgb(32 159 75);
            --text-primary: rgba(0, 0, 0, 0.644);
            --text-hover: #000000;
            --bg-light: whitesmoke;
            --bg-white: #ffffff;
            --gray-icon: #c9d1d9;
            --button-hover: rgb(81, 81, 81);
            --card-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        /* Navigation */
        nav {
            background-color: var(--bg-white);
            border-radius: 12px;
            padding: 20px 40px;
            margin: 20px auto;
            max-width: 1400px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--card-shadow);
        }
        
        .logo img {
            height: 50px;
        }
        
        /* FAQ Hero Section */
        .faq-hero {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #3a4166 100%);
            padding: 100px 5%;
            text-align: center;
            color: white;
            margin-bottom: 60px;
        }
        
        .faq-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .faq-hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }
        
        /* FAQ Container */
        .faq-container {
            max-width: 1000px;
            margin: 0 auto 80px;
            padding: 0 5%;
        }
        
        /* FAQ Categories */
        .faq-categories {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .category-btn {
            background: var(--bg-white);
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow);
        }
        
        .category-btn.active, .category-btn:hover {
            background: var(--accent-green);
            color: white;
        }
        
        /* FAQ Accordion */
        .faq-accordion {
            background: var(--bg-white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }
        
        .faq-item {
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .faq-item:last-child {
            border-bottom: none;
        }
        
        .faq-question {
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .faq-question:hover {
            background: rgba(0,0,0,0.02);
        }
        
        .faq-question h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .faq-question i {
            color: var(--accent-green);
            transition: transform 0.3s ease;
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding: 0 30px;
        }
        
        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 0 30px 25px;
        }
        
        .faq-answer p {
            margin-bottom: 15px;
        }
        
        /* CTA Section */
        .faq-cta {
            text-align: center;
            padding: 60px 5%;
            background: var(--bg-white);
            margin-top: 60px;
        }
        
        .faq-cta h2 {
            font-size: 2rem;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }
        
        .faq-cta p {
            max-width: 600px;
            margin: 0 auto 30px;
        }
        
        .cta-btn {
            background: var(--accent-green);
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(32, 159, 75, 0.3);
        }
        
        .cta-btn:hover {
            background: #228a4f;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(32, 159, 75, 0.3);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .faq-hero h1 {
                font-size: 2.2rem;
            }
            
            .faq-question {
                padding: 20px;
            }
            
            .faq-question h3 {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <img src="../Assets/logo2.png" alt="Freelance Marketplace Logo">
        </div>
        <div class="nav-btns">
            <a href="login.php" class="btn-login">Login</a>
            <a href="register.php" class="btn-register">Register</a>
        </div>
    </nav>
    
    <!-- FAQ Hero Section -->
    <section class="faq-hero">
        <h1>Frequently Asked Questions</h1>
        <p>Find quick answers to common questions about our freelance platform</p>
    </section>
    
    <!-- FAQ Container -->
    <div class="faq-container">
        <!-- FAQ Categories -->
        <div class="faq-categories">
            <button class="category-btn active" data-category="all">All Questions</button>
            <button class="category-btn" data-category="freelancers">For Freelancers</button>
            <button class="category-btn" data-category="clients">For Clients</button>
            <button class="category-btn" data-category="payments">Payments</button>
            <button class="category-btn" data-category="account">Account</button>
        </div>
        
        <!-- FAQ Accordion -->
        <div class="faq-accordion">
            <!-- General Questions -->
            <div class="faq-item active" data-category="all">
                <div class="faq-question">
                    <h3>What is Freelance Marketplace?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Freelance Marketplace is a platform that connects skilled professionals with businesses and individuals looking to hire talent for their projects. We make the hiring process simple, efficient, and rewarding for both clients and freelancers.</p>
                </div>
            </div>
            
            <!-- For Freelancers -->
            <div class="faq-item" data-category="freelancers all">
                <div class="faq-question">
                    <h3>How do I create a freelancer profile?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>To create a freelancer profile:</p>
                    <ol>
                        <li>Sign up for a free account</li>
                        <li>Select "I want to work" during registration</li>
                        <li>Complete your profile with skills, experience, and portfolio</li>
                        <li>Verify your identity (required for payment processing)</li>
                        <li>Start bidding on projects or create service packages</li>
                    </ol>
                </div>
            </div>
            
            <!-- For Clients -->
            <div class="faq-item" data-category="clients all">
                <div class="faq-question">
                    <h3>How do I hire a freelancer?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Hiring a freelancer is simple:</p>
                    <ol>
                        <li>Post your project with clear requirements</li>
                        <li>Review freelancer proposals and profiles</li>
                        <li>Interview candidates (optional)</li>
                        <li>Select the best match and agree on terms</li>
                        <li>Fund the project escrow to get started</li>
                    </ol>
                </div>
            </div>
            
            <!-- Payments -->
            <div class="faq-item" data-category="payments all">
                <div class="faq-question">
                    <h3>How does the payment system work?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>We use a secure escrow payment system:</p>
                    <ul>
                        <li><strong>For clients:</strong> Funds are held in escrow until you approve the work</li>
                        <li><strong>For freelancers:</strong> You're guaranteed payment for approved work</li>
                        <li><strong>Payment methods:</strong> Credit cards, PayPal, bank transfers, and more</li>
                        <li><strong>Fees:</strong> We charge a small service fee (displayed before payment)</li>
                    </ul>
                </div>
            </div>
            
            <!-- Account -->
            <div class="faq-item" data-category="account all">
                <div class="faq-question">
                    <h3>Can I upgrade or downgrade my account?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, you can change your account plan at any time:</p>
                    <ul>
                        <li>Freelancers can upgrade to "Pro" for more visibility</li>
                        <li>Clients can upgrade for additional project management features</li>
                        <li>Changes take effect immediately</li>
                        <li>Downgrades are processed at the end of your billing cycle</li>
                    </ul>
                    <p>Visit your account settings to manage your subscription.</p>
                </div>
            </div>
            
            <!-- General -->
            <div class="faq-item" data-category="all">
                <div class="faq-question">
                    <h3>Is there a mobile app available?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes! Our mobile app is available for both iOS and Android devices. You can:</p>
                    <ul>
                        <li>Manage projects on the go</li>
                        <li>Communicate with clients/freelancers</li>
                        <li>Receive notifications for new messages and project updates</li>
                        <li>Submit and review work</li>
                    </ul>
                    <p>Download it from the App Store or Google Play Store.</p>
                </div>
            </div>
            
            <!-- For Freelancers -->
            <div class="faq-item" data-category="freelancers all">
                <div class="faq-question">
                    <h3>How do I get paid for my work?</h3>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Getting paid is simple:</p>
                    <ol>
                        <li>Complete the work as agreed with your client</li>
                        <li>Submit your work through our platform</li>
                        <li>The client reviews and approves the work</li>
                        <li>Funds are released from escrow to your account</li>
                        <li>Withdraw to your preferred payment method (minimum $20 balance)</li>
                    </ol>
                    <p>Standard processing time is 2-5 business days.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <section class="faq-cta">
        <h2>Still have questions?</h2>
        <p>Our support team is available 24/7 to help you with any questions or issues.</p>
        <button class="cta-btn">Contact Support</button>
    </section>
    
    <script>
        // FAQ Accordion Functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.parentElement;
                item.classList.toggle('active');
                
                // Close other open items
                document.querySelectorAll('.faq-item').forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                    }
                });
            });
        });
        
        // FAQ Category Filter
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active button
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                const category = btn.dataset.category;
                const allItems = document.querySelectorAll('.faq-item');
                
                if (category === 'all') {
                    allItems.forEach(item => item.style.display = 'block');
                } else {
                    allItems.forEach(item => {
                        if (item.dataset.category.includes(category)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>