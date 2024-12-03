<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import SectionWrapper from "./SectionWrapper.svelte";
    let selectedTab = "outstanding"; // Default selected tab

    let staff = {
        id: "12345",
        name: "Tommer",
        role: "Lab Secretary",
        email: "souirTOmmer@gmail.com",
        phone: "54329876",
        password: "password123", // Note: In a real application, never store passwords in plain text
    };

    let outstandingPayments = [
        { id: 1, amount: "$200", dueDate: "2023-10-01" },
        { id: 2, amount: "$150", dueDate: "2023-11-01" },
    ];

    let paymentHistory = [
        { id: 1, amount: "$100", date: "2023-09-01" },
        { id: 2, amount: "$50", date: "2023-08-01" },
    ];

    console.log("Billing component loaded");
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
                        <span>Payment ID: {payment.id}</span>
                        <span>Amount: {payment.amount}</span>
                        <span>Due Date: {payment.dueDate}</span>
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
                        <span>Payment ID: {payment.id}</span>
                        <span>Amount: {payment.amount}</span>
                        <span>Date: {payment.date}</span>
                    </div>
                {/each}
            </div>
        {/if}
    </div>
</div>
