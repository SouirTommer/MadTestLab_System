<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";

    let appointments = []; // Reactive variable to store fetched orders

    onMount(() => {
        fetchOrders(); // Fetch orders when the component is mounted
    });

    async function fetchOrders() {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Patient/patient_read_appointment_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            console.log("Fetched data:", data); // Debugging: Log fetched data
            appointments = data;
            console.log("Appointments:", appointments);
        } catch (error) {
            console.error("Error fetching orders:", error);
        }
    }

    function getStatusClass(status) {
        switch (status) {
            case "Scheduled":
                return "bg-yellow-200 text-yellow-800";
            case "Completed":
                return "bg-green-200 text-green-800";
            case "In Progress":
                return "bg-blue-200 text-blue-800";
            default:
                return "bg-gray-200 text-gray-800";
        }
    }

    var filter = "all";
</script>

<div class="flex flex-col mt-8">
    <div class="flex gap-4 pb-4">
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'all'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "all")}
        >
            All
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'scheduled'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "scheduled")}
        >
            Scheduled
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'completed'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "completed")}
        >
            Completed
        </button>

    </div>
    <div>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border">Appointment ID</th>
                    <th class="py-2 px-4 border">Patient Name</th>
                    <th class="py-2 px-4 border">Physician Name</th>
                    <th class="py-2 px-4 border">Secretary Name</th>
                    <th class="py-2 px-4 border">Date and Time</th>
                    <th class="py-2 px-4 border">Status</th>
                </tr>
            </thead>
            <tbody class="text-center">
                {#each appointments as appointment}
                    <tr>
                        <td class="py-2 px-4 border"
                            >{appointment.AppointmentID}</td
                        >
                        <td class="py-2 px-4 border"
                            >{appointment.PatientFirstName}
                            {appointment.PatientLastName}</td
                        >
                        <td class="py-2 px-4 border"
                            >{appointment.PhysicianFirstName}
                            {appointment.PhysicianLastName}</td
                        >
                        <td class="py-2 px-4 border"
                            >{appointment.SecretaryFirstName}
                            {appointment.SecretaryLastName}</td
                        >
                        <td class="py-2 px-4 border"
                            >{appointment.AppointmentDateTime}</td
                        >
                        <td class="py-2 px-4 border status-tag 
                                )}"
                            ><span class="status-tag {getStatusClass(appointment.AppointmentsStatus)}">{appointment.AppointmentsStatus}</span></td
                        >
                    </tr>
                {/each}
            </tbody>
        </table>
    </div>
</div>
