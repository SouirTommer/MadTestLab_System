<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import SectionWrapper from "./SectionWrapper.svelte";
    import { onMount } from "svelte";

    let selectedTab = "outstanding"; // Default selected tab

    let outstandingPayments = []; // Reactive variable to store outstanding payments
    let paymentHistory = []; // Reactive variable to store payment history

    onMount(() => {
        fetchBillingInfo(); // Fetch billing information when the component is mounted
    });

    async function fetchBillingInfo() {
        try {
            const response = await fetch("http://localhost:8080/database/Patient/patient_read_bill_action.php", {
                credentials: 'include' // Include credentials (cookies) with the request
            });
            const data = await response.json();
            console.log('Fetched data:', data); // Debugging: Log fetched data

            if (data.status === 'success') {
                // Separate outstanding payments and payment history
                outstandingPayments = data.data.filter(payment => payment.PaymentStatus === 'Outstanding');
                paymentHistory = data.data.filter(payment => payment.PaymentStatus !== 'Outstanding');
            }
        } catch (error) {
            console.error('Error fetching billing info:', error);
        }
    }

</script>

<div class="flex flex-col h-full">
    <div class="flex gap-4 p-6">
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {selectedTab ===
            'outstanding'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (selectedTab = "outstanding")}
        >
            Outstanding Payment
        </button>
        <button
            class="px-4 py-2 rounded-lg font-semibold hover:bg-slate-100 transition {selectedTab ===
            'history'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (selectedTab = "history")}
        >
            Payment History
        </button>
    </div>

    <div class="flex flex-col flex-1 items-start pb-10">
        {#if selectedTab === "outstanding"}
            <div
                class="flex flex-col gap-6 p-6 w-[800px] rounded-lg border-solid border-2 border-slate-200 transition" 
                in:fade={{ delay: 150, duration: 150 }}
                out:fade={{ duration: 150, easing: cubicInOut }}
            >
                <div>
                    <h1 class="text-xl font-semibold">Outstanding Payments</h1>
                    <p class="text-slate-500 font-normal">
                        Here are your outstanding payments.
                    </p>
                </div>
                {#each outstandingPayments as payment}
                    <div class="flex justify-between w-full">
                        <span>Payment ID: {payment.BillID}</span>
                        <span>Order ID: {payment.OrderID}</span>
                        <span>Amount: {payment.Amount}</span>
                        <span>Due Date: {payment.BillDateTime}</span>
                        <span>Test Name: {payment.TestName}</span>
                        <span>Insurance: {payment.InsuranceName}</span>
                    </div>
                {/each}
            </div>
        {:else if selectedTab === "history"}
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
                    <div class="flex justify-between w-full">
                        <span>Payment ID: {payment.BillID}</span>
                        <span>Order ID: {payment.OrderID}</span>
                        <span>Amount: {payment.Amount}</span>
                        <span>Date: {payment.BillDateTime}</span>
                        <span>Test Name: {payment.TestName}</span>
                        <span>Insurance: {payment.InsuranceName}</span>
                    </div>
                {/each}
            </div>
        {/if}
    </div>
</div>
