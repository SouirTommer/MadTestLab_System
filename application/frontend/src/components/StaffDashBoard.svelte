<script>
    let todayAppointments = [
        {
            id: 1,
            patient: "John Doe",
            time: "10:00 AM",
            info: "Age: 30, Type of Test: Blood Test, Phone: 123-456-7890",
        },
        {
            id: 2,
            patient: "Jane Smith",
            time: "11:00 AM",
            info: "Age: 25, Type of Test: Urine Test, Phone: 234-567-8901",
        },
        {
            id: 3,
            patient: "Michael Johnson",
            time: "12:00 PM",
            info: "Age: 45, Type of Test: X-Ray, Phone: 345-678-9012",
        },
        {
            id: 4,
            patient: "Emily Davis",
            time: "1:00 PM",
            info: "Age: 35, Type of Test: MRI, Phone: 456-789-0123",
        },
        {
            id: 5,
            patient: "David Wilson",
            time: "2:00 PM",
            info: "Age: 50, Type of Test: CT Scan, Phone: 567-890-1234",
        },
        {
            id: 6,
            patient: "Sarah Brown",
            time: "3:00 PM",
            info: "Age: 28, Type of Test: Blood Test, Phone: 678-901-2345",
        },
    ];

    let tomorrowAppointments = [
        {
            id: 1,
            patient: "Alice Johnson",
            time: "9:00 AM",
            info: "Age: 40, Condition: Diabetes, Phone: 123-456-7890",
        },
        {
            id: 2,
            patient: "Bob Brown",
            time: "10:30 AM",
            info: "Age: 50, Condition: Hypertension, Phone: 234-567-8901",
        },
        {
            id: 3,
            patient: "Michael Johnson",
            time: "12:00 PM",
            info: "Age: 45, Condition: Flu, Phone: 345-678-9012",
        },
        {
            id: 4,
            patient: "Emily Davis",
            time: "1:00 PM",
            info: "Age: 35, Condition: Checkup, Phone: 456-789-0123",
        },
        {
            id: 5,
            patient: "David Wilson",
            time: "2:00 PM",
            info: "Age: 50, Condition: Diabetes, Phone: 567-890-1234",
        },
        {
            id: 6,
            patient: "Sarah Brown",
            time: "3:00 PM",
            info: "Age: 28, Condition: Hypertension, Phone: 678-901-2345",
        },
        {
            id: 7,
            patient: "James Taylor",
            time: "4:00 PM",
            info: "Age: 60, Condition: Flu, Phone: 789-012-3456",
        },
        {
            id: 8,
            patient: "Patricia Anderson",
            time: "5:00 PM",
            info: "Age: 55, Condition: Checkup, Phone: 890-123-4567",
        },
        {
            id: 9,
            patient: "Robert Thomas",
            time: "6:00 PM",
            info: "Age: 40, Condition: Diabetes, Phone: 901-234-5678",
        },
        {
            id: 10,
            patient: "Linda Jackson",
            time: "7:00 PM",
            info: "Age: 65, Condition: Hypertension, Phone: 012-345-6789",
        },
    ];

    let selectedPatientInfo = "";

    function viewPatientInfo(info) {
        selectedPatientInfo = info;
    }

    function newAppointment() {
        alert("New Appointment button clicked");
    }

    function closePatientInfo() {
        selectedPatientInfo = "";
    }
</script>

<div class="flex flex-col gap-4 p-6 w-full">
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
            class="flex flex-col gap-6 p-6 h-fit w-full max-w-[400px] h-auto bg-white rounded-lg shadow-lg border-solid border-2 border-slate-200"
        >
            <h2 class="text-xl font-semibold text-slate-700">
                Today's Appointments
            </h2>
            {#each todayAppointments as appointment}
                <div class="flex justify-between w-full items-center">
                    <span>{appointment.patient}</span>
                    <span>{appointment.time}</span>
                    <button
                        class="text-indigo-400"
                        on:click={() => viewPatientInfo(appointment.info)}
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
                <div class="flex justify-between w-full items-center text-end">
                    <span>{appointment.patient}</span>
                    <span class=" text-end">{appointment.time}</span>
                    <button
                        class="text-indigo-400"
                        on:click={() => viewPatientInfo(appointment.info)}
                    >
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            {/each}
        </div>

        {#if selectedPatientInfo}
            <div
                class="flex flex-col gap-6 p-6 w-full  h-fit max-w-[400px] bg-white rounded-lg shadow-lg border-solid border-2 border-slate-200 relative"
            >
                <button
                    class="absolute top-2 right-2 text-slate-600"
                    on:click={closePatientInfo}
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <h2 class="text-xl font-semibold text-slate-700">
                    Patient Info
                </h2>
                <p class="text-slate-600">{selectedPatientInfo}</p>
            </div>
        {/if}
    </div>
</div>
