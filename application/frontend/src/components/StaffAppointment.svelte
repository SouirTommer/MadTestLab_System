<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount, afterUpdate } from "svelte";
    import StaffCreateAppointment from "./StaffCreateAppointment.svelte";

    let appointments = []; // Reactive variable to store fetched orders
    let patients = [];
    let physicians = [];
    let allAppointments = []; // All appointments
    let todayAppointment = [];
    let scheduledAppointments = []; // Scheduled appointments
    let completedAppointments = []; // Completed appointments
    let filteredAppointments = []; // Reactive variable to store filtered appointments
    let filter = "today";

    let showModal = false; // State variable to control modal visibility

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
            appointments = data.appointments;
            console.log("Fetched data:", data);
            categorizeAppointments();
            filterAppointments();
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });

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
        filterTodayAppointments();
    }

    function sortAppointmentsByDate() {
        filteredAppointments.sort(
            (a, b) =>
                new Date(b.AppointmentDateTime) -
                new Date(a.AppointmentDateTime),
        );
    }

    function filterTodayAppointments() {
        const today = new Date().toISOString().split("T")[0];
        todayAppointment = appointments.filter(
            (appointment) =>
                appointment.AppointmentDateTime.split(" ")[0] === today,
        );
    }

    function filterAppointments() {
        switch (filter) {
            case "all":
                filteredAppointments = allAppointments;

                break;
            case "scheduled":
                filteredAppointments = scheduledAppointments;

                break;
            case "completed":
                filteredAppointments = completedAppointments;

                break;
            case "today":
                filteredAppointments = todayAppointment;

                break;
            default:
                filteredAppointments = allAppointments;
        }
        sortAppointmentsByDate();
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

    function newAppointment() {
        showModal = true; // Show the modal when the button is clicked
    }

    function closePatientInfo() {
        selectedPatientAppointment = "";
        showPatientInfo = false;
    }

    function closeModal() {
        showModal = false; // Close the modal
    }
</script>

<div class="flex flex-col mt-8">
    <div class="flex justify-start">
        <div
            class="flex flex-col gap-6 p-6 w-full max-w-[800px] bg-white rounded-lg shadow-lg border-solid border-2 border-slate-200 cursor-pointer"
            on:click={newAppointment}
            role="button"
            tabindex="0"
            on:keydown={(e) => e.key === "Enter" && newAppointment()}
        >
            <div class="flex items-start gap-4">
                <i class="fa-solid fa-plus text-indigo-400 text-2xl"></i>
                <div>
                    <h2 class="text-xl font-semibold text-slate-700">
                        New Appointment
                    </h2>
                    <p class="text-slate-500">
                        Create new appointment for the patient in the future
                        schedule
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-4 pb-4 mt-10">
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'today'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => {
                filter = "today";
                filterAppointments();
            }}
        >
            Today
        </button>
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
<!-- Modal for creating an appointment -->
{#if showModal}
    <div
        in:fade={{ duration: 300, easing: cubicInOut }}
        out:fade={{ duration: 300, easing: cubicInOut }}
    >
        <StaffCreateAppointment onClose={closeModal} />
    </div>
{/if}
