// // server.js (Unified Backend for Gemini Chat and Authenticated Makcorps Search)

// require('dotenv').config(); 
// const express = require('express');
// const { GoogleGenAI } = require('@google/genai');
// const cors = require('cors'); 
// const axios = require('axios'); 

// const app = express();
// const port = process.env.PORT || 3000; 

// // --------------------------------------------------------------------
// // --- 1. CONFIGURATION AND INITIALIZATION ---
// // --------------------------------------------------------------------

// // Initialize the Gemini AI Client
// const geminiApiKey = process.env.GEMINI_API_KEY;
// if (!geminiApiKey) {
//     console.error("ERROR: GEMINI_API_KEY not found. Check your .env file.");
//     process.exit(1);
// }
// const ai = new GoogleGenAI({ apiKey: geminiApiKey });

// // Initialize Makcorps Parameters
// const MAKCORPS_API_KEY = process.env.MAKCORPS_API_KEY;

// if (!MAKCORPS_API_KEY) {
//     console.error("ERROR: MAKCORPS_API_KEY not found. Check your .env file.");
//     process.exit(1);
// }
// // This is a common authenticated endpoint URL structure
// const MAKCORPS_API_URL = 'https://api.makcorps.com/city'; // old https://api.makcorps.com/free


// // --------------------------------------------------------------------
// // --- 2. MIDDLEWARE SETUP ---
// // --------------------------------------------------------------------

// app.use(cors()); 
// app.use(express.json()); 

// // --------------------------------------------------------------------
// // --- 3. ENDPOINTS ---
// // --------------------------------------------------------------------

// /**
//  * Endpoint A: Gemini Chat Assistance
//  */
// app.post('/chat', async (req, res) => {
//     try {
//         const { message } = req.body;
//         // ... (Gemini logic) ...
//         const response = await ai.models.generateContent({
//             model: "gemini-2.5-flash",
//             contents: [{ role: "user", parts: [{ text: message }] }]
//         });
//         res.json({ response: response.text });
//     } catch (error) {
//         console.error("[Gemini] API Error:", error);
//         res.status(500).json({ error: 'Failed to get a response from the AI.' });
//     }
// });


// /**
//  * Endpoint B: Authenticated Makcorps Hotel Search Proxy
//  */
// // app.post('/api/makcorps-search', async (req, res) => {
// //     // Destructure search parameters from the request body
// //     const { destination, checkin, checkout, rooms } = req.body; 

// //     if (!destination || !checkin || !checkout || !rooms) {
// //         return res.status(400).json({ error: 'Missing required search parameters.' });
// //     }
    
// //     // Configure the request for Authenticated Makcorps API
// //     const options = {
// //         method: 'GET',
// //         url: MAKCORPS_API_URL,// Now 'https://api.makcorps.com/free'
// //         params: {
// //             city: destination,
// //             //checkin: checkin,
// //            // checkout: checkout,
// //            // rooms: rooms,
// //             apikey: MAKCORPS_API_KEY
// //         },
// //         headers: {}
// //     };
// //     console.log(`Searching AUTHENTICATED Makcorps for hotels in: ${destination}`);
// //     console.log(`Attempting connection to: ${options.url}`);

// //     try {
// //         const response = await axios.request(options);
        
// //         // Handling various response structures
// //         const data = response.data;
// //         if (data && Array.isArray(data.hotels)) {
// //              res.json(data.hotels); 
// //         } else if (Array.isArray(data)) {
// //              res.json(data); 
// //         } else {
// //              res.status(500).json({ error: 'Makcorps returned an unusual response format or no hotels found.', details: data });
// //         }
       
// //     } catch (error) {
// //         console.error("Makcorps Hotel Search Error:", error.message);
        
// //         // *** CRITICAL: LOGGING THE API'S ACTUAL ERROR RESPONSE ***
// //         if (error.response) {
// //             console.error("Makcorps API Response Error Status:", error.response.status);
// //             console.error("Makcorps API Response Error Data:", error.response.data);
// //             return res.status(500).json({ 
// //                 error: `Failed to fetch hotel data from Authenticated Makcorps API. Status: ${error.response.status}.`, 
// //                 // Return the API's actual error status and message
// //                 details: error.response.data || 'No specific details provided by Makcorps.'
// //             });
// //         }
        
