<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";

    let orders = []; // Reactive variable to store fetched orders
    let allOrders = [];
    let pendingOrders = [];
    let inProgressOrders = [];
    let completedOrders = [];
    let filteredOrders = [];
    let filter = "in progress";

    let insurance = [];

    let showModal = false;
    let selectedOrder = {};

    // orders = [
    //     {
    //         OrderID: "101",
    //         PatientFirstName: "John",
    //         PatientLastName: "Doe",
    //         LabStaffFirstName: "Jane",
    //         LabStaffLastName: "Smith",
    //         SecretaryFirstName: "Alice",
    //         SecretaryLastName: "Johnson",
    //         TestName: "Complete Blood Count",
    //         OrderDateTime: "2023-10-01 10:00",
    //         OrderStatus: "Pending",
    //         InsuranceName: "HealthPlus Insurance",
    //     },
    //     {
    //         OrderID: "102",
    //         PatientFirstName: "Mary",
    //         PatientLastName: "Jane",
    //         LabStaffFirstName: "Robert",
    //         LabStaffLastName: "Brown",
    //         SecretaryFirstName: "Emily",
    //         SecretaryLastName: "Davis",
    //         TestName: "Lipid Panel",
    //         OrderDateTime: "2023-10-02 11:00",
    //         OrderStatus: "Completed",
    //         InsuranceName: "FamilyCare Insurance",
    //     },
    //     {
    //         OrderID: "103",
    //         PatientFirstName: "Alice",
    //         PatientLastName: "Johnson",
    //         LabStaffFirstName: "Emily",
    //         LabStaffLastName: "Davis",
    //         SecretaryFirstName: "Michael",
    //         SecretaryLastName: "Clark",
    //         TestName: "Basic Metabolic Panel",
    //         OrderDateTime: "2023-10-03 12:00",
    //         OrderStatus: "Completed",
    //         InsuranceName: "Senior Health Insurance",
    //     },
    // ];
    // categorizeOrders();
    // filterOrders();

    onMount(() => {
        fetchOrders(); // Fetch orders when the component is mounted
    });

    async function fetchOrders() {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Pathologist/pathologist_read_order_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            console.log("Fetched data:", data); // Debugging: Log fetched data
            orders = data.orders;
            insurance = data.insurance;
            categorizeOrders();
            filterOrders();
        } catch (error) {
            console.error("Error fetching orders:", error);
        }
    }

    function categorizeOrders() {
        allOrders = orders;

        pendingOrders = orders.filter(
            (order) => order.OrderStatus.toLowerCase() === "pending",
        );
        completedOrders = orders.filter(
            (order) => order.OrderStatus.toLowerCase() === "completed",
        );
        inProgressOrders = orders.filter(
            (order) => order.OrderStatus.toLowerCase() === "in progress",
        );
    }

    function filterOrders() {
        switch (filter) {
            case "all":
                filteredOrders = allOrders;
                break;
            case "pending":
                filteredOrders = pendingOrders;
                break;
            case "in progress":
                filteredOrders = inProgressOrders;
                break;
            case "completed":
                filteredOrders = completedOrders;
                break;
            default:
                filteredOrders = allOrders;
        }
    }

    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case "pending":
                return "bg-yellow-200 text-yellow-800";
            case "completed":
                return "bg-green-200 text-green-800";
            case "in progress":
                return "bg-blue-200 text-blue-800";
            default:
                return "bg-gray-200 text-gray-800";
        }
    }

    function handleButtonClick(order) {
        selectedOrder = order;
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
                "http://localhost:8080/database/Pathologist/pathologist_create_result_action.php",
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
            'in progress'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "in progress";
                filterOrders();
            }}
        >
            In Progress
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'pending'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "pending";
                filterOrders();
            }}
        >
            Pending
        </button>

        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'completed'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "completed";
                filterOrders();
            }}
        >
            Completed
        </button>

        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'all'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "all";
                filterOrders();
            }}
        >
            All
        </button>
    </div>
    {#if filteredOrders.length === 0}
        <h1
            class="pt-56 text-center text-2xl text-gray-800"
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
            <i class="fa-solid fa-magnifying-glass text-4xl pr-4"></i> No orders
            found
        </h1>
    {:else}
        <div
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
            <table class="min-w-full bg-white border border-slate-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Order ID</th>
                        <th class="border px-4 py-2">Patient Name</th>
                        <th class="border px-4 py-2">Lab Staff Name</th>
                        <th class="border px-4 py-2">Secretary Name</th>
                        <th class="border px-4 py-2">Test Name</th>
                        <th class="border px-4 py-2">Order DateTime</th>
                        <th class="border px-4 py-2">Order Status</th>
                        <th class="border px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {#each filteredOrders as order}
                        <tr>
                            <td class="py-2 px-4 border">{order.OrderID}</td>
                            <td class="py-2 px-4 border"
                                >{order.PatientFirstName}
                                {order.PatientLastName}</td
                            >
                            <td class="py-2 px-4 border"
                                >{order.LabStaffFirstName}
                                {order.LabStaffLastName}</td
                            >
                            <td class="py-2 px-4 border"
                                >{order.SecretaryFirstName}
                                {order.SecretaryLastName}</td
                            >
                            <td class="py-2 px-4 border">{order.TestName}</td>
                            <td class="py-2 px-4 border"
                                >{order.OrderDateTime}</td
                            >
                            <td class="py-2 px-4 border">
                                <span
                                    class="status-tag {getStatusClass(
                                        order.OrderStatus,
                                    )}">{order.OrderStatus}</span
                                >
                            </td>
                            <td class="py-2 px-4 border">
                                {#if order.OrderStatus === "In Progress"}
                                    <button
                                        class="px-4 py-2 text-indigo-400 text-3xl"
                                        on:click={() =>
                                            handleButtonClick(order)}
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
                                        <i class="fa-regular fa-square-minus"
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
                <h2 class="text-xl font-semibold mb-4">Submit Result</h2>
                <form on:submit={handleSubmit} class="space-y-4">
                    <div>
                        <label
                            for="orderID"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Order ID:</strong></label
                        >
                        <input
                            type="text"
                            name="orderID"
                            id="orderID"
                            value={selectedOrder.OrderID}
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
                            id="patientName"
                            value={`${selectedOrder.PatientFirstName} ${selectedOrder.PatientLastName}`}
                            readonly
                            class="py-2 px-4 mt-1 block w-full bg-slate-100 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="physicianName"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Lab Staff Name:</strong></label
                        >
                        <input
                            type="text"
                            id="physicianName"
                            value={`${selectedOrder.LabStaffFirstName} ${selectedOrder.LabStaffLastName}`}
                            readonly
                            class="py-2 px-4 mt-1 block w-full bg-slate-100 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>

                    <div>
                        <label
                            for="Result Status"
                            class=" text-sm font-medium text-gray-700"
                            ><strong>Report Status:</strong></label
                        >
                        <select
                            name="resultStatus"
                            id="resultStatus"
                            class="py-2 px-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option value="" disabled selected
                                >-- Select a Status --</option
                            >
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="reportURL"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Report URL:</strong></label
                        >
                        <input
                            type="text"
                            name="reportURL"
                            id="reportURL"
                            placeholder="Enter the URL of the report"
                            class="py-2 px-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                    <div>
                        <label
                            for="interpretation"
                            class="block text-sm font-medium text-gray-700"
                            ><strong>Interpretation:</strong></label
                        >
                        <textarea
                            name="interpretation"
                            id="interpretation"
                            placeholder="Enter the interpretation of the result"
                            class="py-2 px-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        ></textarea>
                    <div class="pt-8 mt-4 w-full flex justify-center">
                        <button
                            type="submit"
                            name="submit"
                            class="px-10 py-2 bg-indigo-500 text-white rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >Submit Result</button
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
