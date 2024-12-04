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
    let filter = "scheduled";
    let showModal = false;
    let selectedAppointment = {};

    const tests = [
        { code: "1", name: "Complete Blood Count" },
        { code: "2", name: "Lipid Panel" },
        { code: "3", name: "Basic Metabolic Panel" },
        { code: "4", name: "Urinalysis" },
        { code: "5", name: "Thyroid Function Test" },
        { code: "6", name: "X-Ray" },
        { code: "7", name: "MRI Scan" },
        { code: "8", name: "COVID-19 PCR Test" },
    ];

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
            case "cancelled":
                return "bg-red-200 text-red-800";
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
                "http://localhost:8080/database/Physician/physician_create_order_action.php",
                {
                    method: "POST",
                    credentials: "include", // Include credentials (cookies) with the request
                    body: formData,
                },
            );
            // for (let [key, value] of formData.entries()) {
            //     console.log(`${key}: ${value}`);
            // }
            const result = await response.json();
            console.log("Create appointment response:", result); // Debugging statement
            if (result.status === "success") {
                alert("Appointment created successfully");
                closeModal(); // Call the onClose function to close the modal

                location.reload();
            } else {
                alert("Failed to create appointment: " + result.message);
            }
        } catch (error) {
            console.error("Error creating appointment:", error);
            alert("Error creating appointment. Please try again.");
        }
        console.log("Form submitted");
    }
</script>

<div class="flex flex-col mt-8">
    <div class="flex gap-4 pb-4">
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
                                {appointment.SecretaryLastName}
                                {appointment.SecretaryId}</td
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
                                {#if appointment.AppointmentsStatus === "Scheduled"}
                                    <button
                                        class="px-4 py-2 text-indigo-400 text-3xl"
                                        on:click={() =>
                                            handleButtonClick(appointment)}
                                        aria-label="Create Order"
                                    >
                                        <i
                                            class="fa-regular fa-square-plus hover:text-indigo-600"
                                        ></i>
                                    </button>
                                {:else}
                                    <button
                                        class="px-4 py-2 text-slate-600 text-3xl"
                                        aria-label="Cannot Create Order"
                                    >
                                        <i
                                            class="fa-regular fa-square-minus "
                                        ></i>
                                    </button>
                                {/if}
                         
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    {/if}
</div>

<div
    in:fade={{ duration: 200, easing: cubicInOut }}
    out:fade={{ duration: 200, easing: cubicInOut }}
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
                <h2 class="text-xl font-semibold mb-4">Create Test Order</h2>
                <form on:submit={handleSubmit} class="space-y-4">
                    <div>
                        <label
                            for="appointmentId"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Appointment ID:</strong></label
                        >
                        <input
                            type="text"
                            name="appointmentID"
                            id="appointmentId"
                            value={selectedAppointment.AppointmentID}
                            readonly
                            class="py-2 px-4 mt-1 block w-full bg-slate-100 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="patientName"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Patient Name:</strong></label
                        >
                        <input
                            type="text"
                            name="patient"
                            id="patientName"
                            value={`${selectedAppointment.PatientFirstName} ${selectedAppointment.PatientLastName}`}
                            readonly
                            class="py-2 px-4 mt-1 block w-full bg-slate-100 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="physicianName"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Physician Name:</strong></label
                        >
                        <input
                            type="text"
                            id="physicianName"
                            value={`${selectedAppointment.PhysicianFirstName} ${selectedAppointment.PhysicianLastName}`}
                            readonly
                            class="py-2 px-4 mt-1 block w-full bg-slate-100 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="secretaryName"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Secretary Name:</strong></label
                        >
                        <input
                            type="text"
                            name="secretary"
                            id="secretaryName"
                            value={`${selectedAppointment.SecretaryFirstName} ${selectedAppointment.SecretaryLastName}`}
                            readonly
                            class="py-2 px-4 mt-1 block w-full bg-slate-100 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="orderDateTime"
                            class="block text-sm font-medium  text-gray-700"
                            ><strong>Test Order Date Time:</strong></label
                        >
                        <input
                            type="datetime-local"
                            name="orderDateTime"
                            id="orderDateTime"
                            value=""
                            class="py-2 px-4 mt-1 block w-full  border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="orderStatus"
                            class=" text-sm font-medium text-gray-700"
                            ><strong>Order Status:</strong></label
                        >
                        <select
                            name="orderStatus"
                            id="orderStatus"
                            class="py-2 px-4  mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="" disabled selected>-- Select a Status --</option>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="test"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Test:</strong></label
                        >
                        <div>
                            <label
                                for="testCode"
                                class="block text-sm font-medium text-gray-700"
                            ></label>
                            <select
                                id="testCode"
                                name="testCode"
                                class="py-2 px-4  mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            >
                                <option value="" disabled selected>-- Select a Test --</option>
                                {#each tests as test}
                                    <option value={test.code}
                                        >{test.name}</option
                                    >
                                {/each}
                            </select>
                        </div>
                        <div class="pt-8 mt-4 w-full flex justify-center">
                            <button
                                type="submit"
                                name="submit"
                                class="px-10 py-2 bg-indigo-500 text-white rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >Create Test Order</button
                            >
                        </div>
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
