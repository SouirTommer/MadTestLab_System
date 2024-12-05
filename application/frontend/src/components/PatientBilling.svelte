<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import SectionWrapper from "./SectionWrapper.svelte";
    import { onMount } from "svelte";

    let selectedTab = "outstanding"; // Default selected tab

    let paymentHistory = []; // Reactive variable to store fetched payments

    onMount(() => {
        fetchBillingInfo(); // Fetch billing information when the component is mounted
    });

    async function fetchBillingInfo() {
        try {
            const response = await fetch(
                "http://localhost:8080/database/Patient/patient_read_bill_action.php",
                {
                    credentials: "include", // Include credentials (cookies) with the request
                },
            );
            const data = await response.json();
            console.log("Fetched data:", data); // Debugging: Log fetched data
            paymentHistory = data.data; // Store fetched payments in reactive variable
            if (data.status === "success") {
                // Separate outstanding payments and payment history

                paymentHistory = data.data.filter(
                    (payment) => payment.PaymentStatus !== "Outstanding",
                );
                paymentHistory.sort((a, b) => new Date(b.BillDateTime) - new Date(a.BillDateTime));
            }
        } catch (error) {
            console.error("Error fetching billing info:", error);
        }
    }
</script>

<div class="flex flex-col h-full">
    <div class="flex gap-4 p-4"></div>

    <div class="flex flex-col flex-1 items-start pb-10">
        <div
            class="flex flex-col gap-6 p-6 w-[800px] rounded-lg border-solid border-2 border-slate-200 transition"
            in:fade={{ delay: 150, duration: 150 }}
            out:fade={{ duration: 150, easing: cubicInOut }}
        >
            <div>
                <h1 class="text-xl font-semibold">Payment History</h1>
                <p class="text-slate-500 font-normal">
                    Here is your payment history.
                </p>
            </div>
            {#each paymentHistory as payment}
                <div
                    class="flex flex-col gap-2 p-6 bg-white shadow-lg rounded-lg transition-transform duration-300 transform hover:-translate-y-1"
                >
                    <div class="flex justify-between">
                        <span class="font-bold text-lg"
                            >{payment.BillDateTime}</span
                        >
                    </div>
                    <hr class="border-slate-400" />
                    <div class="px-4 ">
                        <div class="flex justify-between border-b pt-2">
                            <span class="font-medium">Payment ID:</span>
                            <span>{payment.BillID}</span>
                        </div>
                        <div class="flex justify-between border-b pt-2">
                            <span class="font-medium">Order ID:</span>
                            <span>{payment.OrderID}</span>
                        </div>
                        <div class="flex justify-between border-b pt-2">
                            <span class="font-medium">Amount:</span>
                            <span>{payment.Amount}</span>
                        </div>
                        <div class="flex justify-between border-b pt-2">
                            <span class="font-medium">Test Name:</span>
                            <span>{payment.TestName}</span>
                        </div>
                        <div class="flex justify-between border-b pt-2">
                            <span class="font-medium">Insurance:</span>
                            <span>{payment.InsuranceName}</span>
                        </div>
                    </div>
                </div>
            {/each}
        </div>
    </div>
</div>
