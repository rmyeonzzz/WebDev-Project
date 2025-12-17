function switchTab(tab) {
    const flightContainer = document.getElementById('flight-container');
    const hotelContainer = document.getElementById('hotel-container');
    const btnFlight = document.getElementById('btn-flight');
    const btnHotel = document.getElementById('btn-hotel');

    if (tab === 'flight') {
        flightContainer.style.display = 'block';
        hotelContainer.style.display = 'none';
        btnFlight.classList.add('active');
        btnHotel.classList.remove('active');
    } else {
        flightContainer.style.display = 'none';
        hotelContainer.style.display = 'block';
        btnFlight.classList.remove('active');
        btnHotel.classList.add('active');
    }
}

async function getWeather(lat, lon) {
    try {
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`;
        const response = await fetch(url);
        const data = await response.json();

        const temp = data.current_weather.temperature;
        const code = data.current_weather.weathercode;

        let icon = "☀️";
        if (code >= 1 && code <= 3) icon = "⛅";
        if (code >= 45 && code <= 48) icon = "🌫️";
        if (code >= 51 && code <= 67) icon = "🌧️";
        if (code >= 71) icon = "❄️";
        if (code >= 95) icon = "⚡";

        return { temp: temp, icon: icon };

    } catch (error) {
        console.error("Weather API Error:", error);
        return null;
    }
}

async function searchAttractions() {
    const city = document.getElementById('cityInput').value.toLowerCase().trim();
    const resultsDiv = document.getElementById('attractionResults');

    if (!city) {
        alert("Please enter a city name");
        return;
    }

    resultsDiv.innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted fw-bold">Finding best tours...</p>
        </div>`;

    try {
        const response = await fetch('/viggo/utils/get_attractions.php?city=' + city);

        if (!response.ok) {
            throw new Error(`PHP file not found (404). Check folder path!`);
        }

        const jsonData = await response.json();

        if (jsonData.error) {
            resultsDiv.innerHTML = `<div class="alert alert-danger fw-bold">${jsonData.error}</div>`;
            return;
        }

        if (jsonData.data && jsonData.data.length > 0) {
            let weatherData = null;
            const firstItem = jsonData.data[0];

            if (firstItem.geoCode) {
                weatherData = await getWeather(firstItem.geoCode.latitude, firstItem.geoCode.longitude);
            }

            displayAttractions(jsonData.data, weatherData, city);

        } else {
            resultsDiv.innerHTML = '<div class="alert alert-warning">No tours found for this location.</div>';
        }

    } catch (error) {
        console.error('Attraction Search Error:', error);
        resultsDiv.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
    }
}

function displayAttractions(items, weather, cityName) {
    const resultsDiv = document.getElementById('attractionResults');
    resultsDiv.innerHTML = '';
    resultsDiv.className = 'row g-4'; 

    if (weather) {
        const weatherHeader = document.createElement('div');
        weatherHeader.className = 'col-12 mb-2 text-center';
        weatherHeader.innerHTML = `
            <div class="card border-0 shadow-sm d-inline-block bg-primary text-white p-3 rounded-pill">
                <h5 class="m-0 fw-bold text-capitalize">
                    ${weather.icon} ${cityName}: <span class="ms-2">${weather.temp}°C</span>
                </h5>
            </div>
        `;
        resultsDiv.appendChild(weatherHeader);
    }

    items.forEach(item => {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4';

        let imageUrl = 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=500&q=60';
        if (item.pictures && item.pictures.length > 0) {
            imageUrl = item.pictures[0];
        }

        let priceInfo = '';
        if (item.price) {
            priceInfo = `<h6 class="text-success fw-bold mb-3">${item.price.amount} ${item.price.currencyCode}</h6>`;
        }

        col.innerHTML = `
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                <div style="height: 200px; overflow: hidden;">
                    <img src="${imageUrl}" class="w-100 h-100 object-fit-cover" alt="${item.name}" onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-truncate">${item.name}</h5>
                    <p class="card-text text-muted small flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        ${item.shortDescription || "No description available."}
                    </p>
                    ${priceInfo}
                    <a href="${item.bookingLink}" target="_blank" class="btn btn-primary w-100 fw-bold rounded-pill">Book Now</a>
                </div>
            </div>
        `;

        resultsDiv.appendChild(col);
    });
}

async function searchHotels() {
    const query = document.getElementById('hotel-location-input').value.trim();
    const resultsContainer = document.getElementById('hotel-results');

    if (!query) {
        alert("Please enter a destination (e.g., Manila, Cebu).");
        return;
    }

    resultsContainer.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted fw-bold">Searching for "${query}"...</p>
        </div>`;

    const apiKey = '93433d1ea5msh95f1dcda267fa88p1e342fjsn7bdbd1426f49';
    const apiHost = 'booking-com15.p.rapidapi.com';

   try {
        const locUrl = `https://${apiHost}/api/v1/hotels/searchDestination?query=${encodeURIComponent(query)}`;
        const locRes = await fetch(locUrl, {
            method: 'GET',
            headers: { 
                'X-RapidAPI-Host': apiHost, // Use Capitalized Headers
                'X-RapidAPI-Key': apiKey 
            }
        });

        // 🛑 CRITICAL CHECK FOR 403
        if (locRes.status === 403) {
            resultsContainer.innerHTML = `
                <div class="col-12 text-center py-4">
                    <div class="alert alert-danger border-0 shadow-sm">
                        <i class="fas fa-lock me-2"></i>
                        <strong>Access Denied (403).</strong><br>
                        Please log in to RapidAPI and click "Subscribe to Test" on the Booking.com 15 API Pricing page.
                    </div>
                </div>`;
            return;
        }

        const locJson = await locRes.json();

        let destData = locJson.data.find(item => item.search_type === 'city') || locJson.data[0];

        if (destData.search_type === 'country') {
            resultsContainer.innerHTML = `
                <div class="col-12 text-center p-4">
                    <div class="alert alert-warning">
                        <h5>Location Too Broad</h5>
                        <p class="mb-0">Please search for a specific city (e.g., Manila, Makati, Cebu City).</p>
                    </div>
                </div>`;
            return;
        }

        const today = new Date();
        today.setDate(today.getDate() + 60);
        const nextDay = new Date(today);
        nextDay.setDate(today.getDate() + 1);

        const params = new URLSearchParams({
            dest_id: destData.dest_id,
            search_type: destData.search_type,
            arrival_date: today.toISOString().split('T')[0],
            departure_date: nextDay.toISOString().split('T')[0],
            adults: '1',
            room_qty: '1',
            units: 'metric',
            currency_code: 'USD'
        });

        const hotelRes = await fetch(`https://${apiHost}/api/v1/hotels/searchHotels?${params}`, {
            method: 'GET',
            headers: { 'x-rapidapi-host': apiHost, 'x-rapidapi-key': apiKey }
        });
        const hotelJson = await hotelRes.json();

        resultsContainer.innerHTML = '';
        const hotels = hotelJson.data?.hotels || hotelJson.data?.result || [];

resultsContainer.innerHTML = ''; // Clear results
resultsContainer.className = "row g-4"; // Ensure row class is applied for wrapping

    if (hotels.length > 0) {
    hotels.forEach(hotel => {
    // 1. FIRST, define imageUrl logic
    let imageUrl = 'https://via.placeholder.com/300x200?text=No+Image';
    if (hotel.property && hotel.property.photoUrls && hotel.property.photoUrls[0]) {
        imageUrl = hotel.property.photoUrls[0];
    } else if (hotel.main_photo_url) {
        imageUrl = hotel.main_photo_url;
    }

    // 2. Define other variables safely
    const name = hotel.property?.name || hotel.hotel_name || "Unnamed Hotel";
    const score = hotel.property?.reviewScore || hotel.review_score || '-';
    let price = "Check Price";
    if (hotel.property?.priceBreakdown) {
        price = `$${Math.round(hotel.property.priceBreakdown.grossPrice.value)}`;
    }

    // 3. NOW use the variables in your template string
    const card = `
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="hotel-image-wrapper" style="position: relative; width: 100%; padding-top: 100%; overflow: hidden;">
                    <img 
                        src="${imageUrl}" 
                        class="card-img-top" 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                        alt="${name}" 
                        onerror="this.src='https://via.placeholder.com/400x400?text=No+Image';"
                    >
                    <span class="badge bg-primary position-absolute top-0 end-0 m-3 shadow-sm">${score}</span>
                </div>
                <div class="card-body d-flex flex-column p-4">
                    <h5 class="card-title fw-bold text-truncate mb-1">${name}</h5>
                    <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt text-danger me-1"></i> ${destData.name}</p>
                    
                    <div class="mt-auto d-flex justify-content-between align-items-end pt-3 border-top">
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Est. per night</small>
                            <span class="fw-bold text-dark fs-5">${price}</span>
                        </div>
                        <a href="hotel_details.php?id=${hotel.hotel_id}&image=${encodeURIComponent(imageUrl)}&name=${encodeURIComponent(name)}&price=${encodeURIComponent(price)}" 
                            class="btn btn-primary fw-bold rounded-pill px-3">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>`;
    resultsContainer.innerHTML += card;
});
}
        else {
            resultsContainer.innerHTML = `
                <div class="col-12 text-center mt-4">
                    <div class="alert alert-light border shadow-sm">
                        <h5 class="mb-1">No hotels found.</h5>
                        <p class="text-muted mb-0">Try a different city or date.</p>
                    </div>
                </div>`;
        }

    } catch (error) {
        console.error('Hotel API Error:', error);
        resultsContainer.innerHTML = `<div class="alert alert-danger text-center">Unable to load data. Please try again later.</div>`;
    }
}