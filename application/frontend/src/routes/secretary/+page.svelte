<script>
    import SectionWrapper from "../../components/SectionWrapper.svelte";
    import Header from "../../components/Header.svelte";
    import StaffProfile from "../../components/StaffProfile.svelte";
    let currentTab = "dashboard"; // Tracks the active tab

    let user = {
        name: "Marco",
        email: "stse2122@gmail.com",
    };
</script>

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside
        class="w-72 text-white flex flex-col border-r items-start bg-indigo-400 bg-opacity-15"
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
                class="navItem {currentTab === 'patient_records'
                    ? 'selected'
                    : ''} flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "patient_records"}
                on:click={() => (currentTab = "patient_records")}
            >
                <i class="fa-solid fa-book-medical"></i>
                Patient Records
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
            <p class="text-slate-500 font-medium text-xl px-4 py-1">
                Secretary Staff
            </p>
            <div
                class="userCard text-slate-600 h-18 mb-4 mx-1 py-4 px-6 rounded-2xl border-solid border-2 border-slate-300 trasition"
            >
                <p class="text-xl">{user.name}</p>
                <p class="text-slate-400">{user.email}</p>
            </div>
        </nav>
    </aside>

    <SectionWrapper>
        <main class="flex-1 p-6">
            <div class="flex items-center gap-2 mb-6 py-4">
                <h1 class="text-4xl font-bold pl-2">
                    <i class="fa-solid fa-wrench text-slate-600"></i> Admin Panel
                </h1>
            </div>

            {#if currentTab === "dashboard"}
                <h2 class="text-3xl font-bold mb-4">Dashboard</h2>
                <p>
                    Welcome to your patient portal. Here’s an overview of your
                    recent activity.
                </p>
            {/if}
            {#if currentTab === "appointments"}
                <h2 class="text-3xl font-bold mb-4">Appointments</h2>
                <p>View and manage appointments for patients.</p>
            {/if}
            {#if currentTab === "patient_records"}
                <h2 class="text-3xl font-bold mb-4">Patient Records</h2>
                <p>View and manage patient records and history.</p>
            {/if}
            {#if currentTab === "billing"}
                <h2 class="text-3xl font-bold mb-4">Billing</h2>
                <p>Check your bills, make payments, or view payment history.</p>
            {/if}
            {#if currentTab === "staff_records"}
                <h2 class="text-3xl font-bold mb-4">Staff Records</h2>
                <p>View and manage staff records and history.</p>
            {/if}
            {#if currentTab === "profile"}
                <h2 class="text-3xl font-bold mb-4">Profile</h2>
                <p>Update your personal information and account settings.</p>
                <StaffProfile />
                
            {/if}
        </main>
    </SectionWrapper>
</div>
