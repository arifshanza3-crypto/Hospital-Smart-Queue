/**
 * Services Page - JavaScript
 * Handles fade-in animation for service cards
 */

document.addEventListener('DOMContentLoaded', function() {
    const servicesGrid = document.getElementById('servicesGrid');
    
    if (!servicesGrid) return;

    // Fade-in animation for service cards
    const cards = servicesGrid.querySelectorAll('.service-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 80);
    });

    console.log('✅ Services page loaded - ' + cards.length + ' services rendered');
});