<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";

    let orders = []; // Reactive variable to store fetched orders
    let insurances = [];
    let testsCatalog = [];
    let bloodTestsCatalog = [];
    let urineTestsCatalog = [];
    let imagingTestsCatalog = [];
    let molecularTestsCatalog = [];
    let filteredTestsCatalog = [];

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
            categorizeTestsCatalog();
            filterTestCatalog();
            // console.log(insurances[0].InsuranceName); //debug purpose
            // console.log(testsCatalog[0].TestName); //debug purpose
        } catch (error) {
            console.error("Error fetching orders:", error);
        }
    }

    function categorizeTestsCatalog() {
        bloodTestsCatalog = testsCatalog.filter(
            (test) => test.TestType.toLowerCase() === "blood test",
        );
        urineTestsCatalog = testsCatalog.filter(
            (test) => test.TestType.toLowerCase() === "urine test",
        );
        imagingTestsCatalog = testsCatalog.filter(
            (test) => test.TestType.toLowerCase() === "imaging test",
        );
        molecularTestsCatalog = testsCatalog.filter(
            (test) => test.TestType.toLowerCase() === "molecular test",
        );
    }

    function filterTestCatalog() {
        switch (filter) {
            case "all":
                filteredTestsCatalog = testsCatalog;
                break;
            case "blood test":
                filteredTestsCatalog = bloodTestsCatalog;
                break;
            case "urine test":
                filteredTestsCatalog = urineTestsCatalog;
                break;
            case "imaging test":
                filteredTestsCatalog = imagingTestsCatalog;
                break;
            case "molecular test":
                filteredTestsCatalog = molecularTestsCatalog;
                break;
            default:
                filteredTestsCatalog = testsCatalog;
        }
    }

    function getStatusClass(testType) {
        switch (testType.toLowerCase()) {
            case "blood test":
                return "bg-red-200 text-red-800";
            case "urine test":
                return "bg-yellow-200 text-yellow-800";
            case "imaging test":
                return "bg-blue-200 text-blue-800";
            case "molecular test":
                return "bg-green-200 text-green-800";
            default:
                return "bg-gray-200 text-gray-800";
        }
    }

    var filter = "all";
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
                filterTestCatalog();
            }}
        >
            All
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-red-200 hover:text-red-800 transition {filter ===
            'blood test'
                ? 'bg-red-200 text-red-800'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "blood test";
                filterTestCatalog();
            }}
        >
            Blood Test
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-yellow-200 hover:text-yellow-800 transition {filter ===
            'urine test'
                ? 'bg-yellow-200 text-yellow-800'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "urine test";
                filterTestCatalog();
            }}
        >
            Urine Test
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-blue-200 hover:text-blue-800 transition {filter ===
            'imaging test'
                ? 'bg-blue-200 text-blue-800'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "imaging test";
                filterTestCatalog();
            }}
        >
            Imaging Test
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-green-200 hover:text-green-800 transition {filter ===
            'molecular test'
                ? 'bg-green-200 text-green-800'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "molecular test";
                filterTestCatalog();
            }}
        >
            Molecular test
        </button>
    </div>
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
                {#each filteredTestsCatalog as test}
                    <tr>
                        <td class=" text-center py-2 px-4 border"
                            >{test.TestCode}</td
                        >
                        <td class=" py-2 px-4 border">{test.TestName}</td>
                        <td class=" py-2 px-4 border">{test.Description}</td>
                        <td class=" text-end py-2 px-4 border">{test.Price}</td>
                        <td class=" py-2 px-4 border"
                            ><span
                                class="status-tag {getStatusClass(
                                    test.TestType,
                                )}">{test.TestType}</span
                            ></td
                        >
                    </tr>
                {/each}
            </tbody>
        </table>
    </div>
</div>
