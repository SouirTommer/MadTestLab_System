<script>
    import SectionWrapper from "../../components/SectionWrapper.svelte";
    import Header from "../../components/Header.svelte";
    import StaffProfile from "../../components/StaffProfile.svelte";
    import Appointment from "../../components/StaffAppointment.svelte";
    import PatientOrder from "../../components/StaffCheckOrders.svelte";
    import StaffDashBoard from "../../components/StaffDashBoard.svelte";
    import StaffBilling from "../../components/StaffBilling.svelte";
    import NewAppointment from "../../components/StaffCreateAppointment.svelte";
    import PatientResults from "../../components/StaffCheckResults.svelte";
    import StaffInsurance from "../../components/StaffInsurance.svelte";
    import StaffTestCatalog from "../../components/StaffTestCatalog.svelte";
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";
    import {
        handleLogout,
        getCookie,
        deleteAllCookies,
    } from "../../lib/api.js";

    let currentTab = "dashboard"; // Tracks the active tab
    let user = {
        name: "",
        id: "",
        role: "",
    };
    onMount(() => {
        const username = getCookie("username");
        const role = getCookie("role");

        if (!username || role !== "Secretary") {
            goto("/");
        } else {
            user.name = getCookie("username");
            user.role = getCookie("role");
            user.id = getCookie("accountId");
        }
    });

    function logout() {
        showAlertBox("Logging out...", "#f44336");
        handleLogout(goto);
    }

    function showAlertBox(message, backgroundColor) {
        const alertBox = document.createElement("div");
        document.body.appendChild(alertBox);
        setTimeout(() => {
            document.body.removeChild(alertBox);
        }, 2000);
        alertBox.textContent = message;
        alertBox.style.position = "fixed";
        alertBox.style.bottom = "10rem";
        alertBox.style.left = "50%";
        alertBox.style.transform = "translateX(-50%)";
        alertBox.style.backgroundColor = backgroundColor || "#4caf50";
        alertBox.style.color = "white";
        alertBox.style.padding = "10px 20px";
        alertBox.style.borderRadius = "5px";
        alertBox.style.zIndex = "1000";
    }
</script>

<div class="flex h-screen overflow-x-hidden">
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
            <p class="text-lg font-semibold text-slate-600">Secretary Portal</p>
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
            <p class="text-slate-500 text-m font-medium px-2">Administration</p>
            <button
                class="navItem {currentTab === 'appointments'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "appointments"}
                on:click={() => (currentTab = "appointments")}
            >
                <i class="fa-regular fa-calendar-check"></i>
                Appointments
            </button>
            <button
                class="navItem {currentTab === 'patient_orders'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "patient_orders"}
                on:click={() => (currentTab = "patient_orders")}
            >
                <i class="fa-solid fa-file-medical"></i>
                Patient Orders
            </button>

            <button
                class="navItem {currentTab === 'patient_results'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "patient_results"}
                on:click={() => (currentTab = "patient_results")}
            >
                <i class="fa-solid fa-vials"></i> Patient Results
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
            <p class="text-slate-500 text-m font-medium px-2">Information</p>

            <button
                class="navItem {currentTab === 'testcatalog'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "testcatalog"}
                on:click={() => (currentTab = "testcatalog")}
            >
                <i class="fa-solid fa-flask-vial"></i> Test Catalog
            </button>
            <button
                class="navItem {currentTab === 'insurance'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "insurance"}
                on:click={() => (currentTab = "insurance")}
            >
                <i class="fa-solid fa-book-medical"></i>
                Insurance Plans
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
            <!-- push the div to bottom -->

            <div class="flex-1"></div>
            <button
                class="navItem flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                on:click={() => logout()}
            >
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Logout
            </button>
            <p class="text-slate-500 font-medium text-xl px-4 py-1">
                {user.role}
            </p>
            <div
                class="userCard text-slate-600 h-18 mb-4 mx-1 py-4 px-6 rounded-2xl border-solid border-2 border-slate-300 trasition"
            >
                <p class="text-xl">{user.name}</p>
                <p class="text-slate-400">User ID: {user.id}</p>
            </div>
        </nav>
    </aside>

    <SectionWrapper>
        <main class="flex-1 ml-72 p-6 overflow-y-auto min-w-[1400px]">
            <div class="flexs items-center gap-2 mb-6 py-4">
                <h1 class="text-4xl font-bold pl-2">
                    <i class="fa-solid fa-wrench text-slate-600"></i> Admin Panel
                </h1>
            </div>

            {#if currentTab === "dashboard"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Dashboard</h2>
                    <p>
                        Welcome to your patient portal. Here’s an overview of
                        your recent activity.
                    </p>
                    <StaffDashBoard />
                </div>
            {:else if currentTab === "appointments"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Appointments</h2>
                    <p>View and manage appointments for patients.</p>
                    <Appointment />
                </div>
            {:else if currentTab === "patient_orders"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Patient Orders</h2>
                    <p>View and manage patient Orders.</p>
                    <PatientOrder />
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
                    <StaffBilling />
                </div>
            {:else if currentTab === "patient_results"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Patients Results</h2>
                    <p>View and manage patient results.</p>
                    <PatientResults />
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
                    <StaffProfile />
                </div>
            {:else if currentTab === "testcatalog"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Test Catalog</h2>
                    <p>
                        View all the tests available in the lab and their
                        details.
                    </p>
                    <StaffTestCatalog />
                </div>
            {:else if currentTab === "insurance"}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Insurance Plans</h2>
                    <p>View insurance plans and patient coverage.</p>
                    <StaffInsurance />
                </div>
            {:else}
                <div
                    in:fade={{ delay: 201, duration: 200 }}
                    out:fade={{ duration: 200, easing: cubicInOut }}
                >
                    <h2 class="text-3xl font-bold mb-4">Opps !</h2>
                    <p>
                        Looks like you are lost. Please select a tab from the
                        sidebar.
                    </p>
                </div>
            {/if}
        </main>
    </SectionWrapper>
</div>
