<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ada Masalah!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background decorative elements */
        .bg-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(74, 144, 226, 0.1);
        }

        .bg-circle:nth-child(1) {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 5%;
            animation: float 6s ease-in-out infinite;
        }

        .bg-circle:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation: float 8s ease-in-out infinite reverse;
        }

        .bg-triangle {
            position: absolute;
            width: 0;
            height: 0;
            border-left: 30px solid transparent;
            border-right: 30px solid transparent;
            border-bottom: 50px solid rgba(255, 107, 107, 0.1);
            top: 20%;
            right: 20%;
            animation: rotate 10s linear infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Main illustration container */
        .illustration-container {
            position: relative;
            width: 400px;
            height: 300px;
            margin-bottom: 40px;
            z-index: 1;
        }

        /* Main lock - UPDATED POSITIONING */
        .main-lock {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
            width: 120px;
            height: 130px;
        }

        .lock-body {
            width: 120px;
            height: 80px;
            background: linear-gradient(135deg, #ffd93d 0%, #ff9500 100%);
            border-radius: 15px;
            position: absolute;
            bottom: 0;
            left: 0;
            box-shadow: 0 10px 30px rgba(255, 153, 0, 0.3);
            animation: lockPulse 3s ease-in-out infinite;
        }

        .lock-shackle {
            width: 60px;
            height: 50px;
            border: 8px solid #4a90e2;
            border-bottom: none;
            border-radius: 30px 30px 0 0;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            background: transparent;
        }

        .keyhole {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
        }

        .keyhole::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 12px;
            background: white;
            border-radius: 0 0 3px 3px;
        }

        @keyframes lockPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Shield with checkmark */
        .shield {
            position: absolute;
            left: 20%;
            top: 20%;
            width: 60px;
            height: 70px;
            z-index: 2;
            animation: shieldFloat 4s ease-in-out infinite;
        }

        .shield-body {
            width: 100%;
            height: 100%;
            background: white;
            border: 3px solid #ff6b6b;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkmark {
            color: #ff6b6b;
            font-size: 24px;
            font-weight: bold;
        }

        @keyframes shieldFloat {
            0%, 100% { transform: translateY(0px) rotate(-5deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
        }

        /* Warning triangle */
        .warning-triangle {
            position: absolute;
            right: 15%;
            bottom: 25%;
            z-index: 2;
            animation: warningBounce 2s ease-in-out infinite;
        }

        .triangle-body {
            width: 0;
            height: 0;
            border-left: 25px solid transparent;
            border-right: 25px solid transparent;
            border-bottom: 45px solid #ff6b6b;
            position: relative;
        }

        .triangle-inner {
            position: absolute;
            top: 8px;
            left: -20px;
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-bottom: 35px solid white;
        }

        .exclamation {
            position: absolute;
            top: 15px;
            left: -2px;
            color: #ff6b6b;
            font-size: 18px;
            font-weight: bold;
        }

        @keyframes warningBounce {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        /* Decorative leaves */
        .leaf {
            position: absolute;
            z-index: 1;
        }

        .leaf-left {
            left: 10%;
            bottom: 30%;
            animation: leafSway 5s ease-in-out infinite;
        }

        .leaf-right {
            right: 20%;
            top: 30%;
            animation: leafSway 6s ease-in-out infinite reverse;
        }

        .leaf svg {
            width: 40px;
            height: 40px;
            fill: #4a90e2;
            opacity: 0.7;
        }

        @keyframes leafSway {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(10deg); }
        }

        /* Dashed circles */
        .dashed-circle {
            position: absolute;
            border: 2px dashed #4a90e2;
            border-radius: 50%;
            opacity: 0.3;
            animation: rotateDashed 15s linear infinite;
        }

        .dashed-circle-1 {
            width: 200px;
            height: 200px;
            top: 10%;
            right: 10%;
        }

        .dashed-circle-2 {
            width: 150px;
            height: 150px;
            bottom: 20%;
            left: 15%;
            animation-direction: reverse;
        }

        @keyframes rotateDashed {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Small decorative elements */
        .small-shapes {
            position: absolute;
            z-index: 1;
        }

        .small-circle {
            width: 8px;
            height: 8px;
            background: #4a90e2;
            border-radius: 50%;
            position: absolute;
        }

        .small-triangle {
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 8px solid #ff6b6b;
            position: absolute;
        }

        .small-cross {
            position: absolute;
            color: #ffd93d;
            font-size: 12px;
            font-weight: bold;
        }

        /* Text styling */
        .title {
            font-size: 48px;
            font-weight: 700;
            color: #4a90e2;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: -1px;
            z-index: 1;
            position: relative;
        }

        .message {
            font-size: 18px;
            color: #666;
            text-align: center;
            line-height: 1.6;
            margin-bottom: 15px;
            max-width: 500px;
            z-index: 1;
            position: relative;
        }

        .contact-info {
            font-size: 18px;
            color: #ff6b6b;
            text-align: center;
            font-weight: 600;
            z-index: 1;
            position: relative;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .illustration-container {
                width: 300px;
                height: 250px;
            }
            
            .title {
                font-size: 36px;
            }
            
            .message, .contact-info {
                font-size: 16px;
            }
            
            .main-lock {
                width: 100px;
                height: 110px;
            }
            
            .lock-body {
                width: 100px;
                height: 70px;
            }
            
            .lock-shackle {
                width: 50px;
                height: 40px;
                border-width: 6px;
            }
        }
    </style>
</head>
<body>
    <!-- Background decorative shapes -->
    <div class="bg-shapes">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-triangle"></div>
    </div>

    <!-- Main illustration -->
    <div class="illustration-container">
        <!-- Dashed circles -->
        <div class="dashed-circle dashed-circle-1"></div>
        <div class="dashed-circle dashed-circle-2"></div>

        <!-- Main lock - UPDATED STRUCTURE -->
        <div class="main-lock">
            <div class="lock-shackle"></div>
            <div class="lock-body">
                <div class="keyhole"></div>
            </div>
        </div>

        <!-- Shield with checkmark -->
        <div class="shield">
            <div class="shield-body">
                <div class="checkmark">✓</div>
            </div>
        </div>

        <!-- Warning triangle -->
        <div class="warning-triangle">
            <div class="triangle-body">
                <div class="triangle-inner"></div>
                <div class="exclamation">!</div>
            </div>
        </div>

        <!-- Decorative leaves -->
        <div class="leaf leaf-left">
            <svg viewBox="0 0 24 24">
                <path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/>
            </svg>
        </div>
        <div class="leaf leaf-right">
            <svg viewBox="0 0 24 24">
                <path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z"/>
            </svg>
        </div>

        <!-- Small decorative elements -->
        <div class="small-shapes">
            <div class="small-circle" style="top: 15%; left: 60%;"></div>
            <div class="small-circle" style="bottom: 40%; right: 25%;"></div>
            <div class="small-triangle" style="top: 25%; right: 35%;"></div>
            <div class="small-triangle" style="bottom: 15%; left: 25%;"></div>
            <div class="small-cross" style="top: 35%; left: 15%;">✕</div>
            <div class="small-cross" style="bottom: 35%; right: 15%;">✕</div>
        </div>
    </div>

    <!-- Text content -->
    <h1 class="title">Ada Masalah Nih</h1>
    <p class="message">
        Anda tidak bisa melanjutkan ke halaman ini.
    </p>
    <p class="contact-info">
        Silakan hubungi administrator anda untuk bantuan.
    </p>
</body>
</html>