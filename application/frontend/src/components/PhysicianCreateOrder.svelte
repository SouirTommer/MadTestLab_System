<script>
    import { onMount } from "svelte";

    let patientID = "1";
    let appointmentID = "101";
    let testCode = "1";
    let physicianID = "1";
    let responseMessage = "";

    onMount(async () => {
        try {
            const data = {
                patientID: patientID,
                appointmentID: appointmentID,
                testCode: testCode,
                physicianID: physicianID
            };

            const response = await fetch(
                "http://localhost:8080/database/Physician/physician_create_order_action.php",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    credentials: "include", // Include credentials (cookies) with the request
                    body: JSON.stringify(data)
                }
            );

            const result = await response.json();
            console.log("Fetched data:", result);
            responseMessage = result.message;
        } catch (error) {
            console.error("Error creating order:", error);
            responseMessage = "Error creating order";
        }
    });
</script>

<div class="flex flex-col mt-8">
    <h1>Create Order</h1>
    <form id="createOrderForm">
        <label for="patientID">Patient ID:</label>
        <input type="text" id="patientID" name="patientID" bind:value={patientID} required><br><br>

        <label for="appointmentID">Appointment ID:</label>
        <input type="text" id="appointmentID" name="appointmentID" bind:value={appointmentID} required><br><br>

        <label for="testCode">Test Code:</label>
        <input type="text" id="testCode" name="testCode" bind:value={testCode} required><br><br>

        <label for="physicianID">Physician ID:</label>
        <input type="text" id="physicianID" name="physicianID" bind:value={physicianID} required><br><br>

        <button type="button" on:click={onMount}>Create Order</button>
    </form>

    <div id="response">{responseMessage}</div>
</div>