// //         res.status(500).json({ 
// //             error: 'Failed to fetch hotel data from Authenticated Makcorps API (Network/DNS issue).', 
// //             details: error.message
// //         });
// //     }
// // });

// app.post('/api/makcorps-map', async (req, res) => {
//     const { name } = req.body;
//     if (!name) return res.status(400).json({ error: 'City name required.' });

//     try {
//         const response = await axios.get('https://api.makcorps.com/mapping', {
//             params: { api_key: MAKCORPS_API_KEY, name }
//         });

//         res.json(response.data); // returns document_id(s)
//     } catch (error) {
//         res.status(500).json({ error: 'Mapping API error', details: error.response?.data });
//     }
// });

// app.post('/api/makcorps-search', async (req, res) => {
//     try {
//         const { destination, checkin, checkout, rooms, adults } = req.body;

//         if (!destination || !checkin || !checkout || !rooms || !adults) {
//             return res.status(400).json({ error: 'Missing required search parameters.' });
//         }

//         // 📌 Use the documented “city” endpoint with proper query parameters
//         const options = {
//             method: 'GET',
//             url: 'https://api.makcorps.com/city',
//             params: {
//                 api_key: MAKCORPS_API_KEY,
//                 cityid: destination,              // Must be a city ID, not a name
//                 pagination: 0,
//                 cur: 'PHP',
//                 rooms,
//                 adults,
//                 checkin,
//                 checkout,
//                 tax: true
//             }
//         };

//         const response = await axios.request(options);

//         if (response.data && response.data.currentPageHotelsCount >= 0) {
//             res.json(response.data);
//         } else {
//             return res.status(404).json({ error: 'No hotels found for that city.' });
//         }

//     } catch (error) {
//         console.error("Hotel Search Error:", error.response?.data || error.message);
//         return res.status(500).json({
//             error: 'Error fetching hotels from Makcorps API.',
//             details: error.response?.data || error.message
//         });
//     }
// });

// // --------------------------------------------------------------------
// // --- 4. SERVER START ---
// // --------------------------------------------------------------------

// // Start the server
// app.listen(port, () => {
//     console.log(`✅ Server running securely on http://localhost:${port}`);
//     console.log(`Endpoints active: /chat (for Gemini) and /api/makcorps-search (Authenticated)`);
// });
// server.js (Unified Backend for Gemini Chat and Authenticated Makcorps Search)

// server.js (Unified Backend for Gemini Chat and Authenticated Makcorps Search)

require('dotenv').config();
const express = require('express');
const { GoogleGenAI } = require('@google/genai');
const cors = require('cors');
const axios = require('axios');

const app = express();
const port = process.env.PORT || 3000;

// --------------------------------------------------------------------
// --- 1. CONFIGURATION AND INITIALIZATION ---
// --------------------------------------------------------------------

// Initialize the Gemini AI Client
const geminiApiKey = process.env.GEMINI_API_KEY;
if (!geminiApiKey) {
    console.error("ERROR: GEMINI_API_KEY not found. Check your .env file.");
    process.exit(1);
}
const ai = new GoogleGenAI({ apiKey: geminiApiKey });

// Initialize Makcorps Parameters
const MAKCORPS_API_KEY = process.env.MAKCORPS_API_KEY;

if (!MAKCORPS_API_KEY) {
    console.error("ERROR: MAKCORPS_API_KEY not found. Check your .env file.");
    process.exit(1);
}
// This is a common authenticated endpoint URL structure
const MAKCORPS_API_URL = 'https://api.makcorps.com/city'; // old https://api.makcorps.com/free


// --------------------------------------------------------------------
// --- 2. MIDDLEWARE SETUP ---
// --------------------------------------------------------------------

app.use(cors());
app.use(express.json());

// --------------------------------------------------------------------
// --- 3. ENDPOINTS ---
// --------------------------------------------------------------------

