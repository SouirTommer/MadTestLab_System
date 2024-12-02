<script>
    
    import { goto } from '$app/navigation';

    // Functions to handle navigation
    function navigateToLogin() {
        goto('/login'); // Navigate to the login page
    }

    function navigateToRegister() {
        goto('/register'); // Navigate to the register page
    }
</script>

<div class="flex flex-col items-center gap-4">
    <button class="specialBtn w-32" on:click={navigateToLogin}>
        <p class="text-base sm:text-lg md:text-xl">Login</p>
    </button>
    <button class="specialBtnDark w-32 mt-2" on:click={navigateToRegister}>
        <p class="text-base sm:text-lg md:text-xl">Register</p>
    </button>
</div>
