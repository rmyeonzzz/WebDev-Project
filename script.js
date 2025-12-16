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

/**
 * Fetch weather data from Open-Meteo (No API Key required).
 */
async function getWeather(lat, lon) {
    try {
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`;
        const response = await fetch(url);
        const data = await response.json();

        const temp = data.current_weather.temperature;
        const code = data.current_weather.weathercode;

        // Map weather codes to emojis
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

/* =========================================
   2. TOURIST ATTRACTIONS LOGIC
   ========================================= */

async function searchAttractions() {
    const city = document.getElementById('cityInput').value.toLowerCase().trim();
    const resultsDiv = document.getElementById('attractionResults');

    if (!city) {
        alert("Please enter a city name");
        return;
    }

    resultsDiv.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div><p>Finding best tours...</p></div>';

    try {
        const response = await fetch('/viggo/utils/get_attractions.php?city=' + city);

        if (!response.ok) {
            throw new Error(`PHP file not found (404). Check folder path!`);
        }

        const jsonData = await response.json();

        if (jsonData.error) {
            resultsDiv.innerHTML = `<p style="color:red; font-weight:bold;">${jsonData.error}</p>`;
            return;
        }

        if (jsonData.data && jsonData.data.length > 0) {
            // Fetch weather using coordinates of the first result
            let weatherData = null;
            const firstItem = jsonData.data[0];

            if (firstItem.geoCode) {
                weatherData = await getWeather(firstItem.geoCode.latitude, firstItem.geoCode.longitude);
            }

            displayAttractions(jsonData.data, weatherData, city);

        } else {
            resultsDiv.innerHTML = '<p>No tours found for this location.</p>';
        }

    } catch (error) {
        console.error('Attraction Search Error:', error);
        resultsDiv.innerHTML = `<p style="color:red">Error: ${error.message}</p>`;
    }
}

function displayAttractions(items, weather, cityName) {
    const resultsDiv = document.getElementById('attractionResults');
    resultsDiv.innerHTML = '';

    // Render Weather Header if data exists
    if (weather) {
        const weatherHeader = document.createElement('div');
        weatherHeader.className = 'col-12 mb-4 text-center';
        weatherHeader.innerHTML = `
            <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; display: inline-block;">
                <h4 style="margin:0; color: #0275d8; text-transform: capitalize;">
                    ${weather.icon} Current Weather in ${cityName}
                </h4>
                <span style="font-size: 1.5rem; font-weight: bold;">${weather.temp}°C</span>
            </div>
        `;
        resultsDiv.appendChild(weatherHeader);
    }

    // Render Attraction Cards
    items.forEach(item => {
        const card = document.createElement('div');
        card.className = 'attraction-card';

        let imageUrl = 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=500&q=60';
        if (item.pictures && item.pictures.length > 0) {
            imageUrl = item.pictures[0];
        }

        let priceInfo = '';
        if (item.price) {
            priceInfo = `<p class="price"><strong>Price:</strong> ${item.price.amount} ${item.price.currencyCode}</p>`;
        }

        card.innerHTML = `
            <div class="image-container">
                <img src="${imageUrl}" alt="${item.name}" onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
            </div>
            <div class="card-content">
                <h3>${item.name}</h3>
                <p class="desc">${item.shortDescription || "No description available."}</p>
                ${priceInfo}
                <a href="${item.bookingLink}" target="_blank" class="view-btn">Book Now</a>
            </div>
        `;

        resultsDiv.appendChild(card);
    });
}

/* =========================================
   3. HOTEL SEARCH LOGIC (Booking.com)
   ========================================= */

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
            <p class="mt-2 text-muted">Searching for "${query}"...</p>
        </div>`;

    const apiKey = '329580a304msh4d65ca35ca58b79p10bebfjsnd06f2e179e59';
    const apiHost = 'booking-com15.p.rapidapi.com';

    try {
        // Step 1: Find Destination ID
        const locUrl = `https://${apiHost}/api/v1/hotels/searchDestination?query=${encodeURIComponent(query)}`;
        const locRes = await fetch(locUrl, {
            method: 'GET',
            headers: { 'x-rapidapi-host': apiHost, 'x-rapidapi-key': apiKey }
        });
        const locJson = await locRes.json();

        if (!locJson.status || !locJson.data || locJson.data.length === 0) {
            resultsContainer.innerHTML = '<p class="text-center text-danger">No locations found. Please try a specific city name.</p>';
            return;
        }

        // Prioritize city results, fallback to first result
        let destData = locJson.data.find(item => item.search_type === 'city') || locJson.data[0];

        if (destData.search_type === 'country') {
            resultsContainer.innerHTML = `
                <div class="col-12 text-center text-warning p-4">
                    <h5>Location Too Broad</h5>
                    <p>Please search for a specific city (e.g., Manila, Makati, Cebu City).</p>
                </div>`;
            return;
        }

        // Step 2: Fetch Hotels (Book 60 days out for availability)
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

        // Step 3: Render Hotel Results
        resultsContainer.innerHTML = '';
        const hotels = hotelJson.data?.hotels || hotelJson.data?.result || [];

        if (hotels.length > 0) {
            hotels.forEach(hotel => {
                let imageUrl = 'https://via.placeholder.com/300x200?text=No+Image';
                if (hotel.property?.photoUrls?.[0]) imageUrl = hotel.property.photoUrls[0];
                else if (hotel.main_photo_url) imageUrl = hotel.main_photo_url;

                const name = hotel.property?.name || hotel.hotel_name || "Unnamed Hotel";
                const score = hotel.property?.reviewScore || hotel.review_score || '-';

                let price = "Check Price";
                if (hotel.property?.priceBreakdown) {
                    price = `$${Math.round(hotel.property.priceBreakdown.grossPrice.value)}`;
                }

                const card = `
                    <div class="hotel-card">
                        <img 
                            src="${imageUrl}" 
                            alt="${name}" 
                            referrerpolicy="no-referrer"
                            onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Image+Unavailable';"
                        >
                        <div class="hotel-info">
                            <h5 class="fw-bold text-truncate">${name}</h5>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">${score}</span>
                                <small class="text-muted">Review Score</small>
                            </div>
                            <p class="text-muted small mb-1">📍 ${destData.name}</p>
                            
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <div>
                                    <small class="text-muted d-block">Est. per night</small>
                                    <span class="fw-bold text-dark fs-5">${price}</span>
                                </div>
                                <a href="hotel_details.php?id=${hotel.hotel_id}&image=${encodeURIComponent(imageUrl)}&name=${encodeURIComponent(name)}&price=${encodeURIComponent(price)}" 
                                    class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>`;
                resultsContainer.innerHTML += card;
            });
        } else {
            resultsContainer.innerHTML = `
                <div class="col-12 text-center mt-4">
                    <h5>No hotels found.</h5>
                    <p class="text-muted">Try a different city or date.</p>
                </div>`;
        }

    } catch (error) {
        console.error('Hotel API Error:', error);
        resultsContainer.innerHTML = `<p class="text-danger text-center">Unable to load data. Please try again later.</p>`;
    }
}