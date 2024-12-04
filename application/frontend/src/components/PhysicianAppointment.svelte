<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";
    import Modal from "./Modal.svelte";

    let appointments = []; // Reactive variable to store fetched orders
    let patients = [];
    let physicians = [];
    let allAppointments = []; // All appointments
    let scheduledAppointments = []; // Scheduled appointments
    let completedAppointments = []; // Completed appointments
    let filteredAppointments = []; // Reactive variable to store filtered appointments
    let filter = "all";
    let showModal = false;
    let selectedAppointment = {};

    onMount(async () => {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Physician/physician_read_appointment_action.php",
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
            default:
                filteredAppointments = allAppointments;
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
    function handleButtonClick(appointment) {
        selectedAppointment = appointment;
        showModal = true;
    }

    function closeModal() {
        showModal = false;
    }


    async function handleSubmit(event) {
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
        console.log('Form submitted:', selectedAppointment);
        closeModal();
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
            No orders found
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
                                class="py-2 px-4 border
                                )}"
                                ><span
                                    class="status-tag {getStatusClass(
                                        appointment.AppointmentsStatus,
                                    )}">{appointment.AppointmentsStatus}</span
                                ></td
                            >
                            <td class="py-2 px-4 border">
                                <button
                                    class="px-4 py-2 text-indigo-400 text-3xl"
                                    on:click={() =>
                                        handleButtonClick(appointment)}
                                    aria-label="Open appointment details"
                                >
                                    <i
                                        class="fa-regular fa-square-plus hover:fa-solid"
                                    ></i>
                                </button>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    {/if}
</div>

<div
    in:fade={{ duration: 300, easing: cubicInOut }}
    out:fade={{ duration: 300, easing: cubicInOut }}
>
    {#if showModal}
        <div
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            in:fade={{ duration: 300 }}
            out:fade={{ duration: 300 }}
        >
            <div
                class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative"
            >
                <button
                    class="absolute top-2 right-2 text-gray-500"
                    on:click={closeModal}
                >
                    &times;
                </button>
                <h2>Appointment Details</h2>
                <form on:submit={handleSubmit}>
                    <div>
                        <label for="appointmentId"
                            ><strong>Appointment ID:</strong></label
                        >
                        <input
                            type="text"
                            id="appointmentId"
                            value={selectedAppointment.AppointmentID}
                            readonly
                        />
                    </div>
                    <div>
                        <label for="patientName"
                            ><strong>Patient Name:</strong></label
                        >
                        <input
                            type="text"
                            id="patientName"
                            value={`${selectedAppointment.PatientFirstName} ${selectedAppointment.PatientLastName}`}
                            readonly
                        />
                    </div>
                    <div>
                        <label for="physicianName"
                            ><strong>Physician Name:</strong></label
                        >
                        <input
                            type="text"
                            id="physicianName"
                            value={`${selectedAppointment.PhysicianFirstName} ${selectedAppointment.PhysicianLastName}`}
                            readonly
                        />
                    </div>
                    <div>
                        <label for="secretaryName"
                            ><strong>Secretary Name:</strong></label
                        >
                        <input
                            type="text"
                            id="secretaryName"
                            value={`${selectedAppointment.SecretaryFirstName} ${selectedAppointment.SecretaryLastName}`}
                            readonly
                        />
                    </div>
                    <div>
                        <label for="appointmentDateTime"
                            ><strong>Appointment DateTime:</strong></label
                        >
                        <input
                            type="text"
                            id="appointmentDateTime"
                            value={selectedAppointment.AppointmentDateTime}
                            readonly
                        />
                    </div>
                    <div>
                        <label for="status"><strong>Status:</strong></label>
                        <select id="status" bind:value={selectedAppointment.AppointmentsStatus}>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded"
                            >Submit</button
                        >
                    </div>
                </form>
            </div>
        </div>
    {/if}
</div>

<style>
    .fixed {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .bg-white {
        background: white;
    }
    .rounded-lg {
        border-radius: 8px;
    }
    .shadow-lg {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
    .p-6 {
        padding: 1.5rem;
    }
    .w-full {
        width: 100%;
    }
    .max-w-lg {
        max-width: 32rem;
    }
    .relative {
        position: relative;
    }
    .absolute {
        position: absolute;
    }
    .top-2 {
        top: 0.5rem;
    }
    .right-2 {
        right: 0.5rem;
    }
    .text-gray-500 {
        color: #6b7280;
    }
</style>
