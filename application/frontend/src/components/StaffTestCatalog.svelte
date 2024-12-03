<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";

    let orders = []; // Reactive variable to store fetched orders
    let insurances = [];
    let testsCatalog = [];
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
            testsCatalog = data.testsCatalog;
            // console.log(insurances[0].InsuranceName); //debug purpose
            // console.log(testsCatalog[0].TestName); //debug purpose
        } catch (error) {
            console.error("Error fetching orders:", error);
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

    var filter = "all";
</script>

<div class="flex flex-col mt-8">
    <div>
        <table class="min-w-full bg-white border border-slate-200">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Test Code</th>
                    <th class="border px-4 py-2">Test Name</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Price</th>
                    <th class="border px-4 py-2">Test Type</th>
                </tr>
            </thead>
            <tbody>
                {#each testsCatalog as test}
                    <tr>
                        <td class="text-center py-2 px-4 border-b">{test.TestCode}</td>
                        <td class="py-2 px-4 border-b">{test.TestName}</td>
                        <td class="py-2 px-4 border-b">{test.Description}</td>
                        <td class="text-end py-2 px-4 border-b">{test.Price}</td>
                        <td class="py-2 px-4 border-b">{test.TestType}</td>
                    </tr>
                {/each}
            </tbody>
        </table>
    </div>
</div>