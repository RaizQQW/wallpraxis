// Remove placeholder
document.getElementById('placeholder_consent').remove();

// Initialize map
const map = L.map('map').setView([53.3573, 10.2118], 15); // Coordinates for Winsen (Luhe)

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Add marker for the practice
const marker = L.marker([53.3573, 10.2118]).addTo(map);
marker.bindPopup(`
    <strong>Wallpraxis Winsen</strong><br>
    Wallstraße 1<br>
    21423 Winsen Luhe<br>
    <a href="tel:004941712200">04171 22 00</a>
`).openPopup(); 