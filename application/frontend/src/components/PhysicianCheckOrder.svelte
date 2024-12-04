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
    let filter = "all";
    let insurances = [];
    let testsCatalog = [];

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
                "http://localhost:8080/database/Secretary/secretary_read_order_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            console.log("Fetched data:", data); // Debugging: Log fetched data
            orders = data.orders;
            insurances = data.insurances;
            testsCatalog = data.testsCatalog;
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
        switch (status) {
            case "Pending":
                return "bg-yellow-200 text-yellow-800";
            case "Completed":
                return "bg-green-200 text-green-800";
            case "In Progress":
                return "bg-blue-200 text-blue-800";
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
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "all";
                filterOrders();
            }}
        >
            All
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
            'In Progress'
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
    </div>
    {#if filteredOrders.length === 0}
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
                        <th class="border px-4 py-2">Insurance Name</th>
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
                            <td class="py-2 px-4 border"
                                >{order.InsuranceName}</td
                            >
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    {/if}
</div>
