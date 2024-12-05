<script>
    import { onMount } from "svelte";
    import { cubicInOut } from "svelte/easing";
    import { fade } from "svelte/transition";

    let bills = [];
    let allBills = [];
    let cashBills = [];
    let creditCardBills = [];
    let alipayBills = [];
    let wechatPayBills = [];
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
            bills = data;
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
        cashBills = bills.filter(
            (bill) => bill.PaymentStatus.toLowerCase() === "cash",
        );
        creditCardBills = bills.filter(
            (bill) => bill.PaymentStatus.toLowerCase() === "credit card",
        );
        alipayBills = bills.filter(
            (bill) => bill.PaymentStatus.toLowerCase() === "alipay",
        );
        wechatPayBills = bills.filter(
            (bill) => bill.PaymentStatus.toLowerCase() === "wechat pay",
        );
    }

    function sortResultsByDate() {
        bills.sort(
            (a, b) => new Date(b.BillDateTime) - new Date(a.BillDateTime),
        );
    }

    function filterBills() {
        switch (filter) {
            case "all":
                filteredBills = allBills;
                sortResultsByDate();
                break;
            case "cash":
                filteredBills = cashBills;
                sortResultsByDate();
                break;
            case "credit card":
                filteredBills = creditCardBills;
                sortResultsByDate();
                break;
            case "alipay":
                filteredBills = alipayBills;
                sortResultsByDate();
                break;
            case "wechat pay":
                filteredBills = wechatPayBills;
                sortResultsByDate();
                break;
        }
    }

    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case "cash":
                return "bg-blue-200 text-blue-800";
            case "credit card":
                return "bg-purple-200 text-purple-800";
            case "alipay":
                return "bg-teal-200 text-teal-800";
            case "wechat pay":
                return "bg-red-200 text-red-800";
            default:
                return "bg-gray-200 text-gray-800";
        }
    }

    function getPaymentTag(status) {
        switch (status.toLowerCase()) {
            case "cash":
                return "<i class='text-xl fa-solid fa-money-bill-wave'></i>";
            case "credit card":
                return "<i class='text-xl fa-regular fa-credit-card'></i>";
            case "alipay":
                return "<i class='text-xl fa-brands fa-alipay'></i>";
            case "wechat pay":
                return "<i class='text-xl fa-brands fa-weixin'></i>";
            default:
                return "<i class='text-xl fa-solid fa-money-bill-wave'></i>";
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
            'cash'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "cash";
                filterBills();
            }}
        >
            Cash
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'credit card'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "credit card";
                filterBills();
            }}
        >
            Credit Card
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'alipay'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "alipay";
                filterBills();
            }}
        >
            Alipay
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {filter ===
            'wechat pay'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transparent text-slate-600'}"
            on:click={() => {
                filter = "wechat pay";
                filterBills();
            }}
        >
            Wechat Pay
        </button>
    </div>

    {#if filteredBills.length === 0}
        <h1
            class="pt-56 text-center text-2xl text-gray-800"
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
            <i class="fa-solid fa-magnifying-glass text-4xl pr-4"></i> No Billing
            found
        </h1>
    {:else}
        <div
            in:fade={{ delay: 200, duration: 200 }}
            out:fade={{ duration: 200, easing: cubicInOut }}
        >
            <table
                class="text-center min-w-full bg-white border border-slate-200"
            >
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Bill ID</th>
                        <th class="border px-4 py-2">Order ID</th>
                        <th class="border px-4 py-2">Patient Name</th>
                        <th class="border px-4 py-2">Lab Staff Name</th>
                        <th class="border px-4 py-2">Secretary Name</th>
                        <th class="border px-4 py-2">Test Name</th>
                        <th class="border px-4 py-2">Insurance Name</th>
                        <th class="border px-4 py-2">Bill DateTime</th>
                        <th class="border px-4 py-2">Amount</th>
                        <th class="border px-4 py-2">Payment </th>
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
                            <td class="py-2 px-4 border"
                                >{bill.InsuranceName}</td
                            >
                            <td class="py-2 px-4 border">{bill.BillDateTime}</td
                            >
                            <td class="py-2 px-4 border">{bill.Amount}</td>
                            <td class="py-2 px-4 border">
                                <span
                                    class="status-tag {getStatusClass(
                                        bill.PaymentStatus,
                                    )}"
                                    >{@html getPaymentTag(
                                        bill.PaymentStatus,
                                    )}</span
                                >
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    {/if}
</div>
