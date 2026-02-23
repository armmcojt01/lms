<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS · company logo welcome</title>
    <!-- Font Awesome 6 (free) for subtle icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-image: url('../uploads/images/armmc-bg.png');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        
         .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 35, 102, 0.4); /* Dark royal */
        z-index: -1;
    }
    
        .content {
        position: relative;
        padding: 50px;
        color: white;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        z-index: 1;
    }

        /* main welcome card */
        .welcome-card {
            max-width: 1280px;
            width: 100%;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 3.5rem;
            box-shadow: 
                0 30px 60px -20px rgba(0,40,80,0.25),
                0 8px 20px -8px rgba(0,32,64,0.1),
                inset 0 1px 1px rgba(255,255,255,0.6);
            border: 1px solid rgba(255,255,255,0.6);
            padding: 3rem 2.5rem;
        }

        /* two-column layout */
        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
            align-items: center;
        }

        /* left side – company logo as MAIN SUBJECT (PNG placeholder) */
        .logo-hero {
            background: rgba(255,255,255,0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 2.5rem;
            padding: 3rem 2rem;
            box-shadow: 0 20px 30px -10px rgba(0,20,40,0.15);
            border: 1px solid rgba(255,255,255,0.8);
            transition: transform 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-hero:hover {
            transform: scale(1.01);
            background: rgba(255,255,255,0.65);
        }

        /* logo container: the main subject */
        .logo-main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        /* PNG placeholder styled as a prominent logo */
        .company-logo-png {
            max-width: 340px;
            width: 100%;
            height: auto;
            aspect-ratio: 1 / 1; /* keep square proportion for placeholder */
            object-fit: contain;
            filter: drop-shadow(0 12px 18px rgba(0,50,90,0.25));
            background: transparent;
            /* subtle border to suggest image boundaries, but keep clean */
            border-radius: 32px;
            transition: filter 0.2s;
        }

        /* caption underlines the main subject */
        .logo-caption {
            margin-top: 2rem;
            font-weight: 400;
            font-size: 1.1rem;
            letter-spacing: 2px;
            color: #1c3f5c;
            opacity: 0.8;
            text-transform: uppercase;
            border-bottom: 2px solid #a3c6e9;
            padding-bottom: 0.75rem;
            display: inline-block;
        }

        /* right side – welcome message & LMS context */
        .welcome-content {
            padding: 1rem 0.5rem;
        }

        .lms-badge {
            display: inline-block;
            background: #1d4e75;
            color: white;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.4rem 1.2rem;
            border-radius: 40px;
            letter-spacing: 0.3px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 10px rgba(0,60,110,0.2);
        }

        .welcome-title {
            font-size: clamp(2.2rem, 5vw, 3.4rem);
            font-weight: 700;
            line-height: 1.2;
            color: #0c2e45;
            margin-bottom: 1.2rem;
        }

        .welcome-title span {
            background: linear-gradient(135deg, #1f6392, #0a3b58);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            border-bottom: 4px solid #6ab0f5;
            display: inline-block;
            padding-bottom: 2px;
        }

        .welcome-description {
            font-size: 1.2rem;
            color: #2b4e6b;
            margin-bottom: 2.5rem;
            line-height: 1.6;
            font-weight: 400;
            max-width: 500px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
            margin: 2.5rem 0 2rem;
        }

        .feature-item {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(2px);
            border-radius: 2rem;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 500;
            color: #144a6f;
            border: 1px solid rgba(255,255,255,0.9);
            box-shadow: 0 6px 14px rgba(0,30,60,0.05);
            transition: all 0.2s;
        }

        .feature-item i {
            font-size: 1.7rem;
            color: #1f6fb0;
            width: 2rem;
            text-align: center;
        }

        .feature-item:hover {
            background: white;
            border-color: #b5d8ff;
            box-shadow: 0 12px 18px -10px #1f6fb030;
        }

        .cta-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2.2rem;
        }

        .btn-primary {
            background: #1f6fb0;
            border: none;
            padding: 0.9rem 2.4rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            transition: 0.15s;
            box-shadow: 0 12px 18px -10px #1f6fb0;
            border: 1px solid rgba(255,255,255,0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }

        .btn-primary i {
            font-size: 1.2rem;
        }

        .btn-primary:hover {
            background: #0f558b;
            transform: translateY(-3px);
            box-shadow: 0 20px 22px -12px #1f6fb0;
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid #1f6fb0;
            padding: 0.9rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #1f6fb0;
            cursor: pointer;
            transition: 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }

        .btn-outline i {
            font-size: 1.2rem;
            color: #1f6fb0;
        }

        .btn-outline:hover {
            background: #e7f0ff;
            border-color: #0f4a78;
            color: #0f4a78;
        }

        /* decorative elements */
        .bottom-note {
            margin-top: 3rem;
            font-size: 0.9rem;
            color: #567e9f;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .bottom-note .line {
            height: 1px;
            background: linear-gradient(90deg, transparent, #9bbcdd, transparent);
            flex: 1;
        }

        /* responsiveness */
        @media (max-width: 880px) {
            .grid-layout {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .logo-hero {
                order: 1;
            }
            .welcome-content {
                order: 2;
                text-align: center;
            }
            .welcome-description {
                margin-left: auto;
                margin-right: auto;
            }
            .feature-grid {
                max-width: 500px;
                margin-left: auto;
                margin-right: auto;
            }
            .cta-group {
                justify-content: center;
            }
            .bottom-note {
                justify-content: center;
            }
        }

        @media (max-width: 500px) {
            .welcome-card {
                padding: 1.8rem 1.2rem;
            }
            .feature-grid {
                grid-template-columns: 1fr;
            }
            .logo-caption {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="welcome-card">
        <div class="grid-layout">
            <!-- LEFT SIDE: COMPANY LOGO AS MAIN SUBJECT – now PNG placeholder -->
            <div class="logo-hero">
                <div class="logo-main">
                    <img 
                        class="company-logo-png" 
                        src="../uploads/images/armmc-logo.png" 
                        alt="Company logo – main visual"
                        title="Your company logo"
                    >
                    <!-- company name below logo – reinforces main subject -->
                    <div class="logo-caption">
                        <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle; color: #1f6fb0;"></i> 
                        AMANG RODRIGUEZ MEMORIAL MEDICAL CENTER 
                        <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle; color: #1f6fb0;"></i>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Welcome message and LMS info -->
            <div class="welcome-content">
                <h1 class="welcome-title">
                    welcome to <span>ARMMC LMS</span>
                </h1>
                <p class="welcome-description">
                    Transform your learning experience with our comprehensive Learning Management System. 
               Access courses, track progress, and connect with educators in one seamless platform.
                </p>

                <!-- micro features (relevant to LMS) -->
                <div class="feature-grid">
                    <div class="feature-item">
                        <i class="fas fa-video"></i> <span>Interactive courses</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-chart-line"></i> <span>Progress tracking</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-users"></i> <span>Collaborative</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-certificate"></i> <span>Certification</span>
                    </div>
                </div>

                <!-- call to actions -->
                <div class="cta-group" style="margin-top: 0.5rem; margin-left: 190px;">
                    <button class="btn-primary">
                        <a href="../public/login.php" class="auth-btn login-btn" style="color: white; text-decoration: none;">
                            <i class="fas fa-rocket"></i> Get Started
                        </a>
                    </button>
                </div>

                <!-- subtle bottom note / additional trust text -->
                <div class="bottom-note">
                    <span class="line"></span>
                    <span>ARMMC Learning Management System. All rights reserved 2026.</span><span class="line"></span>
                </div>
                <div class="bottom-note" style="margin-top: 0.5rem; margin-left: 250px;">
                    <span>iMISS</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>