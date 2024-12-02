async function getAPI(url, params = {}) {
    try {
        // Construct query string from params
        const queryString = new URLSearchParams(params).toString();
        const response = await fetch(`${url}?${queryString}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`Error: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('GET API Error:', error);
        throw error;
    }
}

async function postAPI(url, body = {}) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(body),
        });

        if (!response.ok) {
            throw new Error(`Error: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('POST API Error:', error);
        throw error;
    }
}