<script>
    import SectionWrapper from "../../components/SectionWrapper.svelte";
    import Header from "../../components/Header.svelte";
    import {fade} from "svelte/transition";
    import { cubicInOut } from "svelte/easing";


    async function handleRegister(event) {
        event.preventDefault(); // Prevent page reload
        const formData = new FormData(event.target);

        const response = await fetch('/api/register', {
            method: 'POST',
            body: formData,
        });

        if (response.ok) {
            alert('Registration successful! Please log in.');
        } else {
            console.error('Registration failed');
            alert('Registration failed. Please try again.');
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
            Join <span class="text-indigo-400">MedTest</span> <span class=" text-slate-600">Lab</span> Today!
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
                <label for="username" class="text-lg font-medium text-slate-700">
                    Username
                </label>
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
                <label for="email" class="text-lg font-medium text-slate-700">
                    Email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="Enter your email"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="password" class="text-lg font-medium text-slate-700">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Create a password"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="confirm-password" class="text-lg font-medium text-slate-700">
                    Confirm Password
                </label>
                <input
                    id="confirm-password"
                    name="confirm-password"
                    type="password"
                    placeholder="Confirm your password"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                />
            </div>
            <button
                type="submit"
                class="bg-indigo-400 text-white font-medium py-3 rounded-lg hover:bg-indigo-500 transition"
            >
                Register
            </button>
        </form>
        <p class="text-center text-slate-600">
            Already have an account? 
            <a href="/login" class="text-indigo-400 font-medium hover:underline">Log In</a>
        </p>
    </main>
</SectionWrapper>
