<script>
    import { cubicInOut } from "svelte/easing";
    import { fade } from "svelte/transition";
    import { tick } from "svelte";
    import { onMount } from "svelte";
    import StaffCreateAppointment from "./StaffCreateAppointment.svelte";
    import { get } from "svelte/store";

    let appointments = [];
    let todayAppointments = [];
    let tomorrowAppointments = [];
    let patients = [];
    let physicians = [];
    let selectedPatientAppointment = "";
    let showPatientInfo = false;
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
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });

    function categorizeAppointments() {
        todayAppointments = appointments
            .filter(
                (appointment) =>
                    new Date(appointment.AppointmentDateTime).toDateString() ===
                    new Date().toDateString(),
            )
            .sort(
                (a, b) =>
                    new Date(a.AppointmentDateTime) -
                    new Date(b.AppointmentDateTime),
            );
        tomorrowAppointments = appointments
            .filter(
                (appointment) =>
                    new Date(appointment.AppointmentDateTime).toDateString() ===
                    new Date(
                        new Date().getTime() + 24 * 60 * 60 * 1000,
                    ).toDateString(),
            )
            .sort(
                (a, b) =>
                    new Date(a.AppointmentDateTime) -
                    new Date(b.AppointmentDateTime),
            );
    }

    function formatTime(dateTime) {
        const date = new Date(dateTime);
        return date.toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    function viewPatientInfo(appointment) {
        if (appointment === selectedPatientAppointment) {
            selectedPatientAppointment = "";
            showPatientInfo = false;
        } else {
            selectedPatientAppointment = appointment;
            showPatientInfo = true;
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

<div class="flex flex-col gap-4 p-6 w-full w-5xl">
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

    <hr class="border-slate-600 my-2 max-w-full" />

    <div class="flex flex-row gap-4">
        <div
            class="flex flex-col gap-6 p-6 h-fit w-full max-w-[400px] bg-white rounded-lg shadow-lg border-solid border-2 border-slate-200"
        >
            <h2 class="text-xl font-semibold text-slate-700">
                Today's Appointments
            </h2>
            {#each todayAppointments as appointment}
                <div
                    class="flex justify-between w-full items-center {selectedPatientAppointment ===
                    appointment
                        ? 'bg-slate-200'
                        : ''} rounded-lg"
                >
                    <span class="pl-4"
                        >{appointment.PatientFirstName}
                        {appointment.PatientLastName}</span
                    >
                    <span class="flex-1"></span>
                    <span>{formatTime(appointment.AppointmentDateTime)}</span>
                    <!-- svelte-ignore a11y_consider_explicit_label -->
                    <button
                        class="text-indigo-400 bg-transparent"
                        on:click={() => viewPatientInfo(appointment)}
                    >
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            {/each}
        </div>

        <div
            class="flex flex-col gap-6 p-6 h-fit w-full max-w-[400px] bg-white rounded-lg shadow-lg border-solid border-2 border-slate-200"
        >
            <h2 class="text-xl font-semibold text-slate-700">
                Tomorrow's Appointments
            </h2>
            {#each tomorrowAppointments as appointment}
                <div
                    class="flex justify-between w-full items-center {selectedPatientAppointment ===
                    appointment
                        ? 'bg-slate-200'
                        : ''} rounded-lg"
                >
                    <span class="pl-4"
                        >{appointment.PatientFirstName}
                        {appointment.PatientLastName}</span
                    >
                    <span class="flex-1"></span>
                    <span>{formatTime(appointment.AppointmentDateTime)}</span>
                    <!-- svelte-ignore a11y_consider_explicit_label -->
                    <button
                        class="text-indigo-400 bg-transparent"
                        on:click={() => viewPatientInfo(appointment)}
                    >
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            {/each}
        </div>
        {#if showPatientInfo}
            <div
                class="flex flex-col gap-6 p-6 w-full max-w-[400px] bg-white rounded-lg shadow-lg border-solid border-2 border-slate-200 relative"
                in:fade={{ duration: 200, easing: cubicInOut }}
                out:fade={{ duration: 200, easing: cubicInOut }}
            >
                <!-- svelte-ignore a11y_consider_explicit_label -->
                <button
                    class="absolute top-2 right-2 text-slate-600"
                    on:click={closePatientInfo}
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <h2 class="text-xl font-semibold text-slate-700">
                    Appointment Info
                </h2>
                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-user"></i>
                    <span class="pl-2">Patient Name:</span>
                    <span class="flex-1"></span>
                    <span class="ml-2"
                        >{selectedPatientAppointment.PatientFirstName}
                        {selectedPatientAppointment.PatientLastName}</span
                    >
                </p>
                <hr class="border-slate-200" />

                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-clock"></i>
                    <span class="pl-2">Time:</span>
                    <span class="flex-1"></span>
                    <span class="ml-2"
                        >{selectedPatientAppointment.AppointmentDateTime}</span
                    >
                </p>
                <hr class="border-slate-200" />
                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-info-circle"></i>
                    <span class="pl-2"> Status:</span>
                    <span class="flex-1"></span>
                    <span class="status-tag ml-40 {getStatusClass(selectedPatientAppointment.AppointmentsStatus)}"
                        >{selectedPatientAppointment.AppointmentsStatus}</span
                    >
                </p>
                <hr class="border-slate-200" />
                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-user-md"></i>
                    <span class="pl-2">Physician Name:</span>
                    <span class="flex-1"></span>
                    <span class="ml-2"
                        >{selectedPatientAppointment.PhysicianFirstName}
                        {selectedPatientAppointment.PhysicianLastName}</span
                    >
                </p>

                <hr class="border-slate-200" />
            </div>
        {/if}
    </div>
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
