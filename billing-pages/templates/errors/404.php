<?php
$localization = new BillingPages\Core\Localization();
$session = new BillingPages\Core\Session();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - <?= $localization->get('page_not_found') ?> | <?= $localization->get('billing_portal') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        .error-icon {
            font-size: 8rem;
            color: #667eea;
            margin-bottom: 2rem;
        }
        
        .error-code {
            font-size: 4rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.5rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .error-description {
            color: #888;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-outline-secondary {
            border: 2px solid #667eea;
            color: #667eea;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
        
        @media (max-width: 768px) {
            .error-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .error-icon {
                font-size: 6rem;
            }
            
            .error-code {
                font-size: 3rem;
            }
            
            .error-message {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        
        <div class="error-code">404</div>
        
        <div class="error-message">
            <?= $localization->get('page_not_found') ?>
        </div>
        
        <div class="error-description">
            <?= $localization->get('page_not_found_description') ?>
        </div>
        
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
            <a href="/" class="btn btn-primary">
                <i class="bi bi-house me-2"></i>
                <?= $localization->get('go_home') ?>
            </a>
            <button onclick="history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                <?= $localization->get('go_back') ?>
            </button>
        </div>
        
        <div class="mt-4">
            <small class="text-muted">
                <?= $localization->get('if_problem_persists') ?> 
                <a href="/contact" class="text-decoration-none"><?= $localization->get('contact_support') ?></a>
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add typing effect to error message
            const errorMessage = document.querySelector('.error-message');
            const text = errorMessage.textContent;
            errorMessage.textContent = '';
            
            let i = 0;
            const typeWriter = () => {
                if (i < text.length) {
                    errorMessage.textContent += text.charAt(i);
                    i++;
                    setTimeout(typeWriter, 100);
                }
            };
            
            setTimeout(typeWriter, 1000);
        });
        
        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            .btn {
                position: relative;
                overflow: hidden;
            }
            
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html> 