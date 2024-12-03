<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";

    var filter = "all";

    let Results = []; // Reactive variable to store fetched orders

    onMount(() => {
        fetchOrders(); // Fetch orders when the component is mounted
    });

    async function fetchOrders() {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Patient/patient_read_result_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            console.log("Fetched data:", data); // Debugging: Log fetched data
            Results = data.data;
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
</script>

<div class="flex flex-col mt-8">
    <div class="flex gap-4 pb-4">
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'all'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "all")}
        >
            All
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'pending'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "pending")}
        >
            Pending
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'completed'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "completed")}
        >
            Completed
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'inprogress'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (filter = "inprogress")}
        >
            In Progress
        </button>
    </div>
    <div>
        <table class="min-w-full bg-white border border-slate-200">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Result ID</th>
                    <th class="border px-4 py-2">Order ID</th>
                    <th class="border px-4 py-2">Report URL</th>
                    <th class="border px-4 py-2">Interpretation</th>
                    <th class="border px-4 py-2">Result Date and Time</th>
                    <th class="border px-4 py-2">Result Status</th>
                    <th class="border px-4 py-2">Lab Staff First Name</th>
                    <th class="border px-4 py-2">Lab Staff Last Name</th>
                </tr>
            </thead>
            <tbody>
                {#each Results as result}
                    <tr>
                        <td class="border px-4 py-2">{result.ResultID}</td>
                        <td class="border px-4 py-2">{result.OrderID}</td>
                        <td class="border px-4 py-2"
                            ><a href={result.ReportURL} target="_blank"
                                >View Report</a
                            ></td
                        >
                        <td class="border px-4 py-2">{result.Interpretation}</td
                        >
                        <td class="border px-4 py-2">{result.ResultDateTime}</td
                        >
                        <td
                            class="border px-4 py-2 {getStatusClass(
                                result.ResultStatus,
                            )}">{result.ResultStatus}</td
                        >

                        <td class="border px-4 py-2"
                            >{result.LabStaffFirstName}</td
                        >
                        <td class="border px-4 py-2"
                            >{result.LabStaffLastName}</td
                        >
                    </tr>
                {/each}
            </tbody>
        </table>
    </div>
</div>
