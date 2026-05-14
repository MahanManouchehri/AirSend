// اضافه کردن استایل برای default-logo و default-bg
let style = document.createElement('style');
style.textContent = `
    .default-logo {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #4a6491, #2c3e50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .default-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 1;
    }
    
    .default-bg i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    .default-bg p {
        font-size: 1.2rem;
        opacity: 0.7;
    }
    
    .login-message {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.5s ease-out;
    }
    
    .login-message.success {
        background: linear-gradient(to right, #e8f6ef, #d5f2e3);
        border: 2px solid #2ecc71;
        color: #27ae60;
    }
    
    .login-message.error {
        background: linear-gradient(to right, #ffeaea, #ffd6d6);
        border: 2px solid #e74c3c;
        color: #c0392b;
    }
    
    .login-message.warning {
        background: linear-gradient(to right, #fef9e7, #fcf3cf);
        border: 2px solid #f39c12;
        color: #d68910;
    }
    
    .features {
        margin-top: 20px;
    }
    
    .features p {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .features i {
        color: #4a6491;
    }
    
    .debug-info {
        margin-top: 30px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px dashed #ddd;
        text-align: center;
        font-size: 0.9rem;
        color: #666;
    }
`;
document.head.appendChild(style);
