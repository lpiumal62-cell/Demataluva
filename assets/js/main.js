// Bright Future Academy - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });

    // Apply global animations across the site
    applyGlobalAnimations();

    // Initialize Swiper sliders
    initializeSwipers();
    
    // Initialize theme toggle
    initializeThemeToggle();
    
    // Initialize counter animations
    initializeCounters();
    
    // Initialize gallery
    initializeGallery();
    
    // Initialize contact form
    initializeContactForm();
    
    // Initialize mobile menu
    initializeMobileMenu();
    
    // Initialize smooth scrolling
    initializeSmoothScrolling();
});

// Initialize Swiper sliders
function initializeSwipers() {
    // Hero slider
    if (document.querySelector('.hero-swiper')) {
        new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.hero-swiper .swiper-pagination',
                clickable: true,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            }
        });
    }

    // Testimonials slider
    if (document.querySelector('.testimonials-swiper')) {
        new Swiper('.testimonials-swiper', {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.testimonials-swiper .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                }
            }
        });
    }
}

// Apply AOS attributes broadly without editing every template
function applyGlobalAnimations() {
    // Helper to assign AOS with optional staggered delays
    const applyAOS = (nodes, effect = 'fade-up', baseDelay = 0, step = 75, startIndex = 0) => {
        nodes.forEach((el, i) => {
            if (!el.hasAttribute('data-aos')) {
                el.setAttribute('data-aos', effect);
                const delay = baseDelay + (startIndex + i) * step;
                el.setAttribute('data-aos-delay', String(delay));
            }
        });
    };

    // Page-level sections
    applyAOS(Array.from(document.querySelectorAll('section')), 'fade-up', 0, 100);

    // Navigation and footer
    const nav = document.querySelector('nav');
    if (nav && !nav.hasAttribute('data-aos')) nav.setAttribute('data-aos', 'fade-down');
    const footer = document.querySelector('footer');
    if (footer && !footer.hasAttribute('data-aos')) footer.setAttribute('data-aos', 'fade-up');

    // Common cards/containers
    applyAOS(Array.from(document.querySelectorAll('.card-hover')), 'zoom-in', 50, 100);
    applyAOS(Array.from(document.querySelectorAll('.bg-gray-800, .bg-gray-700')), 'fade-up', 100, 80);

    // Grids and children for staggered entrances
    document.querySelectorAll('.grid').forEach(grid => {
        const children = Array.from(grid.children);
        applyAOS(children, 'fade-up', 0, 80);
    });

    // Gallery items
    applyAOS(Array.from(document.querySelectorAll('.gallery-item')), 'zoom-in', 0, 80);

    // Counters (ensure they animate when visible)
    applyAOS(Array.from(document.querySelectorAll('.counter')), 'fade-up', 0, 50);

    // Refresh AOS after dynamic attribute assignment
    if (typeof AOS !== 'undefined' && AOS.refreshHard) {
        AOS.refreshHard();
    } else if (typeof AOS !== 'undefined' && AOS.refresh) {
        AOS.refresh();
    }
}

// Theme toggle functionality
function initializeThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;
    
    // Check for saved theme preference or default to dark
    const currentTheme = localStorage.getItem('theme') || 'dark';
    body.classList.add(currentTheme);
    
    // Update toggle icon
    updateThemeIcon(currentTheme);
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const isDark = body.classList.contains('dark');
            const newTheme = isDark ? 'light' : 'dark';
            
            body.classList.remove('dark', 'light');
            body.classList.add(newTheme);
            
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }
}

// Update theme toggle icon
function updateThemeIcon(theme) {
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        if (icon) {
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    }
}

// Counter animation
function initializeCounters() {
    const counters = document.querySelectorAll('.counter');
    
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    };
    
    // Intersection Observer for counter animation
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
}

// Gallery functionality
function initializeGallery() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = createModal();
    
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const img = this.querySelector('img');
            const title = this.querySelector('.gallery-title')?.textContent || '';
            const description = this.querySelector('.gallery-description')?.textContent || '';
            
            showModal(modal, img.src, title, description);
        });
    });
}

// Create modal for gallery
function createModal() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content p-6">
            <button class="modal-close absolute top-4 right-4 text-white text-2xl hover:text-blue-400">&times;</button>
            <img class="modal-image w-full h-auto rounded-lg" src="" alt="">
            <div class="modal-info mt-4">
                <h3 class="modal-title text-xl font-semibold text-white"></h3>
                <p class="modal-description text-gray-400 mt-2"></p>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Close modal functionality
    modal.addEventListener('click', function(e) {
        if (e.target === modal || e.target.classList.contains('modal-close')) {
            hideModal(modal);
        }
    });
    
    return modal;
}

// Show modal
function showModal(modal, src, title, description) {
    const img = modal.querySelector('.modal-image');
    const titleEl = modal.querySelector('.modal-title');
    const descEl = modal.querySelector('.modal-description');
    
    img.src = src;
    titleEl.textContent = title;
    descEl.textContent = description;
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Hide modal
function hideModal(modal) {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Contact form functionality
function initializeContactForm() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Show loading state
            submitBtn.innerHTML = '<span class="loading"></span> Sending...';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual AJAX call)
            setTimeout(() => {
                showNotification('Message sent successfully!', 'success');
                this.reset();
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }
}

// Newsletter subscription
function initializeNewsletter() {
    const newsletterForm = document.getElementById('newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            
            if (validateEmail(email)) {
                submitBtn.innerHTML = '<span class="loading"></span>';
                submitBtn.disabled = true;
                
                // Simulate subscription (replace with actual AJAX call)
                setTimeout(() => {
                    showNotification('Successfully subscribed to newsletter!', 'success');
                    this.reset();
                    submitBtn.innerHTML = 'Subscribe';
                    submitBtn.disabled = false;
                }, 1500);
            } else {
                showNotification('Please enter a valid email address', 'error');
            }
        });
    }
}

// Mobile menu functionality
function initializeMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }
}

// Smooth scrolling for anchor links
function initializeSmoothScrolling() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80; // Account for fixed header
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Utility functions
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 transform translate-x-full transition-transform duration-300 ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-blue-600'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Search functionality for results page
function initializeSearch() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    
    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const items = searchResults.querySelectorAll('.search-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
}

// Filter functionality for gallery
function initializeGalleryFilter() {
    const filterButtons = document.querySelectorAll('.gallery-filter');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter items
            galleryItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    item.classList.add('fade-in');
                } else {
                    item.style.display = 'none';
                    item.classList.remove('fade-in');
                }
            });
        });
    });
}

// Export functions for external use
window.BrightFutureAcademy = {
    showNotification,
    validateEmail,
    initializeSearch,
    initializeGalleryFilter
};
