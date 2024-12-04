<script>
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import SectionWrapper from "./SectionWrapper.svelte";
    var selectedTab = "pgeneral";

    export let user = {};
</script>

<div class="flex flex-row mt-8">
    <div class="flex flex-col gap-2 items-start pb-10 md:pb-14 mr-20">
        <button
            class="items-start text-start text-slate-600 font-medium sm:text-md md:text-md py-3 rounded-2xl w-full hover:bg-slate-200 transition
             {selectedTab === 'pgeneral'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (selectedTab = "pgeneral")}
        >
            General
        </button>
        <button
            class=" text-start text-slate-600 font-medium sm:text-md md:text-md py-3 w-full items-start rounded-2xl hover:bg-slate-100 transition
             {selectedTab === 'pauthentication'
                ? 'bg-slate-200 text-slate-600'
                : 'bg-transapraent text-slate-600'}"
            on:click={() => (selectedTab = "pauthentication")}
        >
            Authentication
        </button>
    </div>

    <div class="flex flex-col flex-1 items-start pb-10 transition">
        {#if selectedTab === "pgeneral"}
            <div
                class="flex flex-col gap-6 p-6 w-[800px] rounded-lg border-solid border-2 border-slate-200"
                in:fade={{ delay: 150, duration: 150 }}
                out:fade={{ duration: 150, easing: cubicInOut }}
            >
                <div>
                    <h1 class="text-xl font-semibold">My information</h1>
                    <p class="text-slate-500 font-normal">
                        Manage your personal information
                    </p>
                </div>

                <div class="flex flex-col">
                    <label
                        for="username"
                        class="text-md font-medium text-slate-600"
                    >
                        Username
                    </label>
                    <input
                        readonly
                        id="username"
                        type="text"
                        placeholder="Enter your username"
                        value={user.name}
                        class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    />
                </div>
                <div class="flex flex-col">
                    <label
                        for="role"
                        class="text-md font-medium text-slate-600"
                    >
                        User ID
                    </label>
                    <input
                        readonly
                        id="role"
                        type="text"
                        placeholder="Enter your Date of Birth"
                        value={user.id}
                        class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    />
                </div>
                <div class="flex flex-col">
                    <label
                        for="role"
                        class="text-md font-medium text-slate-600"
                    >
                        Role
                    </label>
                    <input
                        readonly
                        id="role"
                        type="text"
                        placeholder="Enter your Date of Birth"
                        value={user.role}
                        class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    />
                </div>
            </div>
        {:else if selectedTab === "pauthentication"}
            <div
                class="flex flex-col gap-6 p-6 w-[800px] rounded-lg border-solid border-2 border-slate-200"
                in:fade={{ delay: 150, duration: 150 }}
                out:fade={{ duration: 150, easing: cubicInOut }}
            >
                <div>
                    <h1 class="text-xl font-semibold">Authentication</h1>
                    <p class="text-slate-500 font-normal">
                        Manage your Authentication Credentials
                    </p>
                </div>

                <div class="flex flex-col">
                    <label
                        for="old-password"
                        class="text-md font-medium text-slate-600"
                    >
                        Old Password
                    </label>
                    <input
                        id="old-password"
                        type="password"
                        placeholder="Enter your old password"
                        class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    />
                </div>
                <div class="flex flex-col">
                    <label
                        for="new-password"
                        class="text-md font-medium text-slate-600"
                    >
                        New Password
                    </label>
                    <input
                        id="new-password"
                        type="password"
                        placeholder="Enter your new password"
                        class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    />
                </div>
                <div class="flex flex-col">
                    <label
                        for="confirm-new-password"
                        class="text-md font-medium text-slate-600"
                    >
                        Confirm New Password
                    </label>
                    <input
                        id="confirm-new-password"
                        type="password"
                        placeholder="Confirm your new password"
                        class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    />
                </div>
            </div>
        {/if}
    </div>
</div>
