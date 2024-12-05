<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";

    let appointments = []; // Reactive variable to store fetched orders
    let allAppointments = []; // All appointments
    let scheduledAppointments = []; // Scheduled appointments
    let completedAppointments = []; // Completed appointments
    let filteredAppointments = []; // Reactive variable to store filtered appointments
    let filter = "scheduled";
    let showModal = false;
    let selectedAppointment = {};

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
            categorizeAppointments();
            filterAppointments();
            console.log("Appointments:", appointments);
        } catch (error) {
            console.error("Error fetching orders:", error);
        }
    }

    function categorizeAppointments() {
        allAppointments = appointments;
        scheduledAppointments = appointments.filter(
            (appointment) =>
                appointment.AppointmentsStatus.toLowerCase() === "scheduled",
        );
        completedAppointments = appointments.filter(
            (appointment) =>
                appointment.AppointmentsStatus.toLowerCase() === "completed",
        );
    }


    function sortAppointmentsByDate() {
        filteredAppointments.sort(
            (a, b) => new Date(b.AppointmentDateTime) - new Date(a.AppointmentDateTime),
        );
    }

    function filterAppointments() {
        switch (filter) {
            case "all":
                filteredAppointments = allAppointments;
                sortAppointmentsByDate();
                break;
            case "scheduled":
                filteredAppointments = scheduledAppointments;
                sortAppointmentsByDate();
                break;
            case "completed":
                filteredAppointments = completedAppointments;
                sortAppointmentsByDate();
                break;
            default:
                filteredAppointments = allAppointments;
                sortAppointmentsByDate();
        }
    }

    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case "scheduled":
                return "bg-yellow-200 text-yellow-800";
            case "completed":
                return "bg-green-200 text-green-800";
            default:
                return "bg-gray-200 text-gray-800";
        }
    }
</script>

<div class="flex flex-col mt-8">
    <div class="flex gap-4 pb-4">
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'all'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => {
                filter = "all";
                filterAppointments();
            }}
        >
            All
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'scheduled'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => {
                filter = "scheduled";
                filterAppointments();
            }}
        >
            Scheduled
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'completed'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => {
                filter = "completed";
                filterAppointments();
            }}
        >
            Completed
        </button>
    </div>

    {#if filteredAppointments.length === 0}
        <h1
            class="pt-56 text-center text-2xl text-gray-800"
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
            <i class="fa-solid fa-magnifying-glass text-4xl pr-4"></i> No Appointment
            found
        </h1>
    {:else}
        <div
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
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
                    {#each filteredAppointments as appointment}
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
                            <td
                                class="py-2 px-4 border status-tag
                                )}"
                                ><span
                                    class="status-tag {getStatusClass(
                                        appointment.AppointmentsStatus,
                                    )}">{appointment.AppointmentsStatus}</span
                                ></td
                            >
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    {/if}
</div>
