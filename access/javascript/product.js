// Product data array matching your existing products
const products = [
    {
        id: 1,
        image: "../uploads/products/p1.jpg",
        title: "Jbi-202 Two Wheeler Rear Indicator Light",
        price: "₹218 - ₹220 / Set",
        moq: "100 Pair",
        material: "ABS, Glass",
        type: "Indicator Light",
        description: "High-quality rear indicator light for two-wheelers with durable ABS construction and clear glass lens. Perfect for various bike models with easy installation and weather-resistant design.",
        features: [
            "Weather resistant construction",
            "Easy plug-and-play installation",
            "Long-lasting LED bulbs",
            "Clear visibility from all angles",
            "Vibration resistant"
        ],
        specs: {
            "Material": "ABS Plastic + Glass",
            "Voltage": "12V DC",
            "Wattage": "10W",
            "Color Available": "Clear/Red",
            "Fit Type": "Universal",
            "Certification": "ISI Approved"
        },
        category: "Lighting",
        rating: 4.5,
        reviews: 128
    },
    {
        id: 2,
        image: "../uploads/products/p2.jpg",
        title: "Plastic JPM Platina Head Light",
        price: "₹200 - ₹500 / Piece",
        moq: "50 Piece",
        material: "Plastic, Glass",
        type: "Head Light",
        description: "Original style replacement headlight for Bajaj Platina with durable polycarbonate housing and anti-fog glass. Provides excellent illumination for night riding.",
        features: [
            "OEM quality replacement",
            "Anti-fog glass lens",
            "Easy mounting system",
            "High-low beam function",
            "Dust and water resistant"
        ],
        specs: {
            "Material": "Plastic + Glass",
            "Voltage": "12V",
            "Bulb Type": "H4 Halogen",
            "Compatibility": "Bajaj Platina",
            "Beam Type": "Dual Beam",
            "Warranty": "6 Months"
        },
        category: "Lighting",
        rating: 4.3,
        reviews: 95
    },
    {
        id: 3,
        image: "../uploads/products/p3.jpg",
        title: "Bike LED Fog Light (Yellow)",
        price: "₹350 / piece",
        moq: "10 Piece",
        material: "Aluminum, Glass",
        type: "Bracket Mount",
        description: "Powerful LED fog lights with 3000K yellow tint specifically designed for better visibility in foggy and rainy conditions. Comes with adjustable mounting bracket.",
        features: [
            "3000K Yellow light for fog penetration",
            "IP67 Waterproof rating",
            "Aluminum heat sink",
            "Adjustable mounting bracket",
            "Plug-and-play wiring"
        ],
        specs: {
            "Power": "18W per pair",
            "Light Source": "LED Chip",
            "Color Temperature": "3000K Yellow",
            "Waterproof": "IP67 Rated",
            "Mounting": "Universal Bracket",
            "Voltage": "12V-24V DC"
        },
        category: "Lighting",
        rating: 4.7,
        reviews: 156
    },
    // Add remaining products following the same structure...
];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
    setupModal();
    setupFiltering();
});