/**
 * Endpoint A: Gemini Chat Assistance
 */
app.post('/chat', async (req, res) => {
    try {
        const { message } = req.body;
        // ... (Gemini logic) ...
        const response = await ai.models.generateContent({
            model: "gemini-2.5-flash",
            contents: [{ role: "user", parts: [{ text: message }] }]
        });
        res.json({ response: response.text });
    } catch (error) {
        console.error("[Gemini] API Error:", error);
        res.status(500).json({ error: 'Failed to get a response from the AI.' });
    }
});


/**
 * Helper: Find City ID from City Name using Makcorps Mapping API
 */
async function findCityId(cityName) {
    try {
        const response = await axios.get('https://api.makcorps.com/mapping', {
            params: { api_key: MAKCORPS_API_KEY, name: cityName }
        });

        // The API returns an array of document_ids for the city name
        const cityMappings = response.data;

        if (Array.isArray(cityMappings) && cityMappings.length > 0 && cityMappings[0].document_id) {
            // Return the first found document_id as the cityid
            return cityMappings[0].document_id;
        }
        return null; 
    } catch (error) {
        console.error("Makcorps Mapping API Error:", error.response?.data || error.message);
        // Throw an error to be caught by the calling function
        throw new Error('Failed to map city name to ID.'); 
    }
}


/**
 * Endpoint B: Authenticated Makcorps Hotel Search Proxy
 * This now handles the mapping step internally.
 */
app.post('/api/makcorps-search', async (req, res) => {
    try {
        const { destination, checkin, checkout, rooms, adults } = req.body;

        if (!destination || !checkin || !checkout || !rooms || !adults) {
            return res.status(400).json({ error: 'Missing required search parameters.' });
        }
        
        // --- STEP 1: Get the City ID from the name ---
        console.log(`Step 1: Attempting to map city name "${destination}" to City ID.`);
        const cityId = await findCityId(destination);

        if (!cityId) {
            console.error(`City ID not found for: ${destination}`);
            return res.status(404).json({ error: `Could not find a Makcorps City ID for "${destination}".` });
        }
        
        console.log(`Step 1 Success: Found City ID: ${cityId}`);

        // --- STEP 2: Use the City ID to search for hotels ---
        
        // 📌 Use the documented “city” endpoint with proper query parameters
        const options = {
            method: 'GET',
            url: MAKCORPS_API_URL, // 'https://api.makcorps.com/city'
            params: {
                api_key: MAKCORPS_API_KEY,
                cityid: cityId,                // Now using the retrieved ID
                pagination: 0,
                cur: 'PHP',
                rooms,
                adults,
                checkin,
                checkout,
                tax: true
            }
        };
        
        console.log(`Step 2: Searching hotels for City ID ${cityId} using URL: ${options.url}`);
        const response = await axios.request(options);
        
        // Check for hotel results (Makcorps uses currentPageHotelsCount)
        if (response.data && response.data.currentPageHotelsCount > 0) {
            // Returning the full response data. Your frontend will handle the 'hotels' array inside it.
            res.json(response.data);
        } else {
            // Success response (200 OK) but no results found
            return res.status(200).json({ hotels: [], message: 'No hotels found for that city on the selected dates.' });
        }

    } catch (error) {
        console.error("Hotel Search Error (Fatal):", error.response?.data || error.message);
        // Log the error status if available
        const status = error.response ? error.response.status : 500;
        
        return res.status(status).json({
            error: 'Error fetching hotels from Makcorps API.',
            details: error.response?.data || error.message
        });
    }
});

// Remove the standalone mapping endpoint as it's now a function.
app.post('/api/makcorps-map', (req, res) => {
    return res.status(404).json({ error: 'This endpoint is deprecated. Use /api/makcorps-search directly.' });
});


// --------------------------------------------------------------------
// --- 4. SERVER START ---
// --------------------------------------------------------------------

// Start the server
app.listen(port, () => {
    console.log(`✅ Server running securely on http://localhost:${port}`);
    console.log(`Endpoints active: /chat (for Gemini) and /api/makcorps-search (Authenticated)`);
});