<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount, afterUpdate } from "svelte";

    let results = [];
    let allResults = [];
    let pendingResults = [];
    let completedResults = [];
    let filteredResults = [];
    let filter = "all";

    //testing data
    // results = [
    //         {
    //             ResultID: "1",
    //             OrderID: "101",
    //             ReportURL: "http://example.com/report1",
    //             Interpretation: "Normal",
    //             ResultDateTime: "2023-10-01 10:00",
    //             ResultStatus: "Pending",
    //             PatientFirstName: "John",
    //             PatientLastName: "Doe",
    //             LabStaffFirstName: "Jane",
    //             LabStaffLastName: "Smith"
    //         },
    //         {
    //             ResultID: "2",
    //             OrderID: "102",
    //             ReportURL: "http://example.com/report2",
    //             Interpretation: "Abnormal",
    //             ResultDateTime: "2023-10-02 11:00",
    //             ResultStatus: "Completed",
    //             PatientFirstName: "Mary",
    //             PatientLastName: "Jane",
    //             LabStaffFirstName: "Robert",
    //             LabStaffLastName: "Brown"
    //         },
    //         {
    //             ResultID: "3",
    //             OrderID: "103",
    //             ReportURL: "http://example.com/report3",
    //             Interpretation: "Normal",
    //             ResultDateTime: "2023-10-03 12:00",
    //             ResultStatus: "Pending",
    //             PatientFirstName: "Alice",
    //             PatientLastName: "Johnson",
    //             LabStaffFirstName: "Emily",
    //             LabStaffLastName: "Davis"
    //         }
    //     ];
    //     categorizeResults();
    //     filterResults();
    //testing data
    onMount(async () => {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Secretary/secretary_read_result_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            results = data;
            console.log("Fetched data:", data);
            if (results === undefined || results.length == 0) {
                console.log("No results found");
            } else {
                categorizeResults();
                filterResults();
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });

    function categorizeResults() {
        allResults = results;
        pendingResults = results.filter(
            (result) => result.ResultStatus.toLowerCase() === "pending",
        );
        completedResults = results.filter(
            (result) => result.ResultStatus.toLowerCase() === "completed",
        );
    }
    function sortResultsByDate() {
        filteredResults.sort(
            (a, b) => new Date(b.ResultDateTime) - new Date(a.ResultDateTime),
        );
    }

    function filterResults() {
        switch (filter) {
            case "all":
                filteredResults = allResults;
                sortResultsByDate();
                break;
            case "inprogress":
                filteredResults = pendingResults;
                sortResultsByDate();
                break;
            case "completed":
                filteredResults = completedResults;
                sortResultsByDate();
                break;
            default:
                filteredResults = allResults;
                sortResultsByDate();
        }
    }

    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case "pending":
                return "bg-yellow-200 text-yellow-800";
            case "completed":
                return "bg-green-200 text-green-800";
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
            on:click={() => {
                filter = "all";
                filterResults();
            }}
        >
            All
        </button>
    </div>

    {#if filteredResults.length === 0}
        <h1
            class="pt-56 text-center text-2xl text-gray-800"
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
            <i class="fa-solid fa-magnifying-glass text-4xl pr-4"></i> No Results
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
                        <th class="border px-4 py-2">Result ID</th>
                        <th class="border px-4 py-2">Order ID</th>
                        <th class="border px-4 py-2">Report URL</th>
                        <th class="border px-4 py-2">Interpretation</th>
                        <th class="border px-4 py-2">Result DateTime</th>
                        <th class="border px-4 py-2">Result Status</th>
                        <th class="border px-4 py-2">Patient Name</th>
                        <th class="border px-4 py-2">Lab Staff Name</th>
                    </tr>
                </thead>
                <tbody>
                    {#each filteredResults as result}
                        <tr>
                            <td class="py-2 px-4 border">{result.ResultID}</td>
                            <td class="py-2 px-4 border">{result.OrderID}</td>
                            <td class="py-2 px-4 border"
                                ><a href={result.ReportURL} target="_blank"
                                    >View Report</a
                                ></td
                            >
                            <td class="py-2 px-4 border"
                                >{result.Interpretation}</td
                            >
                            <td class="py-2 px-4 border"
                                >{result.ResultDateTime}</td
                            >
                            <td class="py-2 px-4 border"
                                ><span
                                    class="status-tag {getStatusClass(
                                        result.ResultStatus,
                                    )}">{result.ResultStatus}</span
                                ></td
                            >
                            <td class="py-2 px-4 border"
                                >{result.PatientFirstName}
                                {result.PatientLastName}</td
                            >
                            <td class="py-2 px-4 border"
                                >{result.LabStaffFirstName}
                                {result.LabStaffLastName}</td
                            >
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    {/if}
</div>
