<script>
    import { cubicInOut } from "svelte/easing";
    import { fade } from "svelte/transition";
    import { tick } from "svelte";
    import StaffCreateAppointment from "./StaffCreateAppointment.svelte";

    let todayAppointments = [
        {
            id: 1,
            patient: "John Doe",
            time: "10:00 AM",
            age: 30,
            typeOfTest: "Blood Test",
            phone: "123-456-7890",
        },
        {
            id: 2,
            patient: "Jane Smith",
            time: "11:00 AM",
            age: 25,
            typeOfTest: "Urine Test",
            phone: "234-567-8901",
        },
        {
            id: 3,
            patient: "Michael Johnson",
            time: "12:00 PM",
            age: 45,
            typeOfTest: "X-Ray",
            phone: "345-678-9012",
        },
        {
            id: 4,
            patient: "Emily Davis",
            time: "1:00 PM",
            age: 35,
            typeOfTest: "MRI",
            phone: "456-789-0123",
        },
        {
            id: 5,
            patient: "David Wilson",
            time: "2:00 PM",
            age: 50,
            typeOfTest: "CT Scan",
            phone: "567-890-1234",
        },
        {
            id: 6,
            patient: "Sarah Brown",
            time: "3:00 PM",
            age: 28,
            typeOfTest: "Blood Test",
            phone: "678-901-2345",
        },
    ];

    let tomorrowAppointments = [
        {
            id: 1,
            patient: "Alice Johnson",
            time: "9:00 AM",
            age: 40,
            condition: "Diabetes",
            phone: "123-456-7890",
        },
        {
            id: 2,
            patient: "Bob Brown",
            time: "10:30 AM",
            age: 50,
            condition: "Hypertension",
            phone: "234-567-8901",
        },
        {
            id: 3,
            patient: "Michael Johnson",
            time: "12:00 PM",
            age: 45,
            condition: "Flu",
            phone: "345-678-9012",
        },
        {
            id: 4,
            patient: "Emily Davis",
            time: "1:00 PM",
            age: 35,
            condition: "Checkup",
            phone: "456-789-0123",
        },
        {
            id: 5,
            patient: "David Wilson",
            time: "2:00 PM",
            age: 50,
            condition: "Diabetes",
            phone: "567-890-1234",
        },
        {
            id: 6,
            patient: "Sarah Brown",
            time: "3:00 PM",
            age: 28,
            condition: "Hypertension",
            phone: "678-901-2345",
        },
    ];

    let selectedPatientAppointment = "";
    let showPatientInfo = false;
    let showModal = false; // State variable to control modal visibility

    function viewPatientInfo(appointment) {
        if (appointment === selectedPatientAppointment) {
            selectedPatientAppointment = "";
            showPatientInfo = false;
        } else {
            selectedPatientAppointment = appointment;
            showPatientInfo = true;
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
                    <span class="pl-4">{appointment.patient}</span>
                    <span class="flex-1"></span>
                    <span>{appointment.time}</span>
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
                    <span class="pl-4">{appointment.patient}</span>
                    <span class="flex-1"></span>
                    <span>{appointment.time}</span>
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
                    Patient Info
                </h2>
                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-clock"></i>
                    <span class="pl-2">Patient Name:</span>
                    <span class="flex-1"></span>
                    <span class="ml-2"
                        >{selectedPatientAppointment.patient}</span
                    >
                </p>
                <hr class="border-slate-200" />

                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-clock"></i>
                    <span class="pl-2">Appointment Time:</span>
                    <span class="flex-1"></span>
                    <span class="ml-2">{selectedPatientAppointment.time}</span>
                </p>
                <hr class="border-slate-200" />
                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-user"></i>
                    <span class="pl-2">Age:</span>
                    <span class="flex-1"></span>
                    <span class="ml-2">{selectedPatientAppointment.age}</span>
                </p>
                <hr class="border-slate-200" />
                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-vial"></i>
                    <span class="pl-2">Type of Test:</span>
                    <span class="flex-1"></span>
                    <span class="ml-2"
                        >{selectedPatientAppointment.typeOfTest ||
                            selectedPatientAppointment.condition}</span
                    >
                </p>
                <hr class="border-slate-200" />
                <p class="text-slate-600 flex items-center gap-1 text-lg">
                    <i class="fa-solid fa-phone"></i>
                    <span class="pl-2">Phone:</span>
                    <span class="flex-1"></span>
                    <span class="ml-2">{selectedPatientAppointment.phone}</span>
                </p>
                <hr class="border-slate-200" />
            </div>
        {/if}
    </div>
 
</div>
   <!-- Modal for creating an appointment -->
   {#if showModal}
   <div in:fade={{ duration: 300, easing: cubicInOut }}
   out:fade={{ duration: 300, easing: cubicInOut }} >
   <StaffCreateAppointment  onClose={closeModal} />
</div>
{/if}