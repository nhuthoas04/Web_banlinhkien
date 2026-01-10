<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Lỗi hệ thống | TechShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #e63946;
            --dark: #1d3557;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1d3557 0%, #457b9d 100%);
        }
        
        .error-container {
            text-align: center;
            padding: 40px;
            color: white;
        }
        
        .error-code {
            font-size: 150px;
            font-weight: 700;
            line-height: 1;
            text-shadow: 4px 4px 0 rgba(0,0,0,0.2);
            background: linear-gradient(135deg, #fff 0%, #a8dadc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .error-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .error-message {
            font-size: 18px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .error-illustration {
            max-width: 300px;
            margin: 30px auto;
        }
        
        .gear {
            animation: spin 4s linear infinite;
            transform-origin: center;
        }
        
        .gear-reverse {
            animation: spin-reverse 4s linear infinite;
            transform-origin: center;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes spin-reverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        
        .btn-home {
            background: white;
            color: var(--dark);
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            margin: 5px;
        }
        
        .btn-home:hover {
            background: #a8dadc;
            color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }
        
        .btn-refresh {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            margin: 5px;
        }
        
        .btn-refresh:hover {
            background: white;
            color: var(--dark);
            transform: translateY(-2px);
        }
        
        .support-info {
            margin-top: 40px;
            padding: 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .support-info h5 {
            margin-bottom: 15px;
        }
        
        .support-info a {
            color: #a8dadc;
        }
        
        .support-info a:hover {
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <h1 class="error-title">Đã xảy ra lỗi!</h1>
        <p class="error-message">
            Hệ thống đang gặp sự cố. Đội ngũ kỹ thuật của chúng tôi đang khẩn trương khắc phục.
            Xin vui lòng thử lại sau ít phút.
        </p>
        
        <div class="error-illustration">
            <svg viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Big gear -->
                <g class="gear" style="transform-origin: 100px 100px;">
                    <circle cx="100" cy="100" r="50" fill="none" stroke="white" stroke-width="8"/>
                    <circle cx="100" cy="100" r="20" fill="white" opacity="0.5"/>
                    <rect x="95" y="40" width="10" height="20" fill="white"/>
                    <rect x="95" y="140" width="10" height="20" fill="white"/>
                    <rect x="40" y="95" width="20" height="10" fill="white"/>
                    <rect x="140" y="95" width="20" height="10" fill="white"/>
                    <rect x="55" y="55" width="10" height="20" fill="white" transform="rotate(45 60 65)"/>
                    <rect x="135" y="55" width="10" height="20" fill="white" transform="rotate(-45 140 65)"/>
                    <rect x="55" y="125" width="10" height="20" fill="white" transform="rotate(-45 60 135)"/>
                    <rect x="135" y="125" width="10" height="20" fill="white" transform="rotate(45 140 135)"/>
                </g>
                
                <!-- Small gear -->
                <g class="gear-reverse" style="transform-origin: 200px 70px;">
                    <circle cx="200" cy="70" r="30" fill="none" stroke="#a8dadc" stroke-width="6"/>
                    <circle cx="200" cy="70" r="12" fill="#a8dadc" opacity="0.5"/>
                    <rect x="197" y="32" width="6" height="14" fill="#a8dadc"/>
                    <rect x="197" y="94" width="6" height="14" fill="#a8dadc"/>
                    <rect x="162" y="67" width="14" height="6" fill="#a8dadc"/>
                    <rect x="224" y="67" width="14" height="6" fill="#a8dadc"/>
                </g>
                
                <!-- Warning sign -->
                <path d="M250 150 L270 185 L230 185 Z" fill="#e63946"/>
                <text x="250" y="178" text-anchor="middle" fill="white" font-size="20" font-weight="bold">!</text>
            </svg>
        </div>
        
        <div>
            <a href="/" class="btn-home">
                <i class="fas fa-home"></i>
                Về trang chủ
            </a>
            <a href="javascript:location.reload()" class="btn-refresh">
                <i class="fas fa-redo"></i>
                Thử lại
            </a>
        </div>
        
        <div class="support-info">
            <h5><i class="fas fa-headset me-2"></i>Cần hỗ trợ?</h5>
            <p class="mb-2">
                <i class="fas fa-phone me-2"></i>Hotline: <a href="tel:1900xxxx">1900 xxxx</a>
            </p>
            <p class="mb-0">
                <i class="fas fa-envelope me-2"></i>Email: <a href="mailto:support@techshop.vn">support@techshop.vn</a>
            </p>
        </div>
    </div>
</body>
</html>


