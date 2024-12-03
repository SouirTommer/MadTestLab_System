<script>
    import SectionWrapper from "../../components/SectionWrapper.svelte";
    import Header from "../../components/Header.svelte";
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import {onMount} from 'svelte';
    import { getCookie } from "../../lib/api.js";

    onMount(() => {
        const username = getCookie('username');
        const role = getCookie('role');

        if (username && role) {
            switch (role) {
                case 'Patient':
                    goto("/patient");
                    break;
                case 'Secretary':
                    goto("/secretary");
                    break;
                case 'LabStaff':
                    goto("/labstaff");
                    break;
                default:
                    break;
            }
        }
    });
    
    async function handleRegister(event) {
        event.preventDefault(); // Prevent page reload
        const formData = new FormData(event.target);

        try {
            console.log("Sending registration request...");
            const response = await fetch("http://localhost:8080/database/Account/register_action.php", {
                method: "POST",
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            console.log("Response received:", result);

            if (response.ok && result.status === 'success') {
                alert("Registration successful! Redirecting to login page...");
                goto("/login");
            } else {
                console.error("Registration failed:", result.message);
                alert("Registration failed. Please try again.");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        }
    }
</script>

<SectionWrapper>
    <Header />
    <main
        class="flex flex-col gap-10 flex-1 items-center justify-center pb-10 md:pb-14"
    >
        <h2
            class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl max-w-[1200px] mx-auto w-full text-center font-semibold"
        >
            Join <span class="text-indigo-400">MedTest</span>
            <span class=" text-slate-600">Lab</span> Today!
        </h2>
        <p
            class="text-lg sm:text-xl md:text-2xl text-center max-w-[800px] mx-auto w-full text-slate-600"
        >
            Create an account to access all your medical tests in one place.
        </p>
        <form
            on:submit={handleRegister}
            class="flex flex-col gap-6 w-full max-w-[400px] mx-auto bg-white p-8 rounded-lg shadow-lg"
        >
            <div class="flex flex-col gap-2">
                <label for="username" class="text-lg font-medium text-slate-700"
                    >Username</label
                >
                <input
                    id="username"
                    name="username"
                    type="text"
                    placeholder="Enter your username"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="password" class="text-lg font-medium text-slate-700"
                    >Password</label
                >
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Enter your password"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <div class="flex flex-col gap-2">
                <label
                    for="firstName"
                    class="text-lg font-medium text-slate-700">First Name</label
                >
                <input
                    id="firstName"
                    name="firstName"
                    type="text"
                    placeholder="Enter your first name"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="lastName" class="text-lg font-medium text-slate-700"
                    >Last Name</label
                >
                <input
                    id="lastName"
                    name="lastName"
                    type="text"
                    placeholder="Enter your last name"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <div class="flex flex-col gap-2">
                <label
                    for="dateOfBirth"
                    class="text-lg font-medium text-slate-700"
                    >Date of Birth</label
                >
                <input
                    id="dateOfBirth"
                    name="dateOfBirth"
                    type="date"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="gender" class="text-lg font-medium text-slate-700"
                    >Gender</label
                >
                <select
                    id="gender"
                    name="gender"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                >
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="flex flex-col gap-2">
                <label for="phone" class="text-lg font-medium text-slate-700"
                    >Phone</label
                >
                <input
                    id="phone"
                    name="phone"
                    type="text"
                    placeholder="Enter your phone number"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="email" class="text-lg font-medium text-slate-700"
                    >Email</label
                >
                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="Enter your email"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <input
                type="submit"
                value="Register"
                class="bg-indigo-500 text-white rounded-lg p-3 cursor-pointer hover:bg-indigo-600"
            />
        </form>

        <p class="text-center text-slate-600">
            Already have an account?
            <a href="/login" class="text-indigo-400 font-medium hover:underline"
                >Log In</a
            >
        </p>
    </main>
</SectionWrapper>
