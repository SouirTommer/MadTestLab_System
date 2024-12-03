<script>
    import SectionWrapper from "../../components/SectionWrapper.svelte";
    import Header from "../../components/Header.svelte";
    import PatientProfile from "../../components/PatientProfile.svelte";
    import Orders from "../../components/PatientOrders.svelte";
    import Results from "../../components/PatientResults.svelte";
    import Billing from "../../components/PatientBilling.svelte";
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";
    import {
        handleLogout,
        getCookie,
        deleteAllCookies,
    } from "../../lib/api.js";

    let user = {
        name: "",
        id: "",
        role: "",
    };

    onMount(() => {
        const username = getCookie("username");
        const role = getCookie("role");

        if (!username || role !== "Patient") {
            goto("/");
        } else {
            user.name = getCookie("username");
            user.role = getCookie("role");
            user.id = getCookie("accountId");
        }
    });

    let currentTab = "dashboard"; // Tracks the active tab

    function getGreetingWithIcon() {
        const now = new Date();
        const hour = now.getHours();

        if (hour >= 5 && hour < 12) {
            return { greeting: "Good Morning", icon: "fa-sun" };
        } else if (hour >= 12 && hour < 18) {
            return { greeting: "Good Afternoon", icon: "fa-sun" };
        } else {
            return { greeting: "Good Evening", icon: "fa-moon" };
        }
    }

    const { greeting, icon } = getGreetingWithIcon();
</script>

<div
    class="flex h-screen overflow-x-hidden"
    in:fade={{ delay: 200, duration: 200 }}
    out:fade={{ duration: 200, easing: cubicInOut }}
>
    <!-- Sidebar -->
    <aside
        class="w-72 text-white flex flex-col border-r items-start bg-indigo-400 bg-opacity-15 fixed top-0 left-0 h-screen"
    >
        <div class="p-6 pt-10">
            <h1 class="font-semibold text-3xl">
                <span class="text-indigo-400"> MedTest </span>
                <span class="text-slate-600">Lab </span>
                <i class=" text-slate-600 fa-solid fa-vial"></i>
            </h1>
            <p class="text-lg font-semibold text-slate-600">Patient Portal</p>
        </div>
        <nav class="flex flex-col gap-2 px-2 mt-8 w-full h-full">
            <button
                class="navItem {currentTab === 'test_order'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "dashboard"}
                on:click={() => (currentTab = "dashboard")}
            >
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </button>
            <p class="text-slate-500 text-m font-medium px-2">My Test</p>
            <button
                class="navItem {currentTab === 'test_order'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "test_order"}
                on:click={() => (currentTab = "test_order")}
            >
                <i class="fa-solid fa-list-check"></i>

                Test Orders
            </button>
            <button
                class="navItem {currentTab === 'test_results'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "test_results"}
                on:click={() => (currentTab = "test_results")}
            >
                <i class="fa-solid fa-vials"></i>
                Test Results
            </button>
            <p class="text-slate-500 text-m font-medium px-2">Setting</p>

            <button
                class="navItem {currentTab === 'profile'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "profile"}
                on:click={() => (currentTab = "profile")}
            >
                <i class="fa-solid fa-user"></i>
                Profile
            </button>

            <button
                class="navItem {currentTab === 'billing'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "billing"}
                on:click={() => (currentTab = "billing")}
            >
                <i class="fa-solid fa-wallet"></i>
                Billing
            </button>
            <!-- push the div to bottom -->
            <div class="flex-1"></div>
            <button
                class="navItem flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                on:click={() => handleLogout(goto)}
            >
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Logout
            </button>
            <div
                class="userCard text-slate-600 h-18 mb-4 mx-1 py-4 px-6 rounded-2xl border-solid border-2 border-slate-300 trasition"
            >
                <p class="text-xl">{user.name}</p>
                <p class="text-slate-400">User ID: {user.id}</p>
            </div>
        </nav>
    </aside>

    <SectionWrapper>
        <main class="flex-1 p-6 ml-72 overflow-y-auto">
            <div class="flex items-center gap-2 mb-6 py-4">
                <i class={`fa-solid ${icon} text-yellow-500 text-4xl`}></i>
                <h1 class="text-4xl font-bold pl-2">
                    {greeting} , {user.name} !
                </h1>
            </div>

            {#if currentTab === "dashboard"}
                <div
                    in:fade={{ delay: 200, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Dashboard</h2>
                    <p>
                        Welcome to your patient portal. Here’s an overview of
                        your recent activity.
                    </p>
                </div>
            {:else if currentTab === "test_order"}
                <div
                    in:fade={{ delay: 200, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Test Order</h2>
                    <p>Order new tests or view your pending test orders.</p>
                    <Orders />
                </div>
            {:else if currentTab === "test_results"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Test Results</h2>
                    <p>View your test results and download reports.</p>
                    <Results />
                </div>
            {:else if currentTab === "billing"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Billing</h2>
                    <p>
                        Check your bills, make payments, or view payment
                        history.
                    </p>
                    <Billing />
                </div>
            {:else if currentTab === "profile"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Profile</h2>
                    <p>
                        Update your personal information and account settings.
                    </p>
                    <PatientProfile />
                </div>
            {:else}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Dashboard</h2>
                    <p>Seems like you're lost. Click on the tabs to navigate</p>
                </div>
            {/if}
        </main>
    </SectionWrapper>
</div>
