<script>
    import { onMount } from "svelte";

    let bills = [];
    let allBills = [];
    let pendingBills = [];
    let completedBills = [];
    let filteredBills = [];
    let filter = "all";

    //testing data
    // bills = [
    //     {
    //         BillID: "1",
    //         OrderID: "101",
    //         PatientFirstName: "John",
    //         PatientLastName: "Doe",
    //         LabStaffFirstName: "Jane",
    //         LabStaffLastName: "Smith",
    //         SecretaryFirstName: "Alice",
    //         SecretaryLastName: "Johnson",
    //         TestName: "Complete Blood Count",
    //         Amount: "45.00",
    //         PaymentStatus: "pending",
    //         BillDateTime: "2023-10-01 10:00",
    //         InsuranceName: "HealthPlus Insurance",
    //     },
    //     {
    //         BillID: "2",
    //         OrderID: "102",
    //         PatientFirstName: "Mary",
    //         PatientLastName: "Jane",
    //         LabStaffFirstName: "Robert",
    //         LabStaffLastName: "Brown",
    //         SecretaryFirstName: "Emily",
    //         SecretaryLastName: "Davis",
    //         TestName: "Lipid Panel",
    //         Amount: "30.00",
    //         PaymentStatus: "completed",
    //         BillDateTime: "2023-10-02 11:00",
    //         InsuranceName: "FamilyCare Insurance",
    //     },
    //     {
    //         BillID: "3",
    //         OrderID: "103",
    //         PatientFirstName: "Alice",
    //         PatientLastName: "Johnson",
    //         LabStaffFirstName: "Emily",
    //         LabStaffLastName: "Davis",
    //         SecretaryFirstName: "Michael",
    //         SecretaryLastName: "Clark",
    //         TestName: "Basic Metabolic Panel",
    //         Amount: "40.00",
    //         PaymentStatus: "pending",
    //         BillDateTime: "2023-10-03 12:00",
    //         InsuranceName: "Senior Health Insurance",
    //     },
    // ];
    // categorizeBills();
    // filterBills();
    //testing data

    onMount(async () => {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Secretary/secretary_read_bill_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            bills = data.bills;
            console.log("Fetched data:", data);
            if (bills === undefined || bills.length == 0) {
                console.log("No bills found");
            } else {
                categorizeBills();
                filterBills();
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    });

    function categorizeBills() {
        allBills = bills;
        pendingBills = bills.filter(
            (bill) => bill.PaymentStatus.toLowerCase() === "pending",
        );
        completedBills = bills.filter(
            (bill) => bill.PaymentStatus.toLowerCase() === "completed",
        );
    }

    function filterBills() {
        switch (filter) {
            case "all":
                filteredBills = allBills;
                break;
            case "pending":
                filteredBills = pendingBills;
                break;
            case "completed":
                filteredBills = completedBills;
                break;
            default:
                filteredBills = allBills;
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
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "all";
                filterBills();
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
                filterBills();
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
                filterBills();
            }}
        >
            Completed
        </button>
    </div>

    {#if filteredBills.length === 0}
        <h1
            class="pt-56 text-center text-2xl text-gray-800"
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
        <i class="fa-solid fa-magnifying-glass text-4xl pr-4 "></i> No Billing found
        </h1>
    {:else}
        <div
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
            <table class="min-w-full bg-white border border-slate-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Bill ID</th>
                        <th class="border px-4 py-2">Order ID</th>
                        <th class="border px-4 py-2">Patient Name</th>
                        <th class="border px-4 py-2">Lab Staff Name</th>
                        <th class="border px-4 py-2">Secretary Name</th>
                        <th class="border px-4 py-2">Test Name</th>
                        <th class="border px-4 py-2">Amount</th>
                        <th class="border px-4 py-2">Payment Status</th>
                        <th class="border px-4 py-2">Bill DateTime</th>
                        <th class="border px-4 py-2">Insurance Name</th>
                    </tr>
                </thead>
                <tbody>
                    {#each filteredBills as bill}
                        <tr>
                            <td class="py-2 px-4 border">{bill.BillID}</td>
                            <td class="py-2 px-4 border">{bill.OrderID}</td>
                            <td class="py-2 px-4 border"
                                >{bill.PatientFirstName}
                                {bill.PatientLastName}</td
                            >
                            <td class="py-2 px-4 border"
                                >{bill.LabStaffFirstName}
                                {bill.LabStaffLastName}</td
                            >
                            <td class="py-2 px-4 border"
                                >{bill.SecretaryFirstName}
                                {bill.SecretaryLastName}</td
                            >
                            <td class="py-2 px-4 border">{bill.TestName}</td>
                            <td class="py-2 px-4 border">{bill.Amount}</td>
                            <td class="py-2 px-4 border">
                                <span
                                    class="status-tag {getStatusClass(
                                        bill.PaymentStatus,
                                    )}">{bill.PaymentStatus}</span
                                ></td
                            >
                            <td class="py-2 px-4 border">{bill.BillDateTime}</td
                            >
                            <td class="py-2 px-4 border"
                                >{bill.InsuranceName}</td
                            >
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    {/if}
</div>
