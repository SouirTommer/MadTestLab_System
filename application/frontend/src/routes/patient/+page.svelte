<script>
    import SectionWrapper from "../../components/SectionWrapper.svelte";
    import Header from "../../components/Header.svelte";
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

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside
        class="w-72 text-white flex flex-col border-r items-center bg-indigo-400 bg-opacity-15"
    >
        <div class="p-6 pt-10">
            <h1 class="font-semibold text-3xl">
                <span class="text-indigo-400"> MedTest </span>
                <span class="text-slate-600">Lab </span>
                <i class=" text-slate-600 fa-solid fa-vial"></i>
            </h1>
            <p class="text-lg font-semibold text-slate-600">Patient Portal</p>
        </div>
        <nav class="flex flex-col gap-4 px-2 mt-8 w-full">
            <button
                class="flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "dashboard" && "bg-"}
                on:click={() => (currentTab = "dashboard")}
            >
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </button>
            <p class="text-slate-500 text-m font-medium px-4">My Health</p>
            <p class="text-slate-500 text-m font-medium px-4">Setting</p>
           
            <button
                class="flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
                class:selected={currentTab === "Profile"}
                on:click={() => (currentTab = "profile")}
            >
                <i class="fa-solid fa-user"></i>
                Profile
            </button>

            <button
            class="flex w-full items-center gap-2 text-left text-lg rounded-lg navTabBtn text-slate-600 transition"
            class:selected={currentTab === "Billing"}
            on:click={() => (currentTab = "billing")}
        >
            <i class="fa-solid fa-wallet"></i>
            Billing
        </button>
        </nav>
    </aside>

    <SectionWrapper>
        <main class="flex-1 p-6">
            <div class="flex items-center gap-2 mb-6 py-4">
                <i class={`fa-solid ${icon} text-yellow-500 text-4xl`}></i>
                <h1 class="text-4xl font-bold pl-2">{greeting} , Tommer !</h1>
            </div>

            {#if currentTab === "dashboard"}
                <h2 class="text-3xl font-bold mb-4">Dashboard</h2>
                <p>
                    Welcome to your patient portal. Here’s an overview of your
                    recent activity.
                </p>
            {/if}
            {#if currentTab === "tests"}
                <h2 class="text-3xl font-bold mb-4">My Tests</h2>
                <p>View and manage your medical tests here.</p>
            {/if}
            {#if currentTab === "appointments"}
                <h2 class="text-3xl font-bold mb-4">Appointments</h2>
                <p>Schedule or modify your sample collection appointments.</p>
            {/if}
            {#if currentTab === "billing"}
                <h2 class="text-3xl font-bold mb-4">Billing</h2>
                <p>Check your bills, make payments, or view payment history.</p>
            {/if}
            {#if currentTab === "profile"}
                <h2 class="text-3xl font-bold mb-4">Profile</h2>
                <p>Update your personal information and account settings.</p>
            {/if}
        </main>
    </SectionWrapper>
</div>
