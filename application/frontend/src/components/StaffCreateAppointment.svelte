<script>
    import Modal from "./Modal.svelte";
    import { onMount } from "svelte";

    export let onClose = () => {}; // Function to handle closing the modal

    let patients = [];
    let physicians = [];
    let patient = "";
    let physician = "";
    let datetime = "";

    onMount(async () => {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Secretary/secretary_read_appointment_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            patients = data.patients;
            physicians = data.physicians;
            console.log("Fetched data:", data);
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });

    async function createAppointment(event) {
        event.preventDefault(); // Prevent page reload
        const formData = new FormData(event.target);

        try {
            const response = await fetch(
                "http://localhost:8080/database/Secretary/secretary_create_appointment_action.php",
                {
                    method: "POST",
                    credentials: "include", // Include credentials (cookies) with the request
                    body: formData,
                },
            );

            const result = await response.json();
            console.log("Create appointment response:", result); // Debugging statement
            if (result.status === "success") {
                alert("Appointment created successfully");
                onClose(); // Call the onClose function to close the modal
            } else {
                alert("Failed to create appointment: " + result.message);
            }
        } catch (error) {
            console.error("Error creating appointment:", error);
            alert("Error creating appointment. Please try again.");
        }
    }
</script>

<!-- Modal for creating an appointment -->
<Modal show={true} {onClose}>
    <h2 class="text-xl font-bold mb-4">Create Appointment</h2>
    <form on:submit|preventDefault={createAppointment}>
    <!-- <form action="http://localhost:8080/database/Secretary/secretary_create_appointment_action.php" method="POST"> -->
        <div class="mb-4">
            <label for="patient" class="block text-gray-700">Patient</label>
            <select
                id="patient"
                name="patient"
                bind:value={patient}
                class="w-full border rounded px-3 py-2"
                required
            >
                <option value="">Select a patient</option>
                {#each patients as patient}
                    <option value={patient.PatientID}
                        >{patient.FirstName} {patient.LastName}</option
                    >
                {/each}
            </select>
        </div>
        <div class="mb-4">
            <label for="physician" class="block text-gray-700">Physician</label>
            <select
                id="physician"
                bind:value={physician}
                name="physician"
                class="w-full border rounded px-3 py-2"
                required
            >
                <option value="">Select a physician</option>
                {#each physicians as physician}
                    <option value={physician.LabStaffID}
                        >{physician.FirstName} {physician.LastName}</option
                    >
                {/each}
            </select>
        </div>
        <div class="mb-4">
            <label for="datetime" class="block text-gray-700"
                >Date and Time</label
            >
            <input
                type="datetime-local"
                id="datetime"
                name="datetime"
                bind:value={datetime}
                class="w-full border rounded px-3 py-2"
            />
        </div>
        <div class="flex flex-1 justify-center pt-6">
            <button
                type="submit"
                class="bg-indigo-600 w-36 text-white px-4 py-2 rounded"
            >
                Create
            </button>
        </div>
    </form>
</Modal>
