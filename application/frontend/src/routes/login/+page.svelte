<script>
    import Header from "../../components/Header.svelte";
    import SectionWrapper from "../../components/SectionWrapper.svelte";
    import { goto } from '$app/navigation';

    function gotoPatientLandingPage() {
        goto('/patient');
    }
</script>

<SectionWrapper>
    <Header />
    <div
        class="flex flex-col gap-10 flex-1 items-center justify-center pb-10 md:pb-14 "
    >
        <h2
            class="sm:mb-20 md:mb-32 text-4xl sm:text-5xl md:text-6xl lg:text-7xl max-w-[1200px] mx-auto w-full text-center font-semibold "
        >
            Welcome Back to <span class="text-indigo-400">MedTest</span> <span class="text-slate-600">Lab </span>
        </h2>
        <form method="GET" action="/patient"
            class="flex flex-col gap-6 w-full max-w-[400px] mx-auto bg-white p-8 rounded-lg shadow-lg"
        >
            <div class="flex flex-col gap-2">
                <label for="username" class="text-lg font-medium text-slate-700">
                    Username
                </label>
                <input
                    id="username"
                    type="text"
                    placeholder="Enter your username"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="password" class="text-lg font-medium text-slate-700">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    placeholder="Enter your password"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                />
            </div>
            <button 
                type="submit"
                class="bg-indigo-400 text-white font-medium sm:text-lg md:text-xl py-3 rounded-lg hover:bg-indigo-500 transition"
            >
                Log In
            </button>
        </form>
        <p class="text-center text-slate-600">
            Don't have an account? 
            <a href="/register" class="text-indigo-400 font-medium hover:underline">Register</a>
        </p>
    </div>
</SectionWrapper>