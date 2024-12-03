<script>
    import Header from "../../components/Header.svelte";
    import SectionWrapper from "../../components/SectionWrapper.svelte";
    import { fade } from "svelte/transition";
    import { cubicInOut } from "svelte/easing";
    import { goto } from "$app/navigation";
    import { onMount } from "svelte";
    import { getCookie } from "../../lib/api.js";

    onMount(() => {
        const username = getCookie("username");
        const role = getCookie("role");

        if (username && role) {
            switch (role) {
                case "Patient":
                    goto("/patient");
                    break;
                case "Secretary":
                    goto("/secretary");
                    break;
                case "LabStaff":
                    goto("/labstaff");
                    break;
                default:
                    break;
            }
        }
    });

    function showAlertBox(message, backgroundColor) {
        const alertBox = document.createElement("div");
        document.body.appendChild(alertBox);
        setTimeout(() => {
            document.body.removeChild(alertBox);
        }, 3000);
        alertBox.textContent = message;
        alertBox.style.position = "fixed";
        alertBox.style.top = "20px";
        alertBox.style.left = "50%";
        alertBox.style.transform = "translateX(-50%)";
        alertBox.style.backgroundColor = backgroundColor || "#4caf50";
        alertBox.style.color = "white";
        alertBox.style.padding = "10px 20px";
        alertBox.style.borderRadius = "5px";
        alertBox.style.zIndex = "1000";
    }
    async function handleLogin(event) {
        event.preventDefault(); // Prevent page reload
        const formData = new FormData(event.target);

        try {
            console.log("Sending login request...");
            const response = await fetch(
                "http://localhost:8080/database/Account/login_action.php",
                {
                    method: "POST",
                    body: formData,
                    headers: {
                        Accept: "application/json",
                    },
                },
            );

            const result = await response.json();
            console.log("Response received:", result);

            if (response.ok && result.status === "success") {
                document.cookie = `username=${result.username}; path=/`;
                document.cookie = `role=${result.role}; path=/`;
                document.cookie = `accountId=${result.accountId}; path=/`;
                showAlertBox("Login successful! Redirecting...", "#4caf50");
                switch (result.role) {
                    case "Patient":
                        goto("/patient");
                        break;
                    case "Secretary":
                        goto("/secretary");
                        break;
                    case "LabStaff":
                        goto("/labstaff");
                        break;
                    default:
                        console.error("Unknown role:", result.role);
                        showAlertBox(
                            "Unknown role. Please contact support.",
                            "#f44336",
                        );
                }
            } else {
                console.error("Login failed:", result.message);
                showAlertBox("Login failed. Please try again.", "#f44336");
            }
        } catch (error) {
            console.error("Error:", error);
            showAlertBox("An error occurred. Please try again.", "#f44336");
        }
    }
</script>

<SectionWrapper>
    <Header />
    <div
        class="flex flex-col gap-10 flex-1 items-center justify-center pb-10 md:pb-14"
        in:fade={{ delay: 200, duration: 200 }}
        out:fade={{ duration: 200, easing: cubicInOut }}
    >
        <h2
            class="sm:mb-20 md:mb-32 text-4xl sm:text-5xl md:text-6xl lg:text-7xl max-w-[1200px] mx-auto w-full text-center font-semibold"
        >
            Welcome Back to <span class="text-indigo-400">MedTest</span>
            <span class="text-slate-600">Lab </span>
        </h2>
        <form
            on:submit={handleLogin}
            class="flex flex-col gap-6 w-full max-w-[400px] mx-auto bg-white p-8 rounded-lg shadow-lg"
        >
            <div class="flex flex-col gap-2">
                <label
                    for="username"
                    class="text-lg font-medium text-slate-700"
                >
                    Username
                </label>
                <input
                    id="username"
                    name="username"
                    type="text"
                    placeholder="Enter your username"
                    class="border border-slate-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                />
            </div>
            <div class="flex flex-col gap-2">
                <label
                    for="password"
                    class="text-lg font-medium text-slate-700"
                >
                    Password
                </label>
                <input
                    id="password"
                    name="password"
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
            <a
                href="/register"
                class="text-indigo-400 font-medium hover:underline">Register</a
            >
        </p>
    </div>
</SectionWrapper>
