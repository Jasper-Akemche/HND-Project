class DriverDashboard {
    constructor() {
        this.map = null;
        this.driverId = null; // This would come from authentication
        
        this.initEventListeners();
        this.initMap();
        this.fetchRequests();
    }

    initEventListeners() {
        document.getElementById('requests-link').addEventListener('click', () => {
            this.fetchRequests();
        });
    }

    initMap() {
        try {
            // Ensure Google Maps is loaded
            if (typeof google === 'undefined' || !google.maps) {
                throw new Error('Google Maps API not loaded');
            }

            this.map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 0, lng: 0 },
                zoom: 2,
                disableDefaultUI: true,
                styles: [
                    // Custom map styling for a clean look
                    {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{ color: '#e9e9e9' }]
                    },
                    {
                        featureType: 'landscape',
                        elementType: 'geometry',
                        stylers: [{ color: '#f5f5f5' }]
                    }
                ]
            });

            this.getCurrentLocation();
        } catch (error) {
            console.error('Map Initialization Error:', error);
            this.showErrorMessage('Could not load map. Please try again later.');
        }
    }

    getCurrentLocation() {
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    this.map.setCenter(currentLocation);
                    this.map.setZoom(12);

                    // Add marker for current location
                    new google.maps.Marker({
                        position: currentLocation,
                        map: this.map,
                        title: 'Your Current Location',
                        icon: {
                            url: 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="%234a90e2" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>'
                        }
                    });
                },
                (error) => {
                    console.warn('Geolocation error:', error);
                    this.showErrorMessage('Could not retrieve your location.');
                }
            );
        } else {
            this.showErrorMessage('Geolocation is not supported by this browser.');
        }
    }

    async fetchRequests() {
        try {
            const response = await fetch(`fetch_requests.php?driver_id=${this.driverId}`);
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const requests = await response.json();
            this.displayRequests(requests);
        } catch (error) {
            console.error('Fetch Requests Error:', error);
            this.showErrorMessage('Could not load delivery requests.');
        }
    }

    displayRequests(requests) {
        const container = document.getElementById('requests-container');
        
        if (requests.length === 0) {
            container.innerHTML = '<p>No new delivery requests at the moment.</p>';
            return;
        }

        const requestsHTML = requests.map(request => `
            <div class="request-card" data-request-id="${request.id}">
                <h3>Delivery #${request.id}</h3>
                <div class="request-details">
                    <p><strong>Pickup:</strong> ${request.pickup_location}</p>
                    <p><strong>Delivery:</strong> ${request.delivery_location}</p>
                    <p><strong>Package:</strong> ${request.package_details}</p>
                </div>
                <button class="btn-accept" onclick="driverDashboard.acceptRequest(${request.id})">
                    Accept Request
                </button>
            </div>
        `).join('');

        container.innerHTML = requestsHTML;
    }

    async acceptRequest(requestId) {
        try {
            const response = await fetch('accept_request.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `request_id=${requestId}&driver_id=${this.driverId}`
            });

            const result = await response.json();

            if (result.success) {
                this.drawDeliveryRoute(requestId);
                this.fetchRequests(); // Refresh requests
            } else {
                this.showErrorMessage('Could not accept the request. Please try again.');
            }
        } catch (error) {
            console.error('Accept Request Error:', error);
            this.showErrorMessage('An error occurred while accepting the request.');
        }
    }

    drawDeliveryRoute(requestId) {
        // Placeholder for route drawing logic
        // In a real app, you'd fetch pickup and delivery coordinates and draw the route
    }

    showErrorMessage(message) {
        const container = document.getElementById('requests-container');
        container.innerHTML = `
            <div class="error-message">
                <p>${message}</p>
            </div>
        `;
    }
}

// Initialize dashboard
let driverDashboard;
document.addEventListener('DOMContentLoaded', () => {
    driverDashboard = new DriverDashboard();
});