// Function to load products
function loadProducts() {
    const container = document.getElementById('productContainer');
    container.innerHTML = '';
    
    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.title}" class="product-img" 
                 onerror="this.src='../uploads/products/default.jpg'">
            <h3>${product.title}</h3>
            <p class="price">${product.price}</p>
            <p class="info"><span>MOQ:</span> ${product.moq}</p>
            <p class="info"><span>Material:</span> ${product.material}</p>
            <div class="btn-row">
                <a href="#" class="view" onclick="showProductDetails(${product.id}); return false;">
                    <i class="fas fa-eye"></i> View Details
                </a>
                <a href="#" class="inq" onclick="sendInquiry(${product.id}); return false;">
                    <i class="fas fa-envelope"></i> Send Inquiry
                </a>
            </div>
        `;
        container.appendChild(productCard);
    });
}

// Function to show product details
function showProductDetails(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <div class="product-detail-container">
            <div class="detail-left">
                <img src="${product.image}" alt="${product.title}" class="detail-image"
                     onerror="this.src='../uploads/products/default.jpg'">
                <div class="image-thumbnails">
                    <img src="${product.image}" alt="Main view" class="active" onclick="changeMainImage(this)">
                    <!-- Additional thumbnails can be added here -->
                </div>
            </div>
            <div class="detail-right">
                <h2>${product.title}</h2>
                <p class="detail-price">${product.price}</p>
                
                <div class="rating" style="margin: 10px 0; color: #ffa500; font-size: 14px;">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span style="color: #666; margin-left: 10px;">${product.rating} (${product.reviews} reviews)</span>
                </div>
                
                <p class="detail-desc">${product.description}</p>
                
                <div class="specs-section">
                    <h3><i class="fas fa-cogs"></i> Specifications</h3>
                    <div class="specs-grid">
                        ${Object.entries(product.specs).map(([key, value]) => `
                            <div class="spec-item">
                                <span class="spec-key">${key}:</span>
                                <span class="spec-value">${value}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div class="features-section">
                    <h3><i class="fas fa-check-circle"></i> Key Features</h3>
                    <ul>
                        ${product.features.map(feature => `
                            <li>
                                <i class="fas fa-check"></i>
                                ${feature}
                            </li>
                        `).join('')}
                    </ul>
                </div>
                
                <div class="action-buttons">
                    <button class="inquiry-btn" onclick="sendInquiry(${product.id})">
                        <i class="fas fa-comment-dots"></i> Send Inquiry
                    </button>
                    <button class="quote-btn" onclick="requestQuote(${product.id})">
                        <i class="fas fa-file-invoice"></i> Get Best Quote
                    </button>
                    <button class="call-btn" onclick="callSeller(${product.id})">
                        <i class="fas fa-phone-alt"></i> Call Seller
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Show modal
    const modal = document.getElementById('productModal');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Function to change main image when thumbnail is clicked
function changeMainImage(thumbnail) {
    const mainImage = document.querySelector('.detail-image');
    const thumbnails = document.querySelectorAll('.image-thumbnails img');
    
    // Remove active class from all thumbnails
    thumbnails.forEach(img => img.classList.remove('active'));
    
    // Add active class to clicked thumbnail
    thumbnail.classList.add('active');
    
    // Change main image
    mainImage.src = thumbnail.src;
    mainImage.alt = thumbnail.alt;
}

// Modal setup
function setupModal() {
    const modal = document.getElementById('productModal');
    const closeBtn = document.querySelector('.close-modal');
    
    closeBtn.onclick = function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    modal.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
    
    // Close on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
}

// Inquiry functions
function sendInquiry(productId) {
    const product = products.find(p => p.id === productId);
    
    // Show a confirmation message
    const message = `Inquiry sent for: ${product.title}\n\nOur sales team will contact you within 24 hours with more details.`;
    alert(message);
    
    // In real implementation, you would make an AJAX call here
    // Example: fetch('/api/send-inquiry', { method: 'POST', body: JSON.stringify({productId}) });
    
    console.log(`Inquiry sent for product ID: ${productId}`);
}

function requestQuote(productId) {
    const product = products.find(p => p.id === productId);
    
    // You could open a quote form or redirect to a quote page
    const quantity = prompt(`Request quote for: ${product.title}\n\nEnter required quantity (MOQ: ${product.moq}):`);
    
    if (quantity) {
        alert(`Quote request submitted for ${quantity} units.\nWe'll email you the best price shortly.`);
        console.log(`Quote requested for ${quantity} units of product ID: ${productId}`);
    }
}

function callSeller(productId) {
    // Show contact number - in real implementation, this would be from your database
    const contactNumber = '+91-9876543210';
    const confirmCall = confirm(`Call our sales representative?\n\nPhone: ${contactNumber}\n\nOperating Hours: 9 AM - 6 PM (Mon-Sat)`);
    
    if (confirmCall) {
        window.location.href = `tel:${contactNumber}`;
    }
}

// Optional: Add filtering functionality
function setupFiltering() {
    // You can add category filters, price range filters, etc.
    console.log('Filtering functionality ready to implement');
}

// Optional: Add image lazy loading
function setupLazyLoading() {
    const images = document.querySelectorAll('.product-img');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('loading');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => {
        img.classList.add('loading');
        img.dataset.src = img.src;
        imageObserver.observe(img);
    });